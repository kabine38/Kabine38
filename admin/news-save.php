<?php


require_once("../app/services/ImageService.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"]);
    $excerpt = trim($_POST["excerpt"]);
    $content = $_POST["content"];

    $slider = isset($_POST["slider"]) ? 1 : 0;
    $premium = isset($_POST["premium"]) ? 1 : 0;
    $featured = isset($_POST["featured"]) ? 1 : 0;

/* ==========================================
   SLUG ERSTELLEN
========================================== */

$baseSlug = strtolower($title);

$baseSlug = preg_replace(
    "/[^a-z0-9]+/i",
    "-",
    $baseSlug
);

$baseSlug = trim($baseSlug, "-");

// Falls aus dem Titel kein gültiger Slug entsteht
if ($baseSlug === "") {
    $baseSlug = "news";
}

$slug = $baseSlug;
$counter = 2;


/* ==========================================
   PRÜFEN, OB SLUG BEREITS EXISTIERT
========================================== */

while (true) {

    $slugCheck = $pdo->prepare("
        SELECT id
        FROM news
        WHERE slug = :slug
        LIMIT 1
    ");

    $slugCheck->execute([
        "slug" => $slug
    ]);

    if (!$slugCheck->fetch()) {
        break;
    }

    $slug = $baseSlug . "-" . $counter;

    $counter++;
}

    $stmt = $pdo->prepare("
    INSERT INTO news
    (
        title,
        slug,
        teaser,
        content,
        author_id,
        is_premium,
        show_slider,
        is_pinned,
        status
    )
    VALUES
    (
        :title,
        :slug,
        :teaser,
        :content,
        :author,
        :premium,
        :slider,
        :featured,
        'published'
    )
");

$stmt->execute([

    "title" => $title,
    "slug" => $slug,
    "teaser" => $excerpt,
    "content" => $content,
    "author" => 1,
    "premium" => $premium,
    "slider" => $slider,
    "featured" => $featured

]);

$newsId = $pdo->lastInsertId();

/* ==========================================
   BILDER SPEICHERN
========================================== */

$imageService = new ImageService();



if (!empty($_FILES["images"]["name"][0])) {

    foreach ($_FILES["images"]["name"] as $index => $name) {

        $file = [

            "name" => $_FILES["images"]["name"][$index],
            "type" => $_FILES["images"]["type"][$index],
            "tmp_name" => $_FILES["images"]["tmp_name"][$index],
            "error" => $_FILES["images"]["error"][$index],
            "size" => $_FILES["images"]["size"][$index]

        ];

        $cropFile = null;

if (
    isset($_FILES["crops"]["name"][$index]) &&
    $_FILES["crops"]["error"][$index] === UPLOAD_ERR_OK
) {
    $cropFile = [
        "name" => $_FILES["crops"]["name"][$index],
        "type" => $_FILES["crops"]["type"][$index],
        "tmp_name" => $_FILES["crops"]["tmp_name"][$index],
        "error" => $_FILES["crops"]["error"][$index],
        "size" => $_FILES["crops"]["size"][$index]
    ];
}

$filename = $imageService->upload(
    $file,
    $cropFile
);
        if (!$filename) {
            continue;
        }

        /* ==========================================
   BILDDATEN AUS DEM FORMULAR
========================================== */

$imageData = $_POST["image_data"][$index] ?? [];

$caption = trim($imageData["caption"] ?? "");
$photographer = trim($imageData["photographer"] ?? "");
$isHero = !empty($imageData["hero"]) ? 1 : 0;


/* ==========================================
   BILD IN DATENBANK SPEICHERN
========================================== */

$stmt = $pdo->prepare("
    INSERT INTO news_images
    (
        news_id,
        image,
        caption,
        photographer,
        is_hero
    )
    VALUES
    (
        :news,
        :image,
        :caption,
        :photographer,
        :is_hero
    )
");

$stmt->execute([
    "news" => $newsId,
    "image" => $filename,
    "caption" => $caption,
    "photographer" => $photographer,
    "is_hero" => $isHero
]);
    }
}




echo "<div class='success-message'>News erfolgreich gespeichert.</div>";

}