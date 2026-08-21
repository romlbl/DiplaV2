<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'price',
        'description',
        'type',
        'keywords',
        'address',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class)->whereNull('parent_id');
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function viewHistory(): HasMany
    {
        return $this->hasMany(ViewHistory::class);
    }

    public function averageRating(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    protected static function booted(): void
    {
        static::deleting(function (Product $product) {
            foreach ($product->images as $image) {
                // on extrait le public_id depuis l'URL Cloudinary pour pouvoir la supprimer
                $publicId = pathinfo(parse_url($image->url, PHP_URL_PATH), PATHINFO_FILENAME);
                app(\App\Services\CloudinaryService::class)->delete('dipla/products/' . $publicId);
            }

            $product->images()->delete();
        });
    }

}