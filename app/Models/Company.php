<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Authenticatable
{
   use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'latitude',
        'longitude',
        'cover_image_url',
        'description',
        'opening_hours',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'opening_hours' => 'array',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function isOpenNow(): ?bool
    {
        $hours = $this->opening_hours;

        if (!$hours) {
            return null;
        }

        $dayKey = strtolower(now()->format('D')); // mon, tue, wed...
        $today = $hours[$dayKey] ?? null;

        if (!$today || ($today['closed'] ?? true)) {
            return false;
        }

        $open = $today['open'] ?? null;
        $close = $today['close'] ?? null;

        if (!$open || !$close) {
            return null;
        }

        $now = now()->format('H:i');

        return $now >= $open && $now <= $close;
    }

    public function scopeSearch(\Illuminate\Database\Eloquent\Builder $query, ?string $term): \Illuminate\Database\Eloquent\Builder
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
              ->orWhere('address', 'like', "%{$term}%");
        });
    }

    public function scopeNearby(\Illuminate\Database\Eloquent\Builder $query, float $lat, float $lng, ?float $radiusKm = null): \Illuminate\Database\Eloquent\Builder
    {
        $haversine = "(
            6371 * acos(
                cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?))
                + sin(radians(?)) * sin(radians(latitude))
            )
        )";

        $query
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw("companies.*, {$haversine} AS distance", [$lat, $lng, $lat]);

        if ($radiusKm !== null) {
            $query->whereRaw("{$haversine} <= ?", [$lat, $lng, $lat, $radiusKm]);
        }

        return $query;
    }
}