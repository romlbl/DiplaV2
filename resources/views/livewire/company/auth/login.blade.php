<div class="flex flex-col gap-6">
    <div class="text-center">
        <h1 class="text-2xl font-semibold text-[#1E293B]">Connexion entreprise</h1>
    </div>

    <form wire:submit="login" class="flex flex-col gap-4">
        <flux:input
            wire:model="email"
            label="Email"
            type="email"
            required
            autofocus
            autocomplete="email"
            class="rounded-xl! border-[#E2E8F0]! bg-[#FDFBF7]! focus:border-[#1E3D59]! focus:ring-[#1E3D59]/20!"
        />

        <flux:input
            wire:model="password"
            label="Mot de passe"
            type="password"
            required
            autocomplete="current-password"
            viewable
            class="rounded-xl! border-[#E2E8F0]! bg-[#FDFBF7]! focus:border-[#1E3D59]! focus:ring-[#1E3D59]/20!"
        />

        <flux:checkbox wire:model="remember" label="Se souvenir de moi" />

        <flux:button type="submit" variant="primary" class="w-full rounded-full! bg-[#1E3D59]! hover:bg-[#16293F]! font-semibold!">
            Se connecter
        </flux:button>
    </form>

    <div class="text-center text-sm text-[#333333]">
        Pas encore de compte ?
        <flux:link :href="route('company.register')" wire:navigate class="text-[#1E3D59]!">Créer un compte entreprise</flux:link>
    </div>
</div>