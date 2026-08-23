<x-layouts::public>
    <div class="max-w-md mx-auto py-12" x-data="{ tab: '{{ $tab === 'company' ? 'company' : 'user' }}' }">

        <div class="flex rounded-full border border-[#E2E8F0] p-1 mb-8">
            <button @click="tab = 'user'"
                    :class="tab === 'user' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'text-[#333333]/50'"
                    class="flex-1 rounded-full px-4 py-2 text-sm font-medium transition">
                Utilisateur
            </button>
            <button @click="tab = 'company'"
                    :class="tab === 'company' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'text-[#333333]/50'"
                    class="flex-1 rounded-full px-4 py-2 text-sm font-medium transition">
                Commerce
            </button>
        </div>

        <div class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-6 shadow-sm">

            {{-- Onglet Utilisateur --}}
            <div x-show="tab === 'user'" x-cloak>
                <h1 class="text-xl font-semibold text-[#1E293B] mb-4">Connexion</h1>

                <x-auth-session-status class="mb-4" :status="session('status')" />
                <x-passkey-verify />

                <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-4">
                    @csrf

                    <flux:input
                        name="email"
                        label="Email"
                        :value="old('email')"
                        type="email"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="email@exemple.com"
                        class="rounded-xl! border-[#E2E8F0]! bg-[#FDFBF7]! focus:border-[#1E3D59]! focus:ring-[#1E3D59]/20!"
                    />

                    <div class="relative">
                        <flux:input
                            name="password"
                            label="Mot de passe"
                            type="password"
                            required
                            autocomplete="current-password"
                            viewable
                            class="rounded-xl! border-[#E2E8F0]! bg-[#FDFBF7]! focus:border-[#1E3D59]! focus:ring-[#1E3D59]/20!"
                        />
                        @if (Route::has('password.request'))
                            <flux:link class="absolute top-0 right-0 text-sm" :href="route('password.request')" wire:navigate>
                                Mot de passe oublié ?
                            </flux:link>
                        @endif
                    </div>

                    <flux:checkbox name="remember" label="Se souvenir de moi" :checked="old('remember')" />

                    <flux:button variant="primary" type="submit" class="w-full rounded-full! bg-[#1E3D59]! hover:bg-[#16293F]! font-semibold!">
                        Se connecter
                    </flux:button>
                </form>

                <div class="text-center text-sm text-[#333333] mt-4">
                    Pas encore de compte ?
                    <flux:link :href="route('register')" wire:navigate class="text-[#1E3D59]!">Inscription</flux:link>
                </div>
            </div>

            {{-- Onglet Commerce --}}
            <div x-show="tab === 'company'" x-cloak>
                <livewire:company.auth.login />
            </div>

        </div>
    </div>
</x-layouts::public>