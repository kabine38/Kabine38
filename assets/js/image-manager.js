/* ==========================================
   KABINE38
   Image Manager
========================================== */

const captionInput =
    document.getElementById("caption");

const photographerInput =
    document.getElementById("photographer");

const heroCheckbox =
    document.getElementById("heroImage");


/* ==========================================
   DATEN IN DEN EDITOR LADEN
========================================== */

function loadImageData() {

    if (window.selectedImage === null) {

        clearEditor();
        return;

    }

    const image = window.images[window.selectedImage];

    captionInput.value = image.caption || "";
    photographerInput.value = image.photographer || "";
    heroCheckbox.checked = image.hero || false;

}

/* ==========================================
   DATEN AUS DEM EDITOR SPEICHERN
========================================== */

function saveImageData() {

    if (window.selectedImage === null) {
        return;
    }

    window.images[window.selectedImage].caption =
        captionInput.value;

    window.images[window.selectedImage].photographer =
        photographerInput.value;

    window.images[window.selectedImage].hero =
        heroCheckbox.checked;

}

/* ==========================================
   EDITOR EVENTS
========================================== */

captionInput.addEventListener("input", () => {

    saveImageData();

});

photographerInput.addEventListener("input", () => {

    saveImageData();

});



/* ==========================================
   HEROBILD SETZEN
========================================== */

heroCheckbox.addEventListener("change", () => {

    if (window.selectedImage === null) {
        return;
    }

    window.images.forEach(image => {

        image.hero = false;

    });

    window.images[window.selectedImage].hero = true;

    saveImageData();

});

/* ==========================================
   EDITOR LEEREN
========================================== */

function clearEditor() {

    captionInput.value = "";
    photographerInput.value = "";
    heroCheckbox.checked = false;

}

/* ==========================================
   EDITOR INITIALISIEREN
========================================== */

document.addEventListener("click", (e) => {

    const card = e.target.closest(".preview");

    if (!card) {
        return;
    }

    if (e.target.closest(".delete-image")) {
        return;
    }

    loadImageData();

});
/* ==========================================
   EDITOR AKTUALISIEREN
========================================== */

window.refreshImageEditor = function () {

    loadImageData();

};

/* ==========================================
   CROPPER ÖFFNEN
========================================== */

const openCropButton = document.getElementById("openCrop");
const cropModal = document.getElementById("cropModal");
const cropImage = document.getElementById("cropImage");
const closeCrop = document.getElementById("closeCrop");

let cropper = null;

openCropButton.addEventListener("click", () => {

    if (window.selectedImage === null) {
        return;
    }

    const image = window.images[window.selectedImage];

    cropImage.src = URL.createObjectURL(image.file);

    cropModal.style.display = "flex";

    if (cropper) {
        cropper.destroy();
    }

    cropper = new Cropper(cropImage, {

        aspectRatio: 16 / 9,
        viewMode: 1,
        autoCropArea: 1,
        responsive: true

    });

    /* ==========================================
   FORMATE
========================================== */

const crop169 = document.getElementById("crop169");
const crop45 = document.getElementById("crop45");
const crop11 = document.getElementById("crop11");

crop169.addEventListener("click", () => {

    if (!cropper) return;

    cropper.setAspectRatio(16 / 9);

});

crop45.addEventListener("click", () => {

    if (!cropper) return;

    cropper.setAspectRatio(4 / 5);

});

crop11.addEventListener("click", () => {

    if (!cropper) return;

    cropper.setAspectRatio(1);

});

});

closeCrop.addEventListener("click", () => {

    cropModal.style.display = "none";

    if (cropper) {

        cropper.destroy();
        cropper = null;

    }

});

const saveCropButton = document.getElementById("saveCrop");

saveCropButton.addEventListener("click", () => {

    if (!cropper || window.selectedImage === null) {
        return;
    }

    const canvas = cropper.getCroppedCanvas();

    window.images[window.selectedImage].crop = canvas.toDataURL("image/jpeg", 0.95);

    editorPreview.src = window.images[window.selectedImage].crop;

    cropModal.style.display = "none";

    cropper.destroy();

    cropper = null;

});

/* ==========================================
   INITIALISIERUNG
========================================== */

clearEditor();
