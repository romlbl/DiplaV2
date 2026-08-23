<?php

namespace App\Livewire\Company;

use App\Models\Product;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductImageManager extends Component
{
    use WithFileUploads;

    public Product $product;

    public $newImages = [];

    public function mount(Product $product): void
    {
        Gate::forUser(auth('company')->user())->authorize('update', $product);

        $this->product = $product;
    }

    public function updatedNewImages(): void
    {
        $this->validate([
            'newImages.*' => ['image', 'max:5120'],
        ]);

        $cloudinary = app(CloudinaryService::class);
        $position = ($this->product->images()->max('position') ?? -1) + 1;

        foreach ($this->newImages as $file) {
            $url = $cloudinary->upload($file->getRealPath());

            $this->product->images()->create([
                'url' => $url,
                'position' => $position++,
            ]);
        }

        $this->newImages = [];
        $this->product->refresh();
    }

    public function deleteImage(int $imageId): void
    {
        $image = $this->product->images()->findOrFail($imageId);

        $publicId = pathinfo(parse_url($image->url, PHP_URL_PATH), PATHINFO_FILENAME);
        app(CloudinaryService::class)->delete('dipla/products/' . $publicId);

        $image->delete();
        $this->product->refresh();
    }

    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            $this->product->images()->where('id', $id)->update(['position' => $index]);
        }

        $this->product->refresh();
    }

    public function render()
    {
        return view('livewire.company.product-image-manager', [
            'images' => $this->product->images()->orderBy('position')->get(),
        ]);
    }
}