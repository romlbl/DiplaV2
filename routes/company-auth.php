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
            return view('company.dashboard');
        })->name('dashboard');
    });