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
            // 1. Chaque mot doit apparaître (contient OU flou) dans au moins une colonne.
            $q->where(function ($allWords) use ($words, $table) {
                foreach ($words as $word) {
                    $like = '%'.addcslashes($word, '%_\\').'%';

                    $allWords->where(function ($oneWord) use ($like, $word, $table) {
                        foreach (static::$searchColumns as $column) {
                            $oneWord->orWhereRaw("unaccent({$table}.{$column}) ILIKE unaccent(?)", [$like]);

                            // Flou : trouve le mot même mal orthographié ou proche (ex: "coiffeur" ~ "coiffure").
                            $oneWord->orWhereRaw("word_similarity(unaccent(?), unaccent({$table}.{$column})) > 0.25", [$word]);
                        }
                    });
                }
            });

            // 2. Ou le produit concerné correspond (réutilise la recherche produit, fautes tolérées).
            $q->orWhereHas('product', fn ($product) => $product->search($term));
        });
    }
}