<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

/**
 * Validation commune à la création et à la modification d'un produit/service.
 */
class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        $company = $this->user('company');

        if (! $company) {
            return false;
        }

        // Création : toute entreprise connectée. Modification : uniquement le propriétaire.
        $product = $this->route('product');

        return $product ? Gate::forUser($company)->allows('update', $product) : true;
    }
    protected function prepareForValidation(): void
    {
        // Si "Prix variable" est coché (value="1"), on force price à null
        if ($this->boolean('price_variable')) {
            $this->merge([
                'price' => null,
            ]);
        }
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'price' => ['nullable', 'required_unless:price_variable,1', 'numeric', 'decimal:0,2', 'min:0', 'max:999999.99'],
            'description' => ['required', 'string', 'max:5000'],
            'type' => ['required', Rule::in(['produit', 'service'])],
            'keywords' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'images' => ['nullable', 'array', 'max:4'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }
}