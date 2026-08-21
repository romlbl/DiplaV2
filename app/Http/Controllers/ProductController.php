<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ViewHistory;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        $product->load(['images', 'company', 'reviews.user']);

        // Historique de consultation : seulement pour un utilisateur particulier connecté
        if (auth()->check()) {
            ViewHistory::record(auth()->user(), $product);
        }

        $related = Product::query()
            ->where('id', '!=', $product->id)
            ->whereNotNull('keywords')
            ->get()
            ->filter(function ($candidate) use ($product) {
                $productKeywords = collect(explode(',', $product->keywords ?? ''))->map(fn ($k) => trim(strtolower($k)))->filter();
                $candidateKeywords = collect(explode(',', $candidate->keywords ?? ''))->map(fn ($k) => trim(strtolower($k)))->filter();

                return $productKeywords->intersect($candidateKeywords)->count() >= 2;
            })
            ->take(4);

        return view('products.show', compact('product', 'related'));
    }
}