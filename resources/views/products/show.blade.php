<x-layouts::public>
    <div class="max-w-6xl mx-auto" x-data="{ tab: 'description' }">

        {{-- ============================= --}}
        {{-- Hero : galerie + infos produit --}}
        {{-- ============================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 mb-12">

            {{-- Colonne gauche : image principale, taille réduite --}}
            <div class="lg:col-span-2">
                <div class="max-w-[260px] mx-auto lg:mx-0 aspect-[2/3] overflow-hidden rounded-2xl border border-[#E2E8F0] bg-[#E2E8F0]">
                    @if($product->images->isNotEmpty())
                        <img id="main-product-image"
                             src="{{ $product->images->first()->url }}"
                             alt="{{ $product->title }}"
                             class="h-full w-full object-cover">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-sm text-[#333333]/40">
                            Pas d'image
                        </div>
                    @endif
                </div>
            </div>

            {{-- Colonne droite : titre, prix, actions, commerce, miniatures --}}
            <div class="lg:col-span-3 flex flex-col">
                {{-- Note --}}
                <div class="flex items-center gap-1 mb-2">
                    @if($product->reviews->isNotEmpty())
                        <span class="text-amber-500">★</span>
                        <span class="text-sm font-medium text-[#1E293B]">{{ $product->averageRating() }}</span>
                        <button type="button" @click="tab = 'avis'"
                                class="text-sm text-[#333333]/60 hover:underline">
                            ({{ $product->reviews->count() }} avis)
                        </button>
                    @else
                        <span class="text-sm text-[#333333]/50">Aucun avis pour l'instant</span>
                    @endif
                </div>

                {{-- Titre --}}
                <h1 class="text-xl md:text-2xl font-bold text-[#1E293B] leading-tight mb-3">
                    {{ $product->title }}
                </h1>

                {{-- Prix, taille réduite --}}
                <div class="rounded-xl border border-[#E2E8F0] bg-[#FAFAFF] px-4 py-2.5 mb-4 w-fit">
                    <span class="text-xl md:text-2xl font-mono font-bold text-[#1E3D59]">
                        {{ number_format($product->price, 2) }} €
                    </span>
                </div>

                {{-- Actions : Contacter puis Favoris en dessous --}}
                <div class="flex flex-col gap-3 mb-5">
                    <button type="button"
                            @click="
                                tab = 'questions';
                                $nextTick(() => {
                                    document.getElementById('question-section')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                    document.getElementById('new-question-input')?.focus();
                                });
                            "
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-[#1E3D59] px-6 py-3 text-sm font-semibold text-[#FDFBF7] transition hover:bg-[#16293F]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zM21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                        </svg>
                        Contacter le commerce
                    </button>

                    <livewire:product.favorite-button :product="$product" />
                </div>

                {{-- Carte commerce --}}
                <a href="{{ route('search', ['q' => $product->company->name]) }}" wire:navigate
                   class="flex items-center justify-between gap-4 rounded-xl border border-[#E2E8F0] bg-[#FAFAFF] p-3 transition hover:border-[#1E3D59] group">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#E2E8F0] overflow-hidden">
                            @if($product->company->cover_image_url)
                                <img src="{{ $product->company->cover_image_url }}" alt="" class="h-full w-full object-cover">
                            @else
                                <span class="text-[#1E3D59] font-semibold text-sm">{{ mb_substr($product->company->name, 0, 1) }}</span>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-medium text-[#1E293B] text-sm truncate group-hover:text-[#1E3D59] transition">
                                {{ $product->company->name }}
                            </h3>
                            <p class="text-xs text-[#333333]/60 truncate">{{ $product->address }}</p>
                        </div>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-[#333333]/40 group-hover:text-[#1E3D59] transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>

                {{-- Miniatures : sous la fiche entreprise --}}
                @if($product->images->count() > 1)
                    <div class="grid grid-cols-6 sm:grid-cols-8 gap-2 mt-4 max-w-md">
                        @foreach($product->images as $image)
                            <button type="button"
                                    onclick="document.getElementById('main-product-image').src = '{{ $image->url }}'"
                                    class="aspect-[2/3] overflow-hidden rounded-lg border-2 border-[#E2E8F0] opacity-70 hover:opacity-100 hover:border-[#1E3D59] transition">
                                <img src="{{ $image->url }}" alt="" class="h-full w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- ============================= --}}
        {{-- Onglets : Description / Avis / Questions --}}
        {{-- ============================= --}}
        <section class="mb-12">
            <div class="flex gap-6 border-b border-[#E2E8F0] mb-6 overflow-x-auto">
                <button @click="tab = 'description'"
                        :class="tab === 'description' ? 'border-[#1E3D59] text-[#1E3D59]' : 'border-transparent text-[#333333]/60 hover:text-[#1E293B]'"
                        class="pb-3 border-b-2 text-sm font-semibold whitespace-nowrap transition">
                    Description
                </button>
                <button @click="tab = 'avis'"
                        :class="tab === 'avis' ? 'border-[#1E3D59] text-[#1E3D59]' : 'border-transparent text-[#333333]/60 hover:text-[#1E293B]'"
                        class="pb-3 border-b-2 text-sm font-semibold whitespace-nowrap transition">
                    Avis ({{ $product->reviews->count() }})
                </button>
                <button @click="tab = 'questions'"
                        :class="tab === 'questions' ? 'border-[#1E3D59] text-[#1E3D59]' : 'border-transparent text-[#333333]/60 hover:text-[#1E293B]'"
                        class="pb-3 border-b-2 text-sm font-semibold whitespace-nowrap transition">
                    Questions ({{ $product->discussions->count() }})
                </button>
            </div>

            {{-- Description --}}
            <div x-show="tab === 'description'" class="max-w-3xl">
                <p class="text-[#333333]/80 leading-relaxed whitespace-pre-line">{{ $product->description }}</p>

                @if($product->keywords)
                    <div class="flex flex-wrap gap-2 mt-5">
                        @foreach(explode(',', $product->keywords) as $keyword)
                            <span class="inline-flex items-center rounded-full border border-[#E2E8F0] px-3 py-1 text-xs text-[#333333]">
                                {{ trim($keyword) }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Avis --}}
            <div x-show="tab === 'avis'" x-cloak>
                <livewire:product.reviews-section :product="$product" />
            </div>

            {{-- Questions --}}
            <div id="question-section" x-show="tab === 'questions'" x-cloak>
                <livewire:product.discussions :product="$product" />
            </div>
        </section>

        {{-- ============================= --}}
        {{-- Itinéraire --}}
        {{-- ============================= --}}
        <section class="mb-12">
            <h2 class="text-lg font-semibold text-[#1E293B] mb-4">Venir chercher ce produit</h2>

            @if($product->latitude && $product->longitude)
                <div x-data="routePreview({{ $product->latitude }}, {{ $product->longitude }})"
                     class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-4 shadow-sm">

                    {{-- Modes de transport --}}
                    <div class="flex gap-2 mb-3 overflow-x-auto">
                        <button type="button" @click="setMode('walking')"
                                :class="mode === 'walking' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'border border-[#E2E8F0] text-[#333333] hover:bg-white'"
                                class="shrink-0 inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 5.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM8 21l1.5-6.5L7 13l2-5 3-1 3 2 2 4-2 1-1.5-2.5L12 15l2 6" />
                            </svg>
                            À pied
                            <span x-show="durations.walking" x-text="durations.walking" class="font-mono text-xs opacity-80"></span>
                        </button>

                        <button type="button" @click="setMode('cycling')"
                                :class="mode === 'cycling' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'border border-[#E2E8F0] text-[#333333] hover:bg-white'"
                                class="shrink-0 inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 7.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM5.25 19.5a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5zM18.75 19.5a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5zM5.25 17.25l4.5-8.25h4.5l4.5 8.25M9.75 9l3 4.5" />
                            </svg>
                            Vélo
                            <span x-show="durations.cycling" x-text="durations.cycling" class="font-mono text-xs opacity-80"></span>
                        </button>

                        <button type="button" @click="setMode('driving')"
                                :class="mode === 'driving' ? 'bg-[#1E3D59] text-[#FDFBF7]' : 'border border-[#E2E8F0] text-[#333333] hover:bg-white'"
                                class="shrink-0 inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125v-4.5c0-.621.504-1.125 1.125-1.125h1.5m0 0h13.5m-13.5 0v-2.25a1.5 1.5 0 013 0v2.25m10.5-2.25v2.25m0-2.25a1.5 1.5 0 013 0v2.25m-3-2.25h.375c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125H18.75m-9 0a1.5 1.5 0 013 0m-3 0a1.5 1.5 0 00-3 0m9 0a1.5 1.5 0 01-3 0" />
                            </svg>
                            Voiture
                            <span x-show="durations.driving" x-text="durations.driving" class="font-mono text-xs opacity-80"></span>
                        </button>
                    </div>

                    <div x-ref="map" class="h-64 w-full rounded-xl border border-[#E2E8F0] overflow-hidden"></div>

                    <p class="text-sm text-[#333333] mt-3">
                        <span x-show="loading">Calcul de l'itinéraire...</span>
                        <span x-show="!loading && error" x-text="error" class="text-[#4A3B5C]"></span>
                        <template x-if="!loading && !error && distanceKm !== null">
                            <span>
                                <span x-text="distanceKm.toFixed(1)"></span> km · environ
                                <span x-text="durations[mode]"></span>
                                <span x-text="mode === 'walking' ? 'à pied' : (mode === 'cycling' ? 'à vélo' : 'en voiture')"></span>
                            </span>
                        </template>
                    </p>
                </div>
            @else
                <p class="text-sm text-[#333333]/60">Position non renseignée pour ce commerce.</p>
            @endif
        </section>

        {{-- ============================= --}}
        {{-- Produits similaires --}}
        {{-- ============================= --}}
        @if($related->isNotEmpty())
            <section class="mb-12">
                <h2 class="text-lg font-semibold text-[#1E293B] mb-4">Vous aimerez aussi</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    @foreach($related as $relatedProduct)
                        <a href="{{ route('products.show', $relatedProduct) }}" wire:navigate
                           class="group flex flex-col overflow-hidden rounded-xl border border-[#E2E8F0] bg-[#FAFAFF] shadow-sm hover:shadow-md transition">
                            <div class="aspect-[2/3] w-full bg-[#E2E8F0] overflow-hidden">
                                @if($relatedProduct->images->isNotEmpty())
                                    <img src="{{ $relatedProduct->images->first()->url }}" alt=""
                                         class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-sm text-[#333333]/40">
                                        Pas d'image
                                    </div>
                                @endif
                            </div>
                            <div class="p-3">
                                <p class="text-sm font-medium text-[#1E293B] truncate">{{ $relatedProduct->title }}</p>
                                <p class="text-xs font-mono text-[#333333]/70 mt-1">{{ number_format($relatedProduct->price, 2) }} €</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

    </div>
</x-layouts::public>