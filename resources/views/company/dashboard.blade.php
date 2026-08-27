<x-layouts::company>
    <div x-data="{ showDevantureModal: false }" class="space-y-8">

        {{-- Bannière + carte note --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
            {{-- Bannière devanture : photo de couverture si elle existe, sinon dégradé --}}
            <div class="lg:col-span-2 relative overflow-hidden rounded-2xl p-6 md:p-8 flex flex-col justify-end min-h-[220px] md:min-h-[260px] shadow-sm
                        {{ $company->cover_image_url ? '' : 'bg-gradient-to-br from-[#1E3D59] to-[#0F2438]' }}">

                @if($company->cover_image_url)
                    <img src="{{ $company->cover_image_url }}" alt=""
                         class="absolute inset-0 h-full w-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/30 to-transparent"></div>
                @else
                    <div class="absolute inset-0 opacity-10">
                        <svg class="h-full w-full" xmlns="http://www.w3.org/2000/svg" width="100%" height="100%">
                            <defs>
                                <pattern id="dashboard-grid" width="32" height="32" patternUnits="userSpaceOnUse">
                                    <path d="M32 0H0V32" fill="none" stroke="white" stroke-width="1"/>
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#dashboard-grid)" />
                        </svg>
                    </div>
                @endif

                <div class="relative">
                    <h1 class="text-xl md:text-2xl font-bold text-white">{{ $company->name }}</h1>
                    <p class="mt-2 max-w-lg text-sm text-white/70">
                        Votre vitrine digitale est actuellement visible. Personnalisez-la pour attirer plus de clients autour de vous.
                    </p>

                    <button type="button" @click="showDevantureModal = true"
                            class="mt-5 inline-flex items-center gap-2 rounded-full bg-white px-5 py-2.5 text-sm font-semibold text-[#1E3D59] transition hover:bg-white/90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                        </svg>
                        Gérer la devanture
                    </button>
                </div>
            </div>

            {{-- Carte note globale --}}
            <div class="rounded-2xl bg-[#1E3D59] p-6 flex flex-col justify-between shadow-sm">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-3xl font-mono font-semibold text-white">
                            {{ $stats['avg_rating'] }}<span class="text-base font-sans text-white/50">/5</span>
                        </span>
                        <div class="flex text-amber-400">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="text-base">{{ $i <= round($stats['avg_rating']) ? '★' : '☆' }}</span>
                            @endfor
                        </div>
                    </div>
                    <p class="text-sm text-white/60">Note globale ({{ $stats['reviews_count'] }} avis)</p>
                </div>

                <div class="mt-4 rounded-xl bg-white/10 p-3">
                    <p class="text-xs text-white/60 mb-1">Adresse actuelle</p>
                    <p class="text-sm text-white truncate">{{ $company->address }}</p>
                </div>
            </div>
        </div>

        {{-- Cartes statistiques --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-5 shadow-sm">
                <div class="flex items-center gap-2 text-[#333333]/60 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-sm font-medium">Vues cumulées</span>
                </div>
                <p class="text-3xl font-mono font-semibold text-[#1E293B]">{{ $stats['total_views'] }}</p>
            </div>

            <div class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-5 shadow-sm">
                <div class="flex items-center gap-2 text-[#333333]/60 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                    <span class="text-sm font-medium">Produits publiés</span>
                </div>
                <p class="text-3xl font-mono font-semibold text-[#1E293B]">{{ $stats['products_count'] }}</p>
            </div>

            <div class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-5 shadow-sm">
                <div class="flex items-center gap-2 text-[#333333]/60 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                    <span class="text-sm font-medium">Ajouts en favoris</span>
                </div>
                <p class="text-3xl font-mono font-semibold text-[#1E293B]">{{ $stats['total_favorites'] }}</p>
            </div>
        </div>

        <div>
            <a href="{{ route('company.products.index') }}"
               class="inline-flex items-center justify-center rounded-full bg-[#1E3D59] px-5 py-2 text-sm font-semibold text-[#FDFBF7] transition hover:bg-[#16293F]">
                Voir mes produits
            </a>
        </div>

        <div x-show="showDevantureModal" x-cloak x-transition
             @storefront-saved.window="window.location.reload()"
             class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto bg-black/40 px-4 py-10"
             style="display: none;">
            <div @click.outside="showDevantureModal = false"
                 class="my-auto w-full max-w-lg max-h-[85vh] overflow-y-auto rounded-2xl bg-[#FDFBF7] p-6 shadow-lg">
                <div class="sticky z-[10000] -top-6 -mx-6 -mt-6 mb-4 flex items-center justify-between border-b border-[#E2E8F0] bg-[#FDFBF7] px-6 py-4">
                    <h2 class="text-base font-semibold text-[#1E293B]">Gérer la devanture</h2>
                    <button type="button" @click="showDevantureModal = false" aria-label="Fermer" class="text-[#333333]/50 hover:text-[#333333]">✕</button>
                </div>

                <livewire:company.edit-storefront :company="$company" :key="$company->id" />
            </div>
        </div>
    </div>
</x-layouts::company>