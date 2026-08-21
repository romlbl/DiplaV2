<div class="flex flex-col gap-6">
    <div class="text-center">
        <h1 class="text-2xl font-semibold text-[#1E293B]">Inscription entreprise</h1>
        <p class="text-sm text-[#333333] mt-1">Créez votre compte pour publier vos produits et services.</p>
    </div>

    <form wire:submit="register" class="flex flex-col gap-4">
        <flux:input
            wire:model="name"
            label="Nom de l'entreprise"
            type="text"
            required
            autofocus
            autocomplete="organization"
            class="rounded-xl! border-[#E2E8F0]! bg-[#FDFBF7]! focus:border-[#1E3D59]! focus:ring-[#1E3D59]/20!"
        />

        <flux:input
            wire:model="email"
            label="Email"
            type="email"
            required
            autocomplete="email"
            class="rounded-xl! border-[#E2E8F0]! bg-[#FDFBF7]! focus:border-[#1E3D59]! focus:ring-[#1E3D59]/20!"
        />

        <div wire:ignore>
            <div data-location-picker class="relative">
                <label for="address" class="block text-sm font-medium text-[#1E293B] mb-1">Adresse</label>

                <input type="text" id="address" required autocomplete="off"
                    data-role="address-input"
                    wire:model="address"
                    placeholder="Commence à taper une adresse..."
                    class="w-full rounded-xl border border-[#E2E8F0] bg-[#FDFBF7] px-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20">

                <div data-role="suggestions"
                    style="z-index: 9999;"
                    class="hidden absolute mt-1 w-full rounded-xl border border-[#E2E8F0] bg-[#FAFAFF] shadow-lg max-h-60 overflow-y-auto">
                </div>

                <div data-role="map" class="mt-3 h-56 w-full rounded-xl border border-[#E2E8F0] overflow-hidden"></div>

                <p class="text-xs text-[#333333]/60 mt-1">Ajuste le repère sur la carte si besoin.</p>

                <input type="hidden" data-role="latitude" wire:model="latitude">
                <input type="hidden" data-role="longitude" wire:model="longitude">
            </div>
        </div>

        <flux:input
            wire:model="password"
            label="Mot de passe"
            type="password"
            required
            autocomplete="new-password"
            viewable
            class="rounded-xl! border-[#E2E8F0]! bg-[#FDFBF7]! focus:border-[#1E3D59]! focus:ring-[#1E3D59]/20!"
        />

        <flux:input
            wire:model="password_confirmation"
            label="Confirmer le mot de passe"
            type="password"
            required
            autocomplete="new-password"
            viewable
            class="rounded-xl! border-[#E2E8F0]! bg-[#FDFBF7]! focus:border-[#1E3D59]! focus:ring-[#1E3D59]/20!"
        />

        <flux:button type="submit" variant="primary" class="w-full rounded-full! bg-[#1E3D59]! hover:bg-[#16293F]! font-semibold!">
            Créer mon compte entreprise
        </flux:button>
    </form>

    <div class="text-center text-sm text-[#333333]">
        Déjà un compte ?
        <flux:link :href="route('company.login')" wire:navigate class="text-[#1E3D59]!">Se connecter</flux:link>
    </div>
</div>