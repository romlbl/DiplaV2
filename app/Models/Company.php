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
}