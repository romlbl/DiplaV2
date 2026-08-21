<x-layouts::company>
    <h1 class="text-2xl font-semibold text-[#1E293B]">Tableau de bord</h1>
    <p class="text-sm text-[#333333] mt-1">
        Bienvenue, <span class="font-medium text-[#1E3D59]">{{ auth('company')->user()->name }}</span>
    </p>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-8">
        <div class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-5 shadow-sm">
            <p class="text-sm text-[#333333]/70">Produits publiés</p>
            <p class="text-3xl font-mono font-semibold text-[#1E293B] mt-1">{{ $stats['products_count'] }}</p>
        </div>
        <div class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-5 shadow-sm">
            <p class="text-sm text-[#333333]/70">Vues cumulées</p>
            <p class="text-3xl font-mono font-semibold text-[#1E293B] mt-1">{{ $stats['total_views'] }}</p>
        </div>
        <div class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-5 shadow-sm">
            <p class="text-sm text-[#333333]/70">Ajouts en favoris</p>
            <p class="text-3xl font-mono font-semibold text-[#1E293B] mt-1">{{ $stats['total_favorites'] }}</p>
        </div>
    </div>

    <div class="mt-8">
        <a href="{{ route('company.products.index') }}"
           class="inline-flex items-center justify-center rounded-full bg-[#1E3D59] px-5 py-2 text-sm font-semibold text-[#FDFBF7] transition hover:bg-[#16293F]">
            Voir mes produits
        </a>
    </div>
</x-layouts::company>