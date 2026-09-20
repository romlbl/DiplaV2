@push('body-top')
    {{-- Fond Neat : fixe, couvre tout le viewport en permanence (donc aussi visible
         au niveau du footer une fois scrollé), uniquement sur la page d'accueil. --}}
    <canvas id="neat-home-background"
            class="fixed inset-0 z-0 h-screen w-screen"
            style="pointer-events: none;"
            aria-hidden="true"></canvas>
@endpush
<div class="max-w-md z-10 mx-auto">
    <div class="text-center">
        <h1 class="text-2xl font-semibold text-[#1E293B]">Connexion entreprise</h1>
    </div>

    <form wire:submit="login" class="flex flex-col gap-4" x-data="{ navigating: false }" @livewire:navigate.window="navigating = true">
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


        <flux:button type="submit" variant="primary" :loading="false"
                    wire:loading.attr="disabled" wire:target="login" x-bind:disabled="navigating"
                    class="w-full rounded-full! bg-[#1E3D59]! hover:bg-[#16293F]! text-[#FFFFFF]! font-semibold! disabled:opacity-60 disabled:cursor-not-allowed">
            <x-spinner wire:loading wire:target="login" />
            <x-spinner x-show="navigating" x-cloak />
            Se connecter
        </flux:button>

    </form>

    <div class="text-center text-sm text-[#333333] mt-4">
        Pas encore de compte ?
        <flux:link :href="route('company.register')" wire:navigate class="text-[#1E3D59]!">Créer un compte entreprise</flux:link>
    </div>

</div>