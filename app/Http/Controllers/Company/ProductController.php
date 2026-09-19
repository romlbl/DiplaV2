<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\ProductRequest;
use App\Models\Product;
use App\Services\CloudinaryService;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function __construct(protected CloudinaryService $cloudinary)
    {
    }

    public function index()
    {
        $products = auth('company')->user()
            ->products()
            ->latest()
            ->paginate(10);

        return view('company.products.index', compact('products'));
    }

    public function create()
    {
        return view('company.products.create');
    }

    public function store(ProductRequest $request)
    {
        $product = auth('company')->user()
            ->products()
            ->create($request->safe()->except('images'));

        $this->storeImages($product, $request);

        return redirect()
            ->route('company.products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function edit(Product $product)
    {
        Gate::forUser(auth('company')->user())->authorize('update', $product);

        return view('company.products.edit', compact('product'));
    }

    // L'autorisation (propriétaire du produit) est gérée dans ProductRequest::authorize().
    public function update(ProductRequest $request, Product $product)
    {
        $product->update($request->safe()->except('images'));

        $this->storeImages($product, $request);

        return redirect()
            ->route('company.products.index')
            ->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $product)
    {
        Gate::forUser(auth('company')->user())->authorize('delete', $product);

        $product->delete();

        return redirect()
            ->route('company.products.index')
            ->with('success', 'Produit supprimé.');
    }

    private function storeImages(Product $product, ProductRequest $request): void
    {
        foreach ($request->file('images', []) as $position => $file) {
            $product->images()->create([
                'url' => $this->cloudinary->upload($file->getRealPath()),
                'position' => $position,
            ]);
        }
    }
}