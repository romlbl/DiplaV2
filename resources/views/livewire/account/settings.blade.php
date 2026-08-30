<div>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-[#1E293B]">Paramètres</h1>
        <p class="text-sm text-[#333333]/70 mt-1">Gère ton profil, ton adresse email et ton mot de passe.</p>
    </div>

    @if(session('settings-status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 mb-6">
            {{ session('settings-status') }}
        </div>
    @endif

    <div class="flex flex-col gap-6 max-w-xl">

        {{-- Nom & adresse --}}
        <div class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-6 shadow-sm">
            <h2 class="text-base font-semibold text-[#1E293B] mb-4">Profil</h2>

            <form wire:submit="updateProfile" class="flex flex-col gap-4">
                <div>
                    <label for="settings-name" class="block text-sm font-medium text-[#1E293B] mb-1">Nom</label>
                    <input type="text" id="settings-name" wire:model="name"
                           class="w-full rounded-xl border border-[#E2E8F0] bg-white px-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20">
                    @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div wire:ignore>
                    <div data-location-picker class="relative">
                        <label for="settings-address" class="block text-sm font-medium text-[#1E293B] mb-1">Adresse</label>

                        <input type="text" id="settings-address" autocomplete="off"
                            data-role="address-input"
                            wire:model="address"
                            placeholder="Commence à taper une adresse..."
                            class="w-full rounded-xl border border-[#E2E8F0] bg-white px-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20">

                        <div data-role="suggestions" style="z-index: 9999;"
                            class="hidden absolute mt-1 w-full rounded-xl border border-[#E2E8F0] bg-[#FAFAFF] shadow-lg max-h-60 overflow-y-auto"></div>

                        <div data-role="map" class="mt-3 h-48 w-full rounded-xl border border-[#E2E8F0] overflow-hidden"></div>

                        <input type="hidden" data-role="latitude" wire:model="latitude">
                        <input type="hidden" data-role="longitude" wire:model="longitude">
                    </div>
                </div>
                @error('address') <p class="text-sm text-red-600 -mt-2">{{ $message }}</p> @enderror

                <button type="submit"
                        class="self-start rounded-full bg-[#1E3D59] px-5 py-2 text-sm font-semibold text-[#FDFBF7] hover:bg-[#16293F] transition">
                    Enregistrer le profil
                </button>
            </form>
        </div>

        {{-- Adresse email --}}
        <div class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-6 shadow-sm">
            <h2 class="text-base font-semibold text-[#1E293B] mb-4">Adresse email</h2>

            <form wire:submit="updateEmail" class="flex flex-col gap-4">
                <div>
                    <label for="settings-email" class="block text-sm font-medium text-[#1E293B] mb-1">Nouvel email</label>
                    <input type="email" id="settings-email" wire:model="email"
                           class="w-full rounded-xl border border-[#E2E8F0] bg-white px-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20">
                    @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="settings-email-password" class="block text-sm font-medium text-[#1E293B] mb-1">Mot de passe actuel</label>
                    <input type="password" id="settings-email-password" wire:model="current_password_email"
                           class="w-full rounded-xl border border-[#E2E8F0] bg-white px-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20">
                    @error('current_password_email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <button type="submit"
                        class="self-start rounded-full bg-[#1E3D59] px-5 py-2 text-sm font-semibold text-[#FDFBF7] hover:bg-[#16293F] transition">
                    Mettre à jour l'email
                </button>
            </form>
        </div>

        {{-- Mot de passe --}}
        <div class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-6 shadow-sm">
            <h2 class="text-base font-semibold text-[#1E293B] mb-4">Mot de passe</h2>

            <form wire:submit="updatePassword" class="flex flex-col gap-4">
                <div>
                    <label for="settings-current-password" class="block text-sm font-medium text-[#1E293B] mb-1">Mot de passe actuel</label>
                    <input type="password" id="settings-current-password" wire:model="current_password"
                           class="w-full rounded-xl border border-[#E2E8F0] bg-white px-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20">
                    @error('current_password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="settings-new-password" class="block text-sm font-medium text-[#1E293B] mb-1">Nouveau mot de passe</label>
                    <input type="password" id="settings-new-password" wire:model="password"
                           class="w-full rounded-xl border border-[#E2E8F0] bg-white px-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20">
                    @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="settings-new-password-confirmation" class="block text-sm font-medium text-[#1E293B] mb-1">Confirmer le nouveau mot de passe</label>
                    <input type="password" id="settings-new-password-confirmation" wire:model="password_confirmation"
                           class="w-full rounded-xl border border-[#E2E8F0] bg-white px-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20">
                </div>

                <button type="submit"
                        class="self-start rounded-full bg-[#1E3D59] px-5 py-2 text-sm font-semibold text-[#FDFBF7] hover:bg-[#16293F] transition">
                    Mettre à jour le mot de passe
                </button>
            </form>
        </div>
    </div>
</div>