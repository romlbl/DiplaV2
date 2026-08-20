<div class="flex flex-col gap-6">
    <div class="text-center">
        <h1 class="text-xl font-semibold text-[#14171A]">Connexion entreprise</h1>
    </div>

    <form wire:submit="login" class="flex flex-col gap-4">
        <flux:input
            wire:model="email"
            label="Email"
            type="email"
            required
            autofocus
            autocomplete="email"
        />

        <flux:input
            wire:model="password"
            label="Mot de passe"
            type="password"
            required
            autocomplete="current-password"
            viewable
        />

        <flux:checkbox wire:model="remember" label="Se souvenir de moi" />

        <flux:button type="submit" variant="primary" class="w-full">
            Se connecter
        </flux:button>
    </form>

    <div class="text-center text-sm text-[#14171A]/70">
        Pas encore de compte ?
        <flux:link :href="route('company.register')" wire:navigate>Créer un compte entreprise</flux:link>
    </div>
</div>