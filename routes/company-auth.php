<?php

use Illuminate\Support\Facades\Route;

Route::middleware('guest:company')
    ->prefix('entreprise')
    ->name('company.')
    ->group(function () {
        Route::get('inscription', \App\Livewire\Company\Auth\Register::class)->name('register');
        Route::get('connexion', \App\Livewire\Company\Auth\Login::class)->name('login');
    });

Route::middleware('auth:company')
    ->prefix('entreprise')
    ->name('company.')
    ->group(function () {
        Route::post('deconnexion', function () {
            auth('company')->logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect('/');
        })->name('logout');

        Route::get('dashboard', function () {
            $company = auth('company')->user();
            $productIds = $company->products()->pluck('id');

            $stats = [
                'products_count' => $productIds->count(),
                'total_views' => \App\Models\ViewHistory::whereIn('product_id', $productIds)->count(),
                'total_favorites' => \App\Models\Favorite::whereIn('product_id', $productIds)->count(),
            ];

            return view('company.dashboard', compact('stats'));
        })->name('dashboard');

        Route::resource('produits', \App\Http\Controllers\Company\ProductController::class)
            ->parameters(['produits' => 'product'])
            ->names('products');
    });