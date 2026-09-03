<?php

namespace App\Livewire;

use App\Models\Company;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.public')]
class Search extends Component
{
    use WithPagination;

    #[Url]
    public string $mode = 'keyword'; // keyword | nearby | discover

    #[Url]
    public string $q = '';

    #[Url]
    public string $type = ''; // '' | produit | service | commerce

    #[Url]
    public ?float $maxPrice = null;

    #[Url]
    public ?float $maxDistance = null;

    #[Url(as: 'lat')]
    public ?float $userLat = null;

    #[Url(as: 'lng')]
    public ?float $userLng = null;

    protected $paginationTheme = 'tailwind';

    public function updated($property): void
    {
        if (in_array($property, ['mode', 'q', 'type', 'maxPrice', 'maxDistance'])) {
            $this->resetPage();
        }
    }

    public function setUserLocation(float $lat, float $lng): void
    {
        $this->userLat = $lat;
        $this->userLng = $lng;
    }

    public function setMode(string $mode): void
    {
        $this->mode = $mode;
        $this->resetPage();
    }

    public function render()
    {
        // "Commerces" cherche uniquement des entreprises.
        if ($this->type === 'commerce') {
            return view('livewire.search', [
                'products' => null,
                'companies' => $this->searchCompanies(),
            ]);
        }

        // "Tout" (type vide) : produits/services ET commerces, affichés
        // dans deux sections distinctes. Les autres types (produit/service)
        // ne cherchent que des produits.
        return view('livewire.search', [
            'products' => $this->searchProducts(),
            'companies' => $this->type === '' ? $this->searchCompanies() : null,
        ]);
    }

    protected function applyModeScopes($query, string $table)
    {
        switch ($this->mode) {
            case 'nearby':
                if ($this->userLat && $this->userLng) {
                    $query->nearby($this->userLat, $this->userLng, 20);
                    $query->orderBy('distance');
                } else {
                    $query->whereRaw('1 = 0');
                }
                break;

            case 'discover':
                if ($this->userLat && $this->userLng) {
                    $query->nearby($this->userLat, $this->userLng, $this->maxDistance ?? 200);
                } else {
                    $query->whereNotNull('latitude');
                }
                $query->inRandomOrder()->limit(1000);
                break;

            case 'keyword':
            default:
                $query->search($this->q ?: null);

                if ($this->userLat && $this->userLng) {
                    $query->nearby($this->userLat, $this->userLng, $this->maxDistance ?? 200);
                    $query->orderBy('distance');
                } else {
                    $query->latest();
                }
                break;
        }

        return $query;
    }
    
    protected function searchProducts()
    {
        $query = Product::query()->ofType($this->type ?: null)->maxPrice($this->maxPrice);

        return $this->applyModeScopes($query, 'products')
            ->with(['images', 'company', 'reviews'])
            ->paginate(12, ['*'], 'productsPage');
    }

    protected function searchCompanies()
    {
        return $this->applyModeScopes(Company::query(), 'companies')->paginate(12, ['*'], 'companiesPage');
    }
}