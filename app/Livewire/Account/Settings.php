<?php

namespace App\Livewire\Account;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.user')]
class Settings extends Component
{
    public string $name = '';
    public string $address = '';
    public ?float $latitude = null;
    public ?float $longitude = null;

    public string $email = '';
    public string $current_password_email = '';

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(): void
    {
        $user = auth()->user();

        $this->name = $user->name;
        $this->address = $user->address ?? '';
        $this->latitude = $user->latitude ? (float) $user->latitude : null;
        $this->longitude = $user->longitude ? (float) $user->longitude : null;
        $this->email = $user->email;
    }

    public function updateProfile(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        auth()->user()->update($validated);

        session()->flash('settings-status', 'Profil mis à jour.');
    }

    public function updateEmail(): void
    {
        $user = auth()->user();

        $validated = $this->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'current_password_email' => ['required', 'current_password'],
        ]);

        $user->update(['email' => $validated['email']]);
        $this->reset('current_password_email');

        session()->flash('settings-status', 'Adresse email mise à jour.');
    }

    public function updatePassword(): void
    {
        $validated = $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        auth()->user()->update(['password' => Hash::make($validated['password'])]);
        $this->reset('current_password', 'password', 'password_confirmation');

        session()->flash('settings-status', 'Mot de passe mis à jour.');
    }

    public function render()
    {
        return view('livewire.account.settings');
    }
}