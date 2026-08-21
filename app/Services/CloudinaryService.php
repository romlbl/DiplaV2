<?php

namespace App\Services;

use Cloudinary\Cloudinary;

class CloudinaryService
{
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('services.cloudinary.cloud_name'),
                'api_key' => config('services.cloudinary.api_key'),
                'api_secret' => config('services.cloudinary.api_secret'),
            ],
        ]);
    }

    /**
     * Upload une image et retourne son URL sécurisée.
     */
    public function upload(string $filePath, string $folder = 'dipla/products'): string
    {
        $result = $this->cloudinary->uploadApi()->upload($filePath, [
            'folder' => $folder,
        ]);

        return $result['secure_url'];
    }

    public function delete(string $publicId): void
    {
        $this->cloudinary->uploadApi()->destroy($publicId);
    }
}