<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * Recherche texte pour les modèles liés à un produit (Review, Discussion).
 * Le modèle doit déclarer : protected static array $searchColumns = ['col1', ...];
 */
trait SearchesText
{
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        $term = trim($term);
        $words = array_filter(explode(' ', $term));
        $table = $query->getModel()->getTable();

        return $query->where(function ($q) use ($term, $words, $table) {
            // 1. Chaque mot doit apparaître dans au moins une colonne (accents et casse ignorés).
            $q->where(function ($allWords) use ($words, $table) {
                foreach ($words as $word) {
                    $like = '%'.addcslashes($word, '%_\\').'%';

                    $allWords->where(function ($oneWord) use ($like, $table) {
                        foreach (static::$searchColumns as $column) {
                            $oneWord->orWhereRaw("unaccent({$table}.{$column}) ILIKE unaccent(?)", [$like]);
                        }
                    });
                }
            });

            // 2. Ou le produit concerné correspond (réutilise la recherche produit, fautes tolérées).
            $q->orWhereHas('product', fn ($product) => $product->search($term));
        });
    }
}