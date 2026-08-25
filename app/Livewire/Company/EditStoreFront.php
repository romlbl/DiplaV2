<?php

namespace App\Livewire\Company;

use App\Models\Company;
use App\Services\CloudinaryService;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditStorefront extends Component
{
    use WithFileUploads;

    public Company $company;

    public string $name = '';
    public string $address = '';
    public ?float $latitude = null;
    public ?float $longitude = null;
    public string $description = '';

    public $newCoverImage = null;

    public array $openingHours = [];

    protected array $days = [
        'mon' => 'Lundi',
        'tue' => 'Mardi',
        'wed' => 'Mercredi',
        'thu' => 'Jeudi',
        'fri' => 'Vendredi',
        'sat' => 'Samedi',
        'sun' => 'Dimanche',
    ];

    public function mount(Company $company): void
    {
        $this->company = $company;
        $this->name = $company->name;
        $this->address = $company->address;
        $this->latitude = $company->latitude ? (float) $company->latitude : null;
        $this->longitude = $company->longitude ? (float) $company->longitude : null;
        $this->description = $company->description ?? '';

        $existing = $company->opening_hours ?? [];

        foreach ($this->days as $key => $label) {
            $this->openingHours[$key] = $existing[$key] ?? [
                'closed' => false,
                'open' => '09:00',
                'close' => '18:00',
            ];
        }
    }

    public function getDaysProperty(): array
    {
        return $this->days;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'description' => ['nullable', 'string', 'max:2000'],
            'newCoverImage' => ['nullable', 'image', 'max:5120'],
            'openingHours.*.closed' => ['boolean'],
            'openingHours.*.open' => ['nullable', 'string'],
            'openingHours.*.close' => ['nullable', 'string'],
        ]);

        $data = [
            'name' => $validated['name'],
            'address' => $validated['address'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'description' => $validated['description'],
            'opening_hours' => $this->openingHours,
        ];

        if ($this->newCoverImage) {
            $cloudinary = app(CloudinaryService::class);

            if ($this->company->cover_image_url) {
                $publicId = pathinfo(parse_url($this->company->cover_image_url, PHP_URL_PATH), PATHINFO_FILENAME);
                $cloudinary->delete('dipla/companies/'.$publicId);
            }

            $data['cover_image_url'] = $cloudinary->upload($this->newCoverImage->getRealPath(), 'dipla/companies');
        }

        $this->company->update($data);

        $this->dispatch('storefront-saved');
    }

    public function render()
    {
        return view('livewire.company.edit-storefront');
    }
}