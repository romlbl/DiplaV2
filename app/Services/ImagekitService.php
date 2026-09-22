<?php

namespace App\Services;

use ImageKit\ImageKit;

class ImageKitService
{
    protected ImageKit $imageKit;

    public function __construct()
    {
        $this->imageKit = new ImageKit(
            config('services.imagekit.public_key'),
            config('services.imagekit.private_key'),
            config('services.imagekit.url_endpoint'),
        );
    }

    /**
     * Upload une image et retourne son URL.
     */
    public function upload(string $filePath, string $folder = 'dipla/products'): string
    {
        $result = $this->imageKit->upload([
            'file' => base64_encode(file_get_contents($filePath)),
            'fileName' => uniqid('img_') . '.webp',
            'folder' => $folder,
            'useUniqueFileName' => true,
        ]);

        return $result->result->url;
    }

    /**
     * Supprime une image à partir de son URL (retrouve le fichier par son nom).
     */
    public function delete(string $url): void
    {
        $fileName = basename(parse_url($url, PHP_URL_PATH));

        $files = $this->imageKit->listFiles([
            'searchQuery' => 'name = "' . $fileName . '"',
        ]);

        if (!empty($files->result)) {
            $this->imageKit->deleteFile($files->result[0]->fileId);
        }
    }
}