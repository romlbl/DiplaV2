<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string'],
            'type' => ['required', 'in:produit,service'],
            'keywords' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'images' => ['nullable', 'array', 'max:4'],
            'images.*' => ['image', 'max:5120'], // 5 Mo max par image
        ]);

        $company = auth('company')->user();

        $product = $company->products()->create(collect($validated)->except('images')->toArray());

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $position => $file) {
                $url = $this->cloudinary->upload($file->getRealPath());

                $product->images()->create([
                    'url' => $url,
                    'position' => $position,
                ]);
            }
        }

        return redirect()
            ->route('company.products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    public function edit(Product $product)
    {
        Gate::forUser(auth('company')->user())->authorize('update', $product);

        return view('company.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        Gate::forUser(auth('company')->user())->authorize('update', $product);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string'],
            'type' => ['required', 'in:produit,service'],
            'keywords' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'images' => ['nullable', 'array', 'max:4'],
            'images.*' => ['image', 'max:5120'],
        ]);

        $product->update(collect($validated)->except('images')->toArray());

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $position => $file) {
                $url = $this->cloudinary->upload($file->getRealPath());

                $product->images()->create([
                    'url' => $url,
                    'position' => $position,
                ]);
            }
        }

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
}