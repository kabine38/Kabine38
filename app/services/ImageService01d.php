<?php

class ImageService
{
    private string $originalPath;
    private string $mediumPath;
    private string $thumbnailPath;

    public function __construct()
    {
        $base = __DIR__ . "/../../uploads/news/";

        $this->originalPath = $base . "original/";
        $this->mediumPath = $base . "medium/";
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
}