<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Search;
use App\Http\Controllers\ProductController;
use App\Livewire\Account\Dashboard as AccountDashboard;
use App\Livewire\CompanyStorefront;


Route::get('/produits/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/commerce/{company}', CompanyStorefront::class)->name('company.storefront');
Route::view('/', 'welcome')->name('home');
Route::view('/mentions-legales', 'legal.mentions')->name('legal.mentions');
Route::view('/politique-de-confidentialite', 'legal.privacy')->name('legal.privacy');
Route::get('/recherche', Search::class)->name('search');
Route::get('/contact', \App\Livewire\Contact::class)->name('contact');
Route::view('/conditions-utilisation', 'legal.terms')->name('legal.terms');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', AccountDashboard::class)->name('dashboard');
    Route::get('compte/parametres', \App\Livewire\Account\Settings::class)->name('account.settings');
});


require __DIR__.'/settings.php';
require __DIR__.'/company-auth.php';
