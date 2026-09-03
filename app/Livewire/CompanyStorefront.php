<?php

namespace App\Livewire;

use App\Models\Company;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.public')]
class CompanyStorefront extends Component
{
    use WithPagination;

    public Company $company;

    #[Url]
    public string $search = '';

    protected $paginationTheme = 'tailwind';

    public function mount(Company $company): void
    {
        $this->company = $company;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $products = $this->company->products()
            ->when($this->search !== '', fn ($q) => $q->where('title', 'like', '%'.$this->search.'%'))
            ->with(['images', 'company', 'reviews'])
            ->latest()
            ->paginate(8);

        return view('livewire.company-storefront', [
            'products' => $products,
        ]);
    }
}