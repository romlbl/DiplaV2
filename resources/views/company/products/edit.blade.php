<x-layouts::company>
    <div class="max-w-2xl mx-auto p-6">
        <div class="mb-6">
            <a href="{{ route('company.products.index') }}" class="text-sm text-[#4A3B5C] hover:underline">&larr; Retour à mes produits</a>
            <h1 class="text-2xl font-semibold text-[#1E293B] mt-2">Modifier le produit</h1>
        </div>
        <div class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-6 shadow-sm mb-4">
            <livewire:company.product-image-manager :product="$product" />
        </div>

        <form method="POST" action="{{ route('company.products.update', $product) }}" enctype="multipart/form-data" class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-6 shadow-sm">
            @method('PUT')
            @include('company.products._form')

            <button type="submit" class="mt-6 w-full inline-flex items-center justify-center rounded-full bg-[#1E3D59] px-6 py-2.5 text-sm font-semibold text-[#FDFBF7] transition hover:bg-[#16293F]">
                Enregistrer les modifications
            </button>
        </form>
    </div>
</x-layouts::company>