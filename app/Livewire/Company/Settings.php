<?php

namespace App\Livewire\Company;

use App\Services\ImageKitService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.company')]
class Settings extends Component
{
    public string $email = '';
    public string $current_password_email = '';

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public string $delete_password = '';

    public function mount(): void
    {
        $this->email = auth('company')->user()->email;
    }

    public function updateEmail(): void
    {
        $company = auth('company')->user();

        $validated = $this->validate([
            'email' => ['required', 'email', 'max:255', Rule::unique('companies', 'email')->ignore($company->id)],
            'current_password_email' => ['required', 'current_password:company'],
        ]);

        $company->update(['email' => $validated['email']]);
        $this->reset('current_password_email');

        session()->flash('settings-status', 'Adresse email mise à jour.');
    }

    public function updatePassword(): void
    {
        $validated = $this->validate([
            'current_password' => ['required', 'current_password:company'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        auth('company')->user()->update(['password' => Hash::make($validated['password'])]);
        $this->reset('current_password', 'password', 'password_confirmation');

        session()->flash('settings-status', 'Mot de passe mis à jour.');
    }

    public function deleteAccount(ImageKitService $imageKit): void
    {
        $this->validate([
            'delete_password' => ['required', 'current_password:company'],
        ]);

        $company = auth('company')->user();

        // Supprime les 3 photos de la devanture sur ImageKit.
        foreach ([$company->cover_image_url, $company->card_image_url, $company->avatar_image_url] as $url) {
            if ($url) {
                $imageKit->delete($url);
            }
        }

        // Supprime chaque produit individuellement (pas de delete() en masse) pour
        // déclencher Product::booted() → nettoyage des photos produit sur ImageKit.
        // Les avis, questions, favoris et historique liés à chaque produit partent
        // automatiquement en cascade au niveau base de données (cascadeOnDelete).
        $company->products()->get()->each(fn ($product) => $product->delete());

        auth('company')->logout();
        $company->delete();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        $this->redirect('/', navigate: false);
    }

    public function render()
    {
        return view('livewire.company.settings');
    }
}