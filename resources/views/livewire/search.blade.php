<div
    x-data="{ locating: false, locationError: false }"
    x-init="
        if ($wire.userLat && $wire.userLng) {
            locating = false;
        } else {
            locating = true;
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        $wire.setUserLocation(position.coords.latitude, position.coords.longitude);
                        locating = false;
                    },
                    () => { locationError = true; locating = false; },
                    { timeout: 8000 }
                );
            } else {
                locationError = true;
                locating = false;
            }
        }
    "
>
    {{-- Bascule des modes --}}
    <div class="flex gap-2 mb-6 overflow-x-auto pb-1">
        <button wire:click="setMode('keyword')"
                class="shrink-0 rounded-full px-5 py-2 text-sm font-medium transition {{ $mode === 'keyword' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'border border-[#E2E8F0] text-[#1E293B] hover:bg-[#FAFAFF]' }}">
            Recherche
        </button>
        <button wire:click="setMode('nearby')"
                class="shrink-0 rounded-full px-5 py-2 text-sm font-medium transition {{ $mode === 'nearby' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'border border-[#E2E8F0] text-[#1E293B] hover:bg-[#FAFAFF]' }}">
            À proximité
        </button>
        <button wire:click="setMode('discover')"
                class="shrink-0 rounded-full px-5 py-2 text-sm font-medium transition {{ $mode === 'discover' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'border border-[#E2E8F0] text-[#1E293B] hover:bg-[#FAFAFF]' }}">
            Découvrir
        </button>
    </div>

    {{-- Statut géolocalisation --}}
    <div x-show="locating" class="text-sm text-[#333333]/60 mb-4">Localisation en cours...</div>
    <div x-show="locationError" class="text-sm text-[#4A3B5C] mb-4">
        Position non disponible — le mode "à proximité" en a besoin, mais tu peux chercher par mot-clé.
    </div>

    <div class="flex flex-col md:flex-row gap-6 md:gap-8">

        {{-- Sidebar filtres --}}
        <aside class="w-full md:w-72 md:shrink-0">
            <div class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-5 shadow-sm md:sticky md:top-24 flex flex-col gap-5">

                {{-- Champ recherche (mode mot-clé uniquement) --}}
                @if($mode === 'keyword')
                    <div>
                        <label for="q" class="block text-sm font-medium text-[#1E293B] mb-1">Recherche</label>
                        <input type="text" id="q" wire:model.live.debounce.400ms="q"
                               placeholder="boulangerie, réparation vélo..."
                               class="w-full rounded-xl border border-[#E2E8F0] bg-[#FDFBF7] px-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20">
                    </div>
                @endif

                {{-- Catégorie --}}
                <div>
                    <label class="block text-sm font-medium text-[#1E293B] mb-2">Catégorie</label>
                    <div class="flex flex-wrap gap-2">
                        <button wire:click="$set('type', '')"
                                class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $type === '' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'border border-[#E2E8F0] text-[#1E293B] hover:bg-white' }}">
                            Tout
                        </button>
                        <button wire:click="$set('type', 'produit')"
                                class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $type === 'produit' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'border border-[#E2E8F0] text-[#1E293B] hover:bg-white' }}">
                            Produits
                        </button>
                        <button wire:click="$set('type', 'service')"
                                class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $type === 'service' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'border border-[#E2E8F0] text-[#1E293B] hover:bg-white' }}">
                            Services
                        </button>
                        <button wire:click="$set('type', 'commerce')"
                                class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $type === 'commerce' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'border border-[#E2E8F0] text-[#1E293B] hover:bg-white' }}">
                            Commerces
                        </button>
                    </div>
                </div>

                {{-- Distance max (mode mot-clé/découvrir, "à proximité" a un rayon fixe 20km) --}}
                @if($mode !== 'nearby')
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label for="maxDistance" class="text-sm font-medium text-[#1E293B]">Distance max</label>
                            <span class="text-sm font-mono text-[#1E293B]">
                                {{ $maxDistance ? $maxDistance.' km' : 'Aucune' }}
                            </span>
                        </div>
                        <input type="range" id="maxDistance" wire:model.live="maxDistance" min="1" max="200" step="1"
                               class="accent-[#1E3D59] w-full">
                    </div>
                @endif

                {{-- Prix max (curseur, non pertinent pour les commerces) --}}
                @if($type !== 'commerce')
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label for="maxPrice" class="text-sm font-medium text-[#1E293B]">Prix max</label>
                            <span class="text-sm font-mono text-[#1E293B]">
                                {{ $maxPrice ? $maxPrice.' €' : 'Aucun' }}
                            </span>
                        </div>
                        <input type="range" id="maxPrice" wire:model.live="maxPrice" min="5" max="1000" step="5"
                               class="accent-[#1E3D59] w-full">
                    </div>
                @endif
            </div>
        </aside>

        {{-- Résultats --}}
        <div class="flex-1 min-w-0">

            @if($type === 'commerce')
                {{-- Commerces --}}
                @if($mode === 'nearby' && !$userLat)
                    <div class="rounded-2xl border border-dashed border-[#E2E8F0] p-10 text-center">
                        <p class="text-[#333333]">Active ta position pour voir les commerces autour de toi.</p>
                    </div>
                @elseif($companies->isEmpty())
                    <div class="rounded-2xl border border-dashed border-[#E2E8F0] p-10 text-center">
                        <p class="text-[#333333]">Aucun commerce ne correspond à cette recherche.</p>
                    </div>
                @else
                    <p class="text-sm text-[#333333]/60 mb-4">
                        {{ $companies->total() }} commerce{{ $companies->total() > 1 ? 's' : '' }}
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        @foreach($companies as $company)
                            <a href="{{ route('search', ['q' => $company->name]) }}" wire:navigate
                               class="group flex flex-col overflow-hidden rounded-xl border border-[#E2E8F0] bg-[#FAFAFF] shadow-sm transition hover:shadow-md">
                                <div class="relative aspect-[2/3] w-full bg-[#E2E8F0]">
                                    @if($company->cover_image_url)
                                        <img src="{{ $company->cover_image_url }}" alt="{{ $company->name }}"
                                             class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-sm text-[#333333]/40">
                                            Pas d'image
                                        </div>
                                    @endif
                                </div>

                                <div class="flex flex-1 flex-col p-4">
                                    <h3 class="font-semibold text-[#1E293B] line-clamp-2">{{ $company->name }}</h3>
                                    <p class="mt-1 text-xs text-[#333333]/60 line-clamp-2">{{ $company->address }}</p>

                                    @if(isset($company->distance))
                                        <p class="mt-auto pt-3 text-xs font-mono text-[#333333]/60">
                                            {{ number_format($company->distance, 1) }} km à vol d'oiseau
                                        </p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $companies->links() }}
                    </div>
                @endif

            @else
                {{-- Produits / Services --}}
                @if($mode === 'nearby' && !$userLat)
                    <div class="rounded-2xl border border-dashed border-[#E2E8F0] p-10 text-center">
                        <p class="text-[#333333]">Active ta position pour voir ce qu'il y a autour de toi.</p>
                    </div>
                @elseif($products->isEmpty())
                    <div class="rounded-2xl border border-dashed border-[#E2E8F0] p-10 text-center">
                        <p class="text-[#333333]">Aucun résultat pour cette recherche.</p>
                    </div>
                @else
                    <p class="text-sm text-[#333333]/60 mb-4">
                        {{ $products->total() }} résultat{{ $products->total() > 1 ? 's' : '' }}
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        @foreach($products as $product)
                            <a href="{{ route('products.show', $product) }}" wire:navigate
                               class="group flex flex-col overflow-hidden rounded-xl border border-[#E2E8F0] bg-[#FAFAFF] shadow-sm transition hover:shadow-md">
                                <div class="relative aspect-[2/3] w-full bg-[#E2E8F0]">
                                    @if($product->images->isNotEmpty())
                                        <img src="{{ $product->images->first()->url }}" alt="{{ $product->title }}"
                                             class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-sm text-[#333333]/40">
                                            Pas d'image
                                        </div>
                                    @endif
                                </div>

                                <div class="flex flex-1 flex-col p-4">
                                    <p class="font-medium text-[#1E293B] line-clamp-2">{{ $product->title }}</p>
                                    <p class="mt-1 text-xs text-[#333333]/60 line-clamp-1">{{ $product->address }}</p>
                                    @if(isset($product->distance))
                                        <p class="mt-2 text-xs font-mono text-[#333333]/60">
                                            {{ number_format($product->distance, 1) }} km à vol d'oiseau
                                        </p>
                                    @endif
                                    <div class="mt-3 flex items-center justify-between">
                                        <span class="font-mono text-sm font-semibold text-[#1E3D59]">
                                            {{ number_format($product->price, 2) }} €
                                        </span>
                                        <span class="text-xs text-[#333333]/50">{{ ucfirst($product->type) }}</span>
                                    </div>

                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>