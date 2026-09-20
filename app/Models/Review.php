<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Concerns\SearchesText;

class Review extends Model
{
    use HasFactory,SearchesText;

    protected static array $searchColumns = ['subject', 'content'];

    protected $fillable = [
        'user_id',
        'product_id',
        'subject',
        'content',
        'rating',
        'company_reply',
        'company_replied_at',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'company_replied_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}