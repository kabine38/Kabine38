<?php

class ImageService
{
    private string $originalPath;

    public function __construct()
    {
        $this->originalPath = __DIR__ . "/../../uploads/news/original/";

        if (!is_dir($this->originalPath)) {
            mkdir($this->originalPath, 0777, true);
        }
    }

    public function upload(array $file): ?string
    {
        if (!isset($file["tmp_name"]) || $file["error"] !== UPLOAD_ERR_OK) {
            return null;
        }

        $extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));

        $allowed = ["jpg", "jpeg", "png", "webp"];

        if (!in_array($extension, $allowed)) {
            return null;
        }

        $filename = uniqid("news_", true) . "." . $extension;

        $target = $this->originalPath . $filename;

        if (!move_uploaded_file($file["tmp_name"], $target)) {
            return null;
        }

        return $filename;
    }
}