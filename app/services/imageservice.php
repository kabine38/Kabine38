<?php

class ImageService
{
    private string $originalPath;
    private string $mediumPath;
    private string $thumbnailPath;

    public function __construct()
    {
        $base = __DIR__ . "/../../uploads/news/";

        $this->originalPath  = $base . "original/";
        $this->mediumPath    = $base . "medium/";
        $this->thumbnailPath = $base . "thumbnails/";

        $this->createDirectory($this->originalPath);
        $this->createDirectory($this->mediumPath);
        $this->createDirectory($this->thumbnailPath);
    }

    private function createDirectory(string $path): void
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }
    private function loadImage(string $file)
{
    $info = getimagesize($file);

    if (!$info) {
        return null;
    }

    switch ($info["mime"]) {

        case "image/jpeg":
            return imagecreatefromjpeg($file);

        case "image/png":
            return imagecreatefrompng($file);

        case "image/webp":
            return imagecreatefromwebp($file);

        default:
            return null;
    }
}

private function saveJpeg($image, string $path, int $quality = 90): void
{
    imagejpeg($image, $path, $quality);
}

private function resize($source, int $newWidth)
{
    $width = imagesx($source);
    $height = imagesy($source);

    if ($width <= $newWidth) {
        return $source;
    }

    $newHeight = (int)(($height / $width) * $newWidth);

    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    imagecopyresampled(
        $newImage,
        $source,
        0,
        0,
        0,
        0,
        $newWidth,
        $newHeight,
        $width,
        $height
    );

    return $newImage;
}

private function createThumbnail($source, int $targetWidth = 640, int $targetHeight = 360)
{
    $sourceWidth = imagesx($source);
    $sourceHeight = imagesy($source);

    $sourceRatio = $sourceWidth / $sourceHeight;
    $targetRatio = $targetWidth / $targetHeight;

    if ($sourceRatio > $targetRatio) {

        // Bild ist breiter als 16:9
        $cropHeight = $sourceHeight;
        $cropWidth = (int) ($sourceHeight * $targetRatio);

        $sourceX = (int) (($sourceWidth - $cropWidth) / 2);
        $sourceY = 0;

    } else {

        // Bild ist höher als 16:9
        $cropWidth = $sourceWidth;
        $cropHeight = (int) ($sourceWidth / $targetRatio);

        $sourceX = 0;
        $sourceY = (int) (($sourceHeight - $cropHeight) / 2);
    }

    $thumbnail = imagecreatetruecolor(
        $targetWidth,
        $targetHeight
    );

    imagecopyresampled(
        $thumbnail,
        $source,
        0,
        0,
        $sourceX,
        $sourceY,
        $targetWidth,
        $targetHeight,
        $cropWidth,
        $cropHeight
    );

    return $thumbnail;
}

public function upload(array $file, ?array $cropFile = null): ?string
{

file_put_contents(
    __DIR__ . "/upload-debug.txt",
    date("H:i:s") . " upload() aufgerufen\n",
    FILE_APPEND
);
    if (!isset($file["tmp_name"]) || $file["error"] !== UPLOAD_ERR_OK) {
        return null;
    }

    $extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

    if (!in_array($extension, ["jpg", "jpeg", "png", "webp"])) {
        return null;
    }

    $filename = uniqid("news_", true) . ".jpg";

    $source = $this->loadImage($file["tmp_name"]);

    if (!$source) {
        return null;
    }

    $master = $this->resize($source, 2560);

   // Master (2560 px)
$this->saveJpeg(
    $master,
    $this->originalPath . $filename,
    90
);

// Medium (1600 px)
$medium = $this->resize($master, 1600);

$this->saveJpeg(
    $medium,
    $this->mediumPath . $filename,
    90
);

// Thumbnail erzeugen
// Falls ein manueller Crop vorhanden ist, diesen verwenden.
// Ansonsten automatischen 16:9-Crop aus dem Master erzeugen.

$thumbnailSource = null;

if (
    $cropFile !== null &&
    isset($cropFile["tmp_name"], $cropFile["error"]) &&
    $cropFile["error"] === UPLOAD_ERR_OK
) {
    $thumbnailSource = $this->loadImage(
        $cropFile["tmp_name"]
    );
}

if ($thumbnailSource) {

    // Manueller Crop:
    // auf 640 × 360 bringen
    $thumbnail = $this->createThumbnail(
        $thumbnailSource,
        640,
        360
    );

} else {

    // Kein manueller Crop:
    // automatischer 16:9-Zuschnitt
    $thumbnail = $this->createThumbnail(
        $master,
        640,
        360
    );
}

$this->saveJpeg(
    $thumbnail,
    $this->thumbnailPath . $filename,
    90
);
// Speicher freigeben
imagedestroy($source);

if ($master !== $source) {
    imagedestroy($master);
}

if ($medium !== $master) {
    imagedestroy($medium);
}

if ($thumbnailSource) {
    imagedestroy($thumbnailSource);
}

imagedestroy($thumbnail);

return $filename;
}

 }
