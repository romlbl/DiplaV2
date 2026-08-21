<x-layouts::company>
    <div class="max-w-3xl mx-auto p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-[#1E293B]">Mes produits</h1>
            <a href="{{ route('company.products.create') }}"
               class="inline-flex items-center justify-center rounded-full bg-[#1E3D59] px-5 py-2 text-sm font-semibold text-[#FDFBF7] transition hover:bg-[#16293F]">
                + Ajouter
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-xl border border-[#E2E8F0] bg-[#4A3B5C]/10 px-4 py-3 text-sm text-[#4A3B5C] mb-4">
                {{ session('success') }}
            </div>
        @endif

        @forelse($products as $product)
            <div class="flex items-center justify-between rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-4 mb-3 shadow-sm">
                <div class="flex items-center gap-4">
                    @if($product->images->isNotEmpty())
                        <img src="{{ $product->images->first()->url }}" alt="" class="w-14 h-14 rounded-lg object-cover border border-[#E2E8F0]">
                    @else
                        <div class="w-14 h-14 rounded-lg bg-[#E2E8F0]"></div>
                    @endif
                    <div>
                        <p class="font-medium text-[#1E293B]">{{ $product->title }}</p>
                        <p class="text-sm text-[#333333]/70">
                            <span class="font-mono">{{ number_format($product->price, 2) }} €</span>
                            · {{ ucfirst($product->type) }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('company.products.edit', $product) }}"
                       class="inline-flex items-center justify-center rounded-full border border-[#E2E8F0] px-4 py-1.5 text-sm font-medium text-[#1E293B] transition hover:bg-[#FDFBF7]">
                        Modifier
                    </a>
                    <form method="POST" action="{{ route('company.products.destroy', $product) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center justify-center rounded-full px-4 py-1.5 text-sm font-medium text-red-600 transition hover:bg-red-50">
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-[#E2E8F0] p-10 text-center">
                <p class="text-[#333333]">Aucun produit publié pour l'instant.</p>
                <a href="{{ route('company.products.create') }}" class="text-sm text-[#1E3D59] font-medium hover:underline mt-2 inline-block">
                    Publier ton premier produit
                </a>
            </div>
        @endforelse

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</x-layouts::company>