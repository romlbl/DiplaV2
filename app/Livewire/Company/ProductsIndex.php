<?php

namespace App\Livewire\Company;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.company')]
class ProductsIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $type = ''; // '' | produit | service

    #[Url]
    public string $sort = 'recent'; // recent | price_asc | price_desc

    protected $paginationTheme = 'tailwind';

    public function updated($property): void
    {
        if (in_array($property, ['search', 'type', 'sort'])) {
            $this->resetPage();
        }
    }

    public function deleteProduct(int $productId): void
    {
        $product = auth('company')->user()->products()->findOrFail($productId);
        $product->delete();

        session()->flash('success', 'Produit supprimé.');
    }

    public function render()
    {
        $query = auth('company')->user()->products()
            ->with(['images', 'reviews']);

        if ($this->search !== '') {
            $query->where('title', 'like', '%'.$this->search.'%');
        }

        if ($this->type !== '') {
            $query->where('type', $this->type);
        }

        match ($this->sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            default => $query->latest(),
        };

        $products = $query->paginate(12);

        return view('livewire.company.products-index', compact('products'));
    }
}