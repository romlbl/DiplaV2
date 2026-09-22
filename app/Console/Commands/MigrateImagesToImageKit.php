<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\ProductImage;
use App\Services\ImageKitService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class MigrateImagesToImageKit extends Command
{
    protected $signature = 'images:migrate-to-imagekit';
    protected $description = 'Télécharge les images Cloudinary et les ré-uploade sur ImageKit';

    public function handle(ImageKitService $imageKit): int
    {
        // Photos produit
        ProductImage::query()->chunkById(50, function ($images) use ($imageKit) {
            foreach ($images as $image) {
                $newUrl = $this->reupload($image->url, $imageKit, 'dipla/products');

                if ($newUrl) {
                    $image->update(['url' => $newUrl]);
                    $this->info("Produit image #{$image->id} migrée");
                }
            }
        });

        // Devantures entreprise (couverture, carte, avatar)
        Company::query()->chunkById(50, function ($companies) use ($imageKit) {
            $fields = [
                'cover_image_url' => 'dipla/companies',
                'card_image_url' => 'dipla/companies/cards',
                'avatar_image_url' => 'dipla/companies/avatars',
            ];

            foreach ($companies as $company) {
                $update = [];

                foreach ($fields as $column => $folder) {
                    if ($company->{$column}) {
                        $newUrl = $this->reupload($company->{$column}, $imageKit, $folder);

                        if ($newUrl) {
                            $update[$column] = $newUrl;
                        }
                    }
                }

                if ($update) {
                    $company->update($update);
                    $this->info("Entreprise #{$company->id} migrée");
                }
            }
        });

        $this->info('Migration terminée.');

        return self::SUCCESS;
    }

    protected function reupload(string $oldUrl, ImageKitService $imageKit, string $folder): ?string
    {
        // Déjà migrée (URL ImageKit) : on saute.
        if (!str_contains($oldUrl, 'cloudinary.com')) {
            return null;
        }

        $tmpPath = tempnam(sys_get_temp_dir(), 'img');

        try {
            $response = Http::get($oldUrl);

            if (!$response->ok()) {
                $this->error("Échec téléchargement : {$oldUrl}");
                return null;
            }

            file_put_contents($tmpPath, $response->body());

            return $imageKit->upload($tmpPath, $folder);
        } catch (\Throwable $e) {
            $this->error("Erreur sur {$oldUrl} : {$e->getMessage()}");
            return null;
        } finally {
            @unlink($tmpPath);
        }
    }
}