<?php

$categoryStmt = $pdo->query("
    SELECT id, name, slug
    FROM categories
    ORDER BY id ASC
");

$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h1 class="page-title">Neue News erstellen</h1>

<p class="page-subtitle">
Erstelle einen neuen Beitrag für KABINE38.
</p>

<form id="newsForm" class="news-form" method="POST" enctype="multipart/form-data">

    <!-- ===========================
         TITEL
    ============================ -->

    <div class="form-group">

        <label for="title">Titel</label>

        <input
            type="text"
            id="title"
            name="title"
            placeholder="Überschrift eingeben..."
            autocomplete="off">

    </div>

    <!-- ===========================
         KURZBESCHREIBUNG
    ============================ -->

    <div class="form-group">

        <label for="excerpt">Kurzbeschreibung</label>

        <textarea
            id="excerpt"
            name="excerpt"
            rows="4"
            placeholder="Kurzer Teaser für die Startseite"></textarea>

    </div>

    <!-- ===========================
         ARTIKEL
    ============================ -->

    <div class="form-group">

        <label for="content">Artikel</label>

        <textarea
            id="content"
            name="content"
            rows="18"
            placeholder="Schreibe deinen Artikel..."></textarea>

    </div>

    <!-- ===========================
         BILDMANAGER
    ============================ -->

    <section class="image-manager">

        <div class="image-manager-header">

            <div>

                <h2>Bilder</h2>

                <p>
                    Lade beliebig viele Bilder hoch.
                    Später kannst du sie sortieren,
                    zuschneiden und ein Titelbild auswählen.
                </p>

            </div>

            <button
                type="button"
                id="selectImages"
                class="upload-button">

                + Bilder auswählen

            </button>

        </div>

        <div id="upload-area">

            <div class="upload-icon">

                📷

            </div>

            <h3>
                Bilder hier hineinziehen
            </h3>

            <p>
                oder auf „Bilder auswählen" klicken
            </p>

            <input
                type="file"
                id="images"
                name="images[]"
                accept="image/*"
                multiple
                hidden>

        </div>

        <div class="gallery-toolbar">

            <div>

                <strong>Galerie</strong>

                <span id="imageCounter">

                    Keine Bilder ausgewählt

                </span>

            </div>

            <div class="gallery-actions">

                <button
                    type="button"
                    id="selectAllImages">

                    Alle auswählen

                </button>

                <button
                    type="button"
                    id="deleteSelectedImages">

                    Auswahl löschen

                </button>

            </div>

        </div>

        <div id="preview-container">

            <!-- Bilder werden per JavaScript eingefügt -->

        </div>

    </section>

 <!-- ===========================
         BILD BEARBEITEN
    ============================ -->

    <section id="image-editor" class="image-editor hidden">

        <div class="editor-header">

            <div>

                <h2>Bild bearbeiten</h2>

                <p>
                    Wähle links ein Bild aus, um es zu bearbeiten.
                </p>

            </div>

            <div class="editor-badge">

                <span id="currentImageName">
                    Kein Bild ausgewählt
                </span>

            </div>

        </div>

        <div class="editor-layout">

            <div class="editor-preview">

                <img
                    id="editor-preview"
                    src=""
                    alt="Vorschau">

            </div>

            <div class="editor-sidebar">

                <div class="editor-card">

                    <h3>Titelbild</h3>

                    <label class="switch-row">

                        <input
                            type="radio"
                            name="heroImage"
                            id="heroImage">

                        <span>

                            Dieses Bild als Titelbild verwenden

                        </span>

                    </label>

                </div>

                <div class="editor-card">

                    <label for="caption">

                        Bildunterschrift

                    </label>

                    <textarea
                        id="caption"
                        rows="4"
                        placeholder="Optional"></textarea>

                </div>

                <div class="editor-card">

                    <label for="photographer">

                        Fotograf

                    </label>

                    <input
                        type="text"
                        id="photographer"
                        placeholder="z. B. Max Mustermann">

                </div>

                <div class="editor-card">

                    <h3>Bild bearbeiten</h3>

                    <div class="editor-buttons">

                        <button
                            type="button"
                            id="openCrop"
                            class="primary-btn">

                            ✂ Zuschneiden

                        </button>

                        <button
                            type="button"
                            id="rotateLeft">

                            ↺ Links drehen

                        </button>

                        <button
                            type="button"
                            id="rotateRight">

                            ↻ Rechts drehen

                        </button>

                    </div>

                </div>

                <div class="editor-card danger">

                    <button
                        type="button"
                        id="deleteCurrentImage">

                        🗑 Bild löschen

                    </button>

                </div>

            </div>

        </div>

    </section>

<!-- ===========================
     KATEGORIEN
============================ -->

<div class="form-group">

    <label>Kategorien</label>

    <div class="category-grid">

      <?php foreach ($categories as $category): ?>

        <label class="category-card">

            <input
                type="checkbox"
                name="categories[]"
                value="<?= (int) $category["id"] ?>">

            <span>
                <?= htmlspecialchars(
                    $category["name"],
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>
            </span>

        </label>

    <?php endforeach; ?>


</div>

<a
    href="categories.php"
    class="add-category-btn">

    + Kategorien verwalten

</a>

 <!-- ===========================
         OPTIONEN
    ============================ -->

    <div class="form-group">

        <label>Optionen</label>

        <div class="checkbox-grid">

            <label>
                <input
                    type="checkbox"
                    name="slider">

                ⭐ Im Hero-Slider anzeigen
            </label>

            <label>
                <input
                    type="checkbox"
                    name="premium">

                💎 Premiumartikel
            </label>

            <label>
                <input
                    type="checkbox"
                    name="featured">

                📌 Auf der Startseite hervorheben
            </label>

        </div>

    </div>

    <div class="form-actions">

        <button
            type="button"
            class="secondary-btn">

            Vorschau

        </button>

        <button
            type="submit"
            class="save-btn">

            News veröffentlichen

        </button>

    </div>

</form>

<!-- =======================================
     CROP MODAL
======================================= -->

<div id="cropModal" class="crop-modal">

    <div class="crop-window">

        <div class="crop-header">

            <h2>Bild zuschneiden</h2>

            <button
                type="button"
                id="closeCrop">

                ✕

            </button>

        </div>

        <div class="crop-body">

            <img
                id="cropImage"
                src=""
                alt="Crop">

        </div>

        <div class="crop-toolbar">

            <button
                type="button"
                id="crop169">

                Hero (16:9)

            </button>

            <button
                type="button"
                id="crop45">

                News (4:5)

            </button>

            <button
                type="button"
                id="crop11">

                Thumbnail (1:1)

            </button>

        </div>

        <div class="crop-footer">

            <button
                type="button"
                id="saveCrop"
                class="save-btn">

                Zuschneiden übernehmen

            </button>

        </div>

    </div>

</div>

