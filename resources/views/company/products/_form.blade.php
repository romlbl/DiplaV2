@csrf

@if($errors->any())
    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 mb-4">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="flex flex-col gap-4">
    <div>
        <label for="title" class="block text-sm font-medium text-[#1E293B] mb-1">Nom du produit / service</label>
        <input type="text" name="title" id="title" required
               value="{{ old('title', $product->title ?? '') }}"
               class="w-full rounded-xl border border-[#E2E8F0] bg-[#FDFBF7] px-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20">
    </div>

    <div class="flex gap-3">
        <label class="flex-1">
            <input type="radio" name="type" value="produit" class="peer sr-only"
                   {{ old('type', $product->type ?? 'produit') === 'produit' ? 'checked' : '' }}>
            <span class="flex items-center justify-center rounded-full border border-[#E2E8F0] px-4 py-2 text-sm font-medium text-[#1E293B] cursor-pointer transition peer-checked:bg-[#1E3D59] peer-checked:text-[#FDFBF7] peer-checked:border-[#1E3D59]">
                Produit
            </span>
        </label>
        <label class="flex-1">
            <input type="radio" name="type" value="service" class="peer sr-only"
                   {{ old('type', $product->type ?? '') === 'service' ? 'checked' : '' }}>
            <span class="flex items-center justify-center rounded-full border border-[#E2E8F0] px-4 py-2 text-sm font-medium text-[#1E293B] cursor-pointer transition peer-checked:bg-[#1E3D59] peer-checked:text-[#FDFBF7] peer-checked:border-[#1E3D59]">
                Service
            </span>
        </label>
    </div>

    <div>
        <label for="price" class="block text-sm font-medium text-[#1E293B] mb-1">Prix (€)</label>
        <input type="number" name="price" id="price" step="0.01" min="0" required
               value="{{ old('price', $product->price ?? '') }}"
               class="w-full rounded-xl border border-[#E2E8F0] bg-[#FDFBF7] px-4 py-2.5 text-sm font-mono text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20">
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-[#1E293B] mb-1">Description</label>
        <textarea name="description" id="description" rows="5" required
                  class="w-full rounded-xl border border-[#E2E8F0] bg-[#FDFBF7] px-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    <div>
        <label for="keywords" class="block text-sm font-medium text-[#1E293B] mb-1">Mots-clés</label>
        <input type="text" name="keywords" id="keywords" placeholder="ex : artisanal, local, fait main"
               value="{{ old('keywords', $product->keywords ?? '') }}"
               class="w-full rounded-xl border border-[#E2E8F0] bg-[#FDFBF7] px-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20">
        <p class="text-xs text-[#333333]/60 mt-1">Séparés par des virgules, ça aide les clients à te trouver.</p>
    </div>

    <div>
        <label for="address" class="block text-sm font-medium text-[#1E293B] mb-1">Adresse</label>
        <input type="text" name="address" id="address" required
               value="{{ old('address', $product->address ?? '') }}"
               class="w-full rounded-xl border border-[#E2E8F0] bg-[#FDFBF7] px-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20">
    </div>

    <div>
        <label for="images" class="block text-sm font-medium text-[#1E293B] mb-1">Photos (jusqu'à 4)</label>
        <input type="file" name="images[]" id="images" multiple accept="image/*"
               class="w-full rounded-xl border border-[#E2E8F0] bg-[#FDFBF7] px-4 py-2.5 text-sm text-[#333333] file:mr-4 file:rounded-full file:border-0 file:bg-[#1E3D59] file:px-4 file:py-1.5 file:text-sm file:font-medium file:text-[#FDFBF7]">

        @if(isset($product) && $product->images->isNotEmpty())
            <div class="flex gap-2 mt-3">
                @foreach($product->images as $image)
                    <img src="{{ $image->url }}" alt="" class="w-16 h-16 rounded-lg object-cover border border-[#E2E8F0]">
                @endforeach
            </div>
            <p class="text-xs text-[#333333]/60 mt-1">Photos actuelles. Ajouter de nouvelles photos les complète (gestion fine à venir).</p>
        @endif
    </div>
</div>