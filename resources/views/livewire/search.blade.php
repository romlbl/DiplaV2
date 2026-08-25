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

    {{-- Champ recherche (mode mot-clé uniquement) --}}
    @if($mode === 'keyword')
        <div class="mb-4">
            <input type="text" wire:model.live.debounce.400ms="q"
                   placeholder="Que cherches-tu ? (ex : boulangerie, réparation vélo...)"
                   class="w-full rounded-xl border border-[#E2E8F0] bg-[#FDFBF7] px-4 py-3 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20">
        </div>
    @endif

    {{-- Filtres --}}
    <div class="flex flex-wrap items-center gap-3 mb-8">
        {{-- Toggle produit/service --}}
        <div class="flex rounded-full border border-[#E2E8F0] p-1">
            <button wire:click="$set('type', '')"
                    class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $type === '' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'text-[#1E293B]' }}">
                Tout
            </button>
            <button wire:click="$set('type', 'produit')"
                    class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $type === 'produit' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'text-[#1E293B]' }}">
                Produits
            </button>
            <button wire:click="$set('type', 'service')"
                    class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ $type === 'service' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'text-[#1E293B]' }}">
                Services
            </button>
        </div>

        {{-- Prix max --}}
        <div class="flex items-center gap-2">
            <label for="maxPrice" class="text-sm text-[#333333]">Prix max</label>
            <input type="range" id="maxPrice" wire:model.live="maxPrice" min="5" max="1000" step="5"
                   class="accent-[#1E3D59] w-32">
            <span class="text-sm font-mono text-[#1E293B] w-16">
                {{ $maxPrice ? $maxPrice.' €' : 'Aucun' }}
            </span>
        </div>

        {{-- Distance max (mode mot-clé/découvrir uniquement, "à proximité" a un rayon fixe 20km) --}}
        @if($mode !== 'nearby')
            <div class="flex items-center gap-2">
                <label for="maxDistance" class="text-sm text-[#333333]">Distance max</label>
                <input type="range" id="maxDistance" wire:model.live="maxDistance" min="1" max="200" step="1"
                       class="accent-[#1E3D59] w-32">
                <span class="text-sm font-mono text-[#1E293B] w-20">
                    {{ $maxDistance ? $maxDistance.' km' : 'Aucune' }}
                </span>
            </div>
        @endif
    </div>

    {{-- Résultats --}}
    @if($mode === 'nearby' && !$userLat)
        <div class="rounded-2xl border border-dashed border-[#E2E8F0] p-10 text-center">
            <p class="text-[#333333]">Active ta position pour voir ce qu'il y a autour de toi.</p>
        </div>
    @elseif($products->isEmpty())
        <div class="rounded-2xl border border-dashed border-[#E2E8F0] p-10 text-center">
            <p class="text-[#333333]">Aucun résultat pour cette recherche.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($products as $product)
                <a href="{{ route('products.show', $product) }}" wire:navigate class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] overflow-hidden shadow-sm hover:shadow-md transition">
                    @if($product->images->isNotEmpty())
                        <img src="{{ $product->images->first()->url }}" alt="" class="w-full h-40 object-cover">
                    @else
                        <div class="w-full h-40 bg-[#E2E8F0]"></div>
                    @endif
                    <div class="p-4">
                        <p class="font-medium text-[#1E293B]">{{ $product->title }}</p>
                        <p class="text-sm text-[#333333]/70 mt-1">
                            <span class="font-mono">{{ number_format($product->price, 2) }} €</span>
                            · {{ ucfirst($product->type) }}
                            @if(isset($product->distance))
                                · {{ number_format($product->distance, 1) }} km
                            @endif
                        </p>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @endif
</div>