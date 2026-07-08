

/* ==========================================
   KABINE38 CMS
   News Editor
========================================== */

const uploadArea = document.getElementById("upload-area");
const selectImages = document.getElementById("selectImages");
const imageInput = document.getElementById("images");

const previewContainer = document.getElementById("preview-container");
const imageCounter = document.getElementById("imageCounter");

const imageEditor = document.getElementById("image-editor");
const editorPreview = document.getElementById("editor-preview");
const currentImageName = document.getElementById("currentImageName");

if (
    uploadArea &&
    selectImages &&
    imageInput &&
    previewContainer &&
    imageCounter &&
    imageEditor &&
    editorPreview &&
    currentImageName
) {

    /* ==========================================
       DATEN
    ========================================== */

    window.images = [];
    window.selectedImage = null;

    /* ==========================================
       BILDZÄHLER
    ========================================== */

    function updateCounter() {

        if (window.images.length === 0) {

            imageCounter.textContent = "Keine Bilder ausgewählt";

        } else if (window.images.length === 1) {

            imageCounter.textContent = "1 Bild ausgewählt";

        } else {

            imageCounter.textContent =
                window.images.length + " Bilder ausgewählt";

        }

    }

    /* ==========================================
       GALERIE LEEREN
    ========================================== */

    function clearGallery() {

        previewContainer.innerHTML = "";

    }

    /* ==========================================
       GALERIE RENDERN
    ========================================== */

    function renderGallery() {

        clearGallery();

        window.images.forEach((image, index) => {

    createPreview(image.file, index);

});

        updateCounter();

    }
 /* ==========================================
       VORSCHAU ERSTELLEN
    ========================================== */

    function createPreview(file, index) {

        const reader = new FileReader();

        reader.onload = function (event) {

            const card = document.createElement("div");

            card.className = "preview";
            card.dataset.index = index;

            card.innerHTML = `
                <button
                    type="button"
                    class="delete-image"
                    data-index="${index}">
                    ✕
                </button>

                <img
                    src="${window.images[index].crop || event.target.result}"
                     alt="${file.name}">

                <div class="preview-footer">
                    ${file.name}
                </div>
            `;

            previewContainer.appendChild(card);

        };

        reader.readAsDataURL(file);

    }

    /* ==========================================
       BILD HINZUFÜGEN
    ========================================== */

    function addImages(fileList) {

        Array.from(fileList).forEach(file => {

            if (!file.type.startsWith("image/")) {
                return;
            }

        window.images.push({

    file: file,

    caption: "",

    photographer: "",

    hero: false,

    crop: null

});

        });

        renderGallery();

    }

    /* ==========================================
       BILD LÖSCHEN
    ========================================== */

    function deleteImage(index) {

        window.images.splice(index, 1);

        if (window.selectedImage === index) {

            window.selectedImage = null;

            imageEditor.classList.add("hidden");

        }

        renderGallery();

    }
/* ==========================================
       BILD AUSWÄHLEN
    ========================================== */

    function selectImage(index) {

        window.selectedImage = index;

        document.querySelectorAll(".preview").forEach(card => {
            card.classList.remove("active");
        });

        const activeCard = document.querySelector(
            `.preview[data-index="${index}"]`
        );

        if (!activeCard) {
            return;
        }

        activeCard.classList.add("active");

        const image = activeCard.querySelector("img");

        editorPreview.src = image.src;

        currentImageName.textContent =
    window.images[index].file.name;

        imageEditor.classList.remove("hidden");

        if (typeof window.refreshImageEditor === "function") {
    window.refreshImageEditor();
}

    }

    /* ==========================================
       DRAG & DROP
    ========================================== */

    uploadArea.addEventListener("dragover", (e) => {

        e.preventDefault();

        uploadArea.classList.add("dragging");

    });

    uploadArea.addEventListener("dragleave", () => {

        uploadArea.classList.remove("dragging");

    });

    uploadArea.addEventListener("drop", (e) => {

        e.preventDefault();

        uploadArea.classList.remove("dragging");

        addImages(e.dataTransfer.files);

    });

    /* ==========================================
       DATEIAUSWAHL
    ========================================== */

    selectImages.addEventListener("click", () => {

        imageInput.click();

    });

    uploadArea.addEventListener("click", () => {

        imageInput.click();

    });

    imageInput.addEventListener("change", () => {

        addImages(imageInput.files);

        imageInput.value = "";

    });

/* ==========================================
       KLICKS IN DER GALERIE
    ========================================== */

    previewContainer.addEventListener("click", (e) => {

        const deleteButton = e.target.closest(".delete-image");

        if (deleteButton) {

            e.stopPropagation();

            const index = Number(deleteButton.dataset.index);

            deleteImage(index);

            return;

        }

        const card = e.target.closest(".preview");

        if (!card) {
            return;
        }

        const index = Number(card.dataset.index);

        selectImage(index);

    });

    /* ==========================================
       START
    ========================================== */

    updateCounter();

}



