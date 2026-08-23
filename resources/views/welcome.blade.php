<x-layouts::public>
    @php
        $companiesCount = \App\Models\Company::count();
        $productsCount = \App\Models\Product::count();
        $newThisWeek = \App\Models\Product::where('created_at', '>=', now()->subWeek())->count();
    @endphp

    <section class="rounded-2xl bg-[#1E3D59] px-6 py-10 md:py-14 text-center">
        <p class="font-mono text-xs tracking-widest uppercase text-[#FDFBF7]/60 mb-3">
            Autour de vous
        </p>
        <h1 class="text-3xl md:text-5xl font-semibold tracking-tight mb-4 text-[#FDFBF7]">
            Trouvez vos commerces de proximité
        </h1>
        <p class="text-[#FDFBF7]/80 text-base md:text-lg mb-8 max-w-xl mx-auto">
            Produits et services proposés par des entreprises près de chez vous
        </p>

        <form action="{{ route('search') }}" method="GET" class="max-w-lg mx-auto flex flex-col sm:flex-row gap-2 px-4">
            <input
                type="text"
                name="q"
                placeholder="Que recherchez-vous ?"
                class="flex-1 px-4 py-3 rounded-full border-0 bg-[#FDFBF7] text-sm text-[#333333] focus:outline-none focus:ring-2 focus:ring-[#FDFBF7]/40"
            >
            <input
                type="text"
                name="lieu"
                placeholder="Lieu"
                class="sm:w-32 px-4 py-3 rounded-full border-0 bg-[#FDFBF7] text-sm text-[#333333] focus:outline-none focus:ring-2 focus:ring-[#FDFBF7]/40"
            >
            <button type="submit" class="bg-[#FDFBF7] text-[#1E3D59] font-semibold text-sm px-6 py-3 rounded-full hover:bg-white transition">
                Rechercher
            </button>
        </form>

        <p x-data="{ n: 0, target: {{ $productsCount }} }"
        x-init="let i = setInterval(() => { n += Math.ceil(target/30); if (n >= target) { n = target; clearInterval(i); } }, 30)"
        class="font-mono text-xs text-[#FDFBF7]/60 mt-6">
            {{ $companiesCount }} commerces · <span x-text="n"></span> produits · {{ $newThisWeek }} nouveautés
        </p>
    </section>

    <section class="flex flex-wrap gap-2 py-6">
        <a href="{{ route('search') }}" wire:navigate class="px-4 py-2 rounded-full bg-[#1E3D59] text-[#FDFBF7] text-sm font-medium hover:bg-[#16293F] transition">
            Tout
        </a>
        <a href="{{ route('search', ['type' => 'produit']) }}" wire:navigate class="px-4 py-2 rounded-full border border-[#E2E8F0] bg-[#FAFAFF] text-[#333333] text-sm hover:border-[#1E3D59] transition">
            Produits
        </a>
        <a href="{{ route('search', ['type' => 'service']) }}" wire:navigate class="px-4 py-2 rounded-full border border-[#E2E8F0] bg-[#FAFAFF] text-[#333333] text-sm hover:border-[#1E3D59] transition">
            Services
        </a>
        <a href="{{ route('search', ['mode' => 'nearby']) }}" wire:navigate class="px-4 py-2 rounded-full border border-[#E2E8F0] bg-[#FAFAFF] text-[#333333] text-sm hover:border-[#1E3D59] transition inline-flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            À proximité
        </a>
        <a href="{{ route('search', ['mode' => 'discover']) }}" wire:navigate class="px-4 py-2 rounded-full border border-[#E2E8F0] bg-[#FAFAFF] text-[#4A3B5C] text-sm hover:border-[#4A3B5C] transition inline-flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            Découvrir
        </a>
    </section>

    <section class="py-8 border-t border-[#E2E8F0]">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-semibold text-[#1E293B]">Nouveautés</h2>
            <a href="{{ route('search') }}" wire:navigate class="text-xs font-mono text-[#4A3B5C] hover:underline">
                Tout voir
            </a>
        </div>

        @php
            $recentProducts = \App\Models\Product::with('images')->latest()->take(4)->get();
        @endphp

        @if($recentProducts->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach($recentProducts as $i => $product)
                    <a href="{{ route('products.show', $product) }}" wire:navigate
                        x-data="{ show: false }"
                        x-init="setTimeout(() => show = true, {{ $i * 80 }})"
                        :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-2'"
                        class="group rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] overflow-hidden transition-all duration-500 hover:-translate-y-0.5 hover:shadow-md">
                        @if($product->images->isNotEmpty())
                            <img src="{{ $product->images->first()->url }}" alt="{{ $product->title }}" class="aspect-square w-full object-cover">
                        @else
                            <div class="aspect-square bg-[#E2E8F0] flex items-center justify-center text-[#333333]/40 text-sm">
                                Pas d'image
                            </div>
                        @endif
                        <div class="p-3">
                            <p class="text-sm font-medium text-[#1E293B] truncate">{{ $product->title }}</p>
                            <p class="text-xs font-mono text-[#4A3B5C] mt-1">{{ number_format($product->price, 2) }} €</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-sm text-[#333333]/60">Aucun produit publié pour l'instant.</p>
        @endif
    </section>

    <section class="py-8 border-t border-[#E2E8F0]">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-semibold text-[#1E293B]">Découvrir</h2>
            <a href="{{ route('search', ['mode' => 'discover']) }}" wire:navigate class="text-xs font-mono text-[#4A3B5C] hover:underline">
                Relancer
            </a>
        </div>

        @php
            $discoverProducts = \App\Models\Product::with('images')->inRandomOrder()->take(4)->get();
        @endphp

        @if($discoverProducts->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach($recentProducts as $i => $product)
                        <a href="{{ route('products.show', $product) }}" wire:navigate
                        x-data="{ show: false }"
                        x-init="setTimeout(() => show = true, {{ $i * 80 }})"
                        :class="show ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-2'"
                        class="group rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] overflow-hidden transition-all duration-500 hover:-translate-y-0.5 hover:shadow-md">
                        @if($product->images->isNotEmpty())
                            <img src="{{ $product->images->first()->url }}" alt="{{ $product->title }}" class="aspect-square w-full object-cover">
                        @else
                            <div class="aspect-square bg-[#E2E8F0] flex items-center justify-center text-[#333333]/40 text-sm">
                                Pas d'image
                            </div>
                        @endif
                        <div class="p-3">
                            <p class="text-sm font-medium text-[#1E293B] truncate">{{ $product->title }}</p>
                            <p class="text-xs font-mono text-[#4A3B5C] mt-1">{{ number_format($product->price, 2) }} €</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-sm text-[#333333]/60">Aucun produit publié pour l'instant.</p>
        @endif
    </section>
</x-layouts::public>