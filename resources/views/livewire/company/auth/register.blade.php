<div class="flex flex-col gap-6">
    <div class="text-center">
        <h1 class="text-xl font-semibold text-[#14171A]">Inscription entreprise</h1>
        <p class="text-sm text-[#14171A]/70 mt-1">Créez votre compte pour publier vos produits et services.</p>
    </div>

    <form wire:submit="register" class="flex flex-col gap-4">
        <flux:input
            wire:model="name"
            label="Nom de l'entreprise"
            type="text"
            required
            autofocus
            autocomplete="organization"
        />

        <flux:input
            wire:model="email"
            label="Email"
            type="email"
            required
            autocomplete="email"
        />

        <flux:input
            wire:model="address"
            label="Adresse"
            type="text"
            required
            placeholder="L'adresse de votre commerce"
        />

        <flux:input
            wire:model="password"
            label="Mot de passe"
            type="password"
            required
            autocomplete="new-password"
            viewable
        />

        <flux:input
            wire:model="password_confirmation"
            label="Confirmer le mot de passe"
            type="password"
            required
            autocomplete="new-password"
            viewable
        />

        <flux:button type="submit" variant="primary" class="w-full">
            Créer mon compte entreprise
        </flux:button>
    </form>

    <div class="text-center text-sm text-[#14171A]/70">
        Déjà un compte ?
        <flux:link :href="route('company.login')" wire:navigate>Se connecter</flux:link>
    </div>
</div>