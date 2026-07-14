<?php

require_once("auth.php");
require_once("../config/database.php");

$db = new Database();
$pdo = $db->connect();


/* ==========================================
   KATEGORIE HINZUFÜGEN
========================================== */

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST["action"] ?? "";
    $name = trim($_POST["name"] ?? "");


    /* ==========================================
       KATEGORIE HINZUFÜGEN
    ========================================== */

    if ($action === "create" && $name !== "") {

        $slug = strtolower($name);

        $slug = preg_replace(
            "/[^a-z0-9]+/i",
            "-",
            $slug
        );

        $slug = trim($slug, "-");

        if ($slug === "") {
            $slug = "kategorie";
        }

        $stmt = $pdo->prepare("
            INSERT INTO categories
            (
                name,
                slug
            )
            VALUES
            (
                :name,
                :slug
            )
        ");

        $stmt->execute([
            "name" => $name,
            "slug" => $slug
        ]);

        $message = "Kategorie erfolgreich hinzugefügt.";
    }


    /* ==========================================
       KATEGORIE BEARBEITEN
    ========================================== */

    if ($action === "update" && $name !== "") {

        $categoryId = (int) ($_POST["category_id"] ?? 0);

        if ($categoryId > 0) {

            $slug = strtolower($name);

            $slug = preg_replace(
                "/[^a-z0-9]+/i",
                "-",
                $slug
            );

            $slug = trim($slug, "-");

            if ($slug === "") {
                $slug = "kategorie";
            }

            $stmt = $pdo->prepare("
                UPDATE categories
                SET
                    name = :name,
                    slug = :slug
                WHERE id = :id
            ");

            $stmt->execute([
                "name" => $name,
                "slug" => $slug,
                "id" => $categoryId
            ]);

            $message = "Kategorie erfolgreich geändert.";
        }
    }
}


/* ==========================================
   KATEGORIE LÖSCHEN
========================================== */

if ($action === "delete") {

    $categoryId = (int) ($_POST["category_id"] ?? 0);

    if ($categoryId > 0) {

        $stmt = $pdo->prepare("
            DELETE FROM categories
            WHERE id = :id
        ");

        $stmt->execute([
            "id" => $categoryId
        ]);

        $message = "Kategorie erfolgreich gelöscht.";
    }
}

/* ==========================================
   KATEGORIEN LADEN
========================================== */

$stmt = $pdo->query("
    SELECT
        id,
        name,
        slug
    FROM categories
    ORDER BY id ASC
");

$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);


include("header.php");

?>

<div class="admin-content">


 <a
    href="news.php"
    class="back-btn">

    ← Zurück zu News

</a>

    <div class="page-header">

        <div>

            <h1>Kategorien verwalten</h1>

            <p>
                Erstelle und verwalte die Kategorien
                für deine News.
            </p>

        </div>

    </div>


    <?php if ($message !== ""): ?>

        <div class="success-message">

            <?= htmlspecialchars(
                $message,
                ENT_QUOTES,
                "UTF-8"
            ) ?>

        </div>

    <?php endif; ?>


    <div class="form-group">

        <h2>Neue Kategorie</h2>

        <form method="POST">

        <input
    type="hidden"
    name="action"
    value="create">

            <label for="name">
                Name der Kategorie
            </label>

            <input
                type="text"
                id="name"
                name="name"
                placeholder="z. B. Regionalliga"
                required>

            <button
                type="submit"
                class="save-btn">

                Kategorie hinzufügen

            </button>

        </form>

    </div>


    <div class="form-group">

        <h2>Vorhandene Kategorien</h2>

        <?php if (empty($categories)): ?>

            <p>
                Noch keine Kategorien vorhanden.
            </p>

        <?php else: ?>

            <?php foreach ($categories as $category): ?>

    <div class="category-admin-item">

        <form method="POST" class="category-edit-form">

            <input
                type="hidden"
                name="action"
                value="update">

            <input
                type="hidden"
                name="category_id"
                value="<?= (int) $category["id"] ?>">

            <input
                type="text"
                name="name"
                value="<?= htmlspecialchars(
                    $category["name"],
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>"
                required>

            <span class="category-slug">
                /<?= htmlspecialchars(
                    $category["slug"],
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>
            </span>

            <button
                type="submit"
                class="secondary-btn">

                Speichern

            </button>

        </form>


        <form
    method="POST"
    class="category-delete-form"
    onsubmit="return confirm('Kategorie wirklich löschen? Die Kategorie-Zuordnungen zu bestehenden News werden ebenfalls entfernt.');">

    <input
        type="hidden"
        name="action"
        value="delete">

    <input
        type="hidden"
        name="category_id"
        value="<?= (int) $category["id"] ?>">

    <button
        type="submit"
        class="delete-btn">

        Löschen

    </button>

</form>

    </div>

<?php endforeach; ?>

        <?php endif; ?>

    </div>

</div>

<?php include("footer.php"); ?>