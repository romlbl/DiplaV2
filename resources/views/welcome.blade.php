<x-layouts::public>
    <section class="text-center py-12 md:py-16">
        <h1 class="text-3xl md:text-5xl font-semibold tracking-tight mb-4 text-[#1E293B]">
            Trouvez vos commerces de proximité
        </h1>
        <p class="text-[#333333] text-base md:text-lg mb-8 max-w-xl mx-auto">
            Produits et services proposés par des entreprises près de chez vous
        </p>

        <form action="{{ route('search') }}" method="GET" class="max-w-lg mx-auto flex flex-col sm:flex-row gap-2 px-4">
            <input
                type="text"
                name="q"
                placeholder="Que recherchez-vous ?"
                class="flex-1 px-4 py-3 rounded-xl border border-[#E2E8F0] bg-white text-sm text-[#333333] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20 focus:border-[#1E3D59]"
            >
            <button type="submit" class="bg-[#1E3D59] text-[#FDFBF7] font-semibold text-sm px-6 py-3 rounded-full hover:bg-[#16293F] transition">
                Rechercher
            </button>
        </form>
    </section>

    <section class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-5 py-10 border-t border-[#E2E8F0]">
        <a href="{{ route('search', ['mode' => 'nearby']) }}" wire:navigate class="p-6 rounded-2xl hover:bg-[#FAFAFF] transition">
            <div class="text-[#1E3D59] mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h2 class="font-semibold mb-1 text-[#1E293B]">À proximité</h2>
            <p class="text-[#333333] text-sm">Découvrez les commerces dans un rayon de 20 km autour de vous</p>
        </a>
        <a href="{{ route('search', ['mode' => 'discover']) }}" wire:navigate class="p-6 rounded-2xl hover:bg-[#FAFAFF] transition sm:border-l border-[#E2E8F0]">
            <div class="text-[#1E3D59] mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <h2 class="font-semibold mb-1 text-[#1E293B]">Découvrir</h2>
            <p class="text-[#333333] text-sm">Explorez aléatoirement de nouveaux produits et services</p>
        </a>
    </section>

    <section class="py-10 border-t border-[#E2E8F0]">
        <h2 class="font-semibold mb-5 text-[#1E293B]">Produits récents</h2>

        @php
            $recentProducts = \App\Models\Product::with('images')->latest()->take(4)->get();
        @endphp

        @if($recentProducts->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach($recentProducts as $product)
                    <a href="{{ route('products.show', $product) }}" wire:navigate class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] overflow-hidden shadow-sm hover:shadow-md transition">
                        @if($product->images->isNotEmpty())
                            <img src="{{ $product->images->first()->url }}" alt="{{ $product->title }}" class="aspect-square w-full object-cover">
                        @else
                            <div class="aspect-square bg-[#E2E8F0] flex items-center justify-center text-[#333333]/40 text-sm">
                                Pas d'image
                            </div>
                        @endif
                        <div class="p-3">
                            <p class="text-sm font-medium text-[#1E293B] truncate">{{ $product->title }}</p>
                            <p class="text-xs font-mono text-[#333333]/70 mt-1">{{ number_format($product->price, 2) }} €</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-sm text-[#333333]/60">Aucun produit publié pour l'instant.</p>
        @endif
    </section>
</x-layouts::public>