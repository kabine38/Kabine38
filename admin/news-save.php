<?php

require_once("../app/services/ImageService.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"]);
    $excerpt = trim($_POST["excerpt"]);
    $content = $_POST["content"];

    $slider = isset($_POST["slider"]) ? 1 : 0;
    $premium = isset($_POST["premium"]) ? 1 : 0;
    $featured = isset($_POST["featured"]) ? 1 : 0;

    $slug = strtolower($title);

    $slug = preg_replace("/[^a-z0-9]+/i", "-", $slug);

    $slug = trim($slug, "-");

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

        $filename = $imageService->upload($file);

        if (!$filename) {
            continue;
        }

        $stmt = $pdo->prepare("
            INSERT INTO news_images
            (
                news_id,
                image
            )
            VALUES
            (
                :news,
                :image
            )
        ");

        $stmt->execute([

            "news" => $newsId,
            "image" => $filename

        ]);

    }

}

echo "<div class='success-message'>News erfolgreich gespeichert.</div>";

}