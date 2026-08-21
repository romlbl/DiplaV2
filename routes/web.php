<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Search;
use App\Http\Controllers\ProductController;
use App\Livewire\Account\Dashboard as AccountDashboard;


Route::get('/produits/{product}', [ProductController::class, 'show'])->name('products.show');

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', AccountDashboard::class)->name('dashboard');
});

Route::get('/recherche', Search::class)->name('search');

require __DIR__.'/settings.php';
require __DIR__.'/company-auth.php';
