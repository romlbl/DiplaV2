<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\Product;

class ProductPolicy
{
    /**
     * Détermine si l'entreprise possède ce produit.
     */
    public function update(Company $company, Product $product): bool
    {
        return $company->id === $product->company_id;
    }

    public function delete(Company $company, Product $product): bool
    {
        return $company->id === $product->company_id;
    }
}