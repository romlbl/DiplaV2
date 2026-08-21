<x-layouts::public>
    <div class="max-w-4xl mx-auto">

        {{-- Galerie --}}
        @if($product->images->isNotEmpty())
            <div class="grid grid-cols-4 gap-2 rounded-2xl overflow-hidden mb-6" style="grid-template-rows: 1fr 1fr;">
                <img src="{{ $product->images->first()->url }}" alt="{{ $product->title }}"
                     class="col-span-4 sm:col-span-2 row-span-2 h-64 sm:h-80 w-full object-cover">
                @foreach($product->images->skip(1)->take(3) as $image)
                    <img src="{{ $image->url }}" alt="" class="hidden sm:block h-[9.5rem] w-full object-cover">
                @endforeach
            </div>
        @else
            <div class="h-64 sm:h-80 w-full rounded-2xl bg-[#E2E8F0] mb-6"></div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Colonne principale --}}
            <div class="lg:col-span-2">
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center rounded-full bg-[#4A3B5C]/10 px-3 py-1 text-xs font-medium text-[#4A3B5C]">
                        {{ ucfirst($product->type) }}
                    </span>
                    @if($product->reviews->isNotEmpty())
                        <span class="text-sm text-[#333333]">
                            ★ {{ $product->averageRating() }} · {{ $product->reviews->count() }} avis
                        </span>
                    @else
                        <span class="text-sm text-[#333333]/60">Aucun avis pour l'instant</span>
                    @endif
                </div>

                <div class="flex items-center justify-between gap-4">
                    <h1 class="text-2xl font-semibold text-[#1E293B]">{{ $product->title }}</h1>
                    <livewire:product.favorite-button :product="$product" />
                </div>
                <p class="text-sm text-[#333333]/70 mt-1">{{ $product->company->name }} · {{ $product->address }}</p>

                <p class="text-2xl font-mono font-semibold text-[#1E3D59] mt-4">
                    {{ number_format($product->price, 2) }} €
                </p>

                <div class="mt-6">
                    <h2 class="text-sm font-semibold text-[#1E293B] mb-2">Description</h2>
                    <p class="text-sm text-[#333333] whitespace-pre-line">{{ $product->description }}</p>
                </div>

                @if($product->keywords)
                    <div class="flex flex-wrap gap-2 mt-4">
                        @foreach(explode(',', $product->keywords) as $keyword)
                            <span class="inline-flex items-center rounded-full border border-[#E2E8F0] px-3 py-1 text-xs text-[#333333]">
                                {{ trim($keyword) }}
                            </span>
                        @endforeach
                    </div>
                @endif

                {{-- Avis : système complet en Phase 5, on affiche juste ce qui existe --}}
                <livewire:product.reviews-section :product="$product" />
                <livewire:product.discussions :product="$product" />
            </div>

            {{-- Colonne itinéraire --}}
            <div class="lg:col-span-1">
                <div class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-4 shadow-sm sticky top-4"
                     data-route-preview
                     data-dest-lat="{{ $product->latitude }}"
                     data-dest-lng="{{ $product->longitude }}">
                    <h2 class="text-sm font-semibold text-[#1E293B] mb-3">Itinéraire</h2>

                    @if($product->latitude && $product->longitude)
                        <div data-role="route-map" class="h-48 w-full rounded-xl border border-[#E2E8F0] overflow-hidden"></div>
                        <p data-role="route-info" class="text-sm text-[#333333] mt-3">
                            Calcul de l'itinéraire...
                        </p>
                    @else
                        <p class="text-sm text-[#333333]/60">Position non renseignée pour ce commerce.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Produits liés --}}
        @if($related->isNotEmpty())
            <div class="mt-12">
                <h2 class="text-lg font-semibold text-[#1E293B] mb-4">Produits similaires</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($related as $relatedProduct)
                        <a href="{{ route('products.show', $relatedProduct) }}" wire:navigate
                           class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] overflow-hidden shadow-sm hover:shadow-md transition">
                            @if($relatedProduct->images->isNotEmpty())
                                <img src="{{ $relatedProduct->images->first()->url }}" alt="" class="w-full h-32 object-cover">
                            @else
                                <div class="w-full h-32 bg-[#E2E8F0]"></div>
                            @endif
                            <div class="p-3">
                                <p class="text-sm font-medium text-[#1E293B] truncate">{{ $relatedProduct->title }}</p>
                                <p class="text-xs font-mono text-[#333333]/70 mt-1">{{ number_format($relatedProduct->price, 2) }} €</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</x-layouts::public>