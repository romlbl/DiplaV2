<x-layouts::public>

    <section class="text-center py-12 md:py-16">
        <h1 class="font-display text-3xl md:text-5xl font-semibold tracking-tight mb-4 text-ink-950">
            Trouvez vos commerces de proximité
        </h1>
        <p class="text-ink-700 text-base md:text-lg mb-8 max-w-xl mx-auto">
            Produits et services proposés par des entreprises près de chez vous
        </p>

        <div class="max-w-lg mx-auto flex flex-col sm:flex-row gap-2 px-4">
            <input
                type="text"
                placeholder="Que recherchez-vous ?"
                class="flex-1 px-4 py-3 rounded-md border border-line bg-white text-sm focus:outline-none focus:ring-1 focus:ring-accent-700 focus:border-accent-700"
                disabled
            >
            <button class="bg-accent-700 text-white font-medium text-sm px-6 py-3 rounded-md hover:bg-accent-600 transition disabled:opacity-50" disabled>
                Rechercher
            </button>
        </div>
        <p class="text-xs text-ink-400 mt-2">Recherche disponible en Phase 4</p>
    </section>

    <section class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-5 py-10 border-t border-line">
        <div class="p-6">
            <div class="text-accent-700 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h2 class="font-semibold mb-1 text-ink-950">À proximité</h2>
            <p class="text-ink-700 text-sm">Découvrez les commerces dans un rayon de 20 km autour de vous</p>
        </div>

        <div class="p-6 sm:border-l border-line">
            <div class="text-accent-700 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <h2 class="font-semibold mb-1 text-ink-950">Découvrir</h2>
            <p class="text-ink-700 text-sm">Explorez aléatoirement de nouveaux produits et services</p>
        </div>
    </section>

    <section class="py-10 border-t border-line">
        <h2 class="font-semibold mb-5 text-ink-950">Produits récents</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
            @for ($i = 0; $i < 4; $i++)
                <div class="border border-line rounded-md overflow-hidden">
                    <div class="aspect-square bg-canvas-alt flex items-center justify-center text-ink-400 text-sm">
                        Image
                    </div>
                    <div class="p-3">
                        <div class="h-3 bg-canvas-alt rounded w-3/4 mb-2"></div>
                        <div class="h-3 bg-canvas-alt rounded w-1/3 font-mono"></div>
                    </div>
                </div>
            @endfor
        </div>
        <p class="text-xs text-ink-400 mt-3">Contenu réel branché en Phase 4 (modèle Product)</p>
    </section>

</x-layouts::public>