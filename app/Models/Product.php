<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

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
    /**
     * Recherche plein texte optimisée (FTS + Préfixes + Fautes de frappe).
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        $term = trim($term);

        return $query->where(function ($q) use ($term) {
            // Full-text classique (rapide, gère pluriel/accents/stemming français)
            $q->whereRaw("search_vector @@ websearch_to_tsquery('french', ?)", [$term]);

            // Flou façon TNTSearch : compare "mot par mot" contre titre/mots-clés/description.
            // word_similarity trouve un mot proche même dans un texte plus long ("barber" ~ "coiffeur barber shop").
            $q->orWhereRaw("word_similarity(unaccent(?), unaccent(title)) > 0.25", [$term]);
            $q->orWhereRaw("word_similarity(unaccent(?), unaccent(coalesce(keywords, ''))) > 0.25", [$term]);
            $q->orWhereRaw("word_similarity(unaccent(?), unaccent(coalesce(description, ''))) > 0.2", [$term]);
        });
    }

    /**
     * Filtre par distance (km) depuis un point donné, formule de Haversine.
     * Ajoute aussi une colonne calculée "distance" utilisable pour trier.
     */
    public function scopeNearby(Builder $query, float $lat, float $lng, ?float $radiusKm = null): Builder
    {
        $haversine = "(
            6371 * acos(
                LEAST(1, GREATEST(-1,
                    cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?))
                    + sin(radians(?)) * sin(radians(latitude))
                ))
            )
        )";

        $query
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw("products.*, {$haversine} AS distance", [$lat, $lng, $lat]);

        if ($radiusKm !== null) {
            // WHERE (pas HAVING) : c'est un filtre ligne par ligne, pas un filtre post-agrégation.
            $query->whereRaw("{$haversine} <= ?", [$lat, $lng, $lat, $radiusKm]);
        }

        return $query;
    }

    public function scopeOfType(Builder $query, ?string $type): Builder
    {
        if (blank($type)) {
            return $query;
        }

        return $query->where('type', $type);
    }

    public function scopeMaxPrice(Builder $query, ?float $price): Builder
    {
        if (blank($price)) {
            return $query;
        }

        return $query->where('price', '<=', $price);
    }

    public function priceLabel(): string
    {
        return $this->price !== null
            ? number_format($this->price, 2) . ' €'
            : 'Variable';
    }


}