<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViewHistory extends Model
{
    use HasFactory;

    protected $table = 'view_history';
    const UPDATED_AT = null;
    const CREATED_AT = 'viewed_at';

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public static function record(User $user, Product $product): void
    {
        static::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $idsToKeep = static::where('user_id', $user->id)
            ->orderByDesc('viewed_at')
            ->limit(7)
            ->pluck('id');

        static::where('user_id', $user->id)
            ->whereNotIn('id', $idsToKeep)
            ->delete();
    }
}