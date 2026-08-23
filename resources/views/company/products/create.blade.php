<x-layouts::company>
    <div class="max-w-2xl mx-auto p-6">
        <div class="mb-6">
            <a href="{{ route('company.products.index') }}" class="text-sm text-[#4A3B5C] hover:underline">&larr; Retour à mes produits</a>
            <h1 class="text-2xl font-semibold text-[#1E293B] mt-2">Nouveau produit</h1>
        </div>

        <div>
            <label for="images" class="block text-sm font-medium text-[#1E293B] mb-1">Photos (jusqu'à 4)</label>
            <input type="file" name="images[]" id="images" multiple accept="image/*"
                class="w-full rounded-xl border border-[#E2E8F0] bg-[#FDFBF7] px-4 py-2.5 text-sm text-[#333333] file:mr-4 file:rounded-full file:border-0 file:bg-[#1E3D59] file:px-4 file:py-1.5 file:text-sm file:font-medium file:text-[#FDFBF7]">
            <p class="text-xs text-[#333333]/60 mt-1">Tu pourras recadrer, réorganiser et gérer tes photos plus finement après la création.</p>
        </div>

        <form method="POST" action="{{ route('company.products.store') }}" enctype="multipart/form-data" class="rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-6 shadow-sm">
            @include('company.products._form')

            <button type="submit" class="mt-6 w-full inline-flex items-center justify-center rounded-full bg-[#1E3D59] px-6 py-2.5 text-sm font-semibold text-[#FDFBF7] transition hover:bg-[#16293F]">
                Publier le produit
            </button>
        </form>
    </div>
</x-layouts::company>