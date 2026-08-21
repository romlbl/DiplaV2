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

        <flux:input
            wire:model="address"
            label="Adresse"
            type="text"
            required
            placeholder="L'adresse de votre commerce"
            class="rounded-xl! border-[#E2E8F0]! bg-[#FDFBF7]! focus:border-[#1E3D59]! focus:ring-[#1E3D59]/20!"
        />

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