<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Search;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::get('/recherche', Search::class)->name('search');

require __DIR__.'/settings.php';
require __DIR__.'/company-auth.php';
