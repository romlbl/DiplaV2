<?php

namespace App\Livewire;

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
    public string $type = ''; // '' | produit | service

    #[Url]
    public ?float $maxPrice = null;

    #[Url]
    public ?float $maxDistance = null;

    public ?float $userLat = null;
    public ?float $userLng = null;

    protected $paginationTheme = 'tailwind';

    public function updated($property): void
    {
        // Toute modification de filtre revient à la page 1
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
        $query = Product::query()->ofType($this->type ?: null)->maxPrice($this->maxPrice);

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

        $products = $query->with('images')->paginate(12);

        return view('livewire.search', compact('products'));
    }
}