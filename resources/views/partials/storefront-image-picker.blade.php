{{--
    resources/views/partials/storefront-image-picker.blade.php

    Widget de sélection + recadrage d'une photo de devanture.
    Variables attendues :
    - $label            : titre affiché au-dessus
    - $help             : texte d'aide sous le bouton
    - $previewUrl       : URL actuelle de l'image (ou null)
    - $wireModel        : nom de la propriété Livewire ciblée (ex: "newCoverImage")
    - $aspectClass      : classes Tailwind pour le cadre de prévisualisation
    - $round            : bool, true pour une prévisualisation en cercle
    - $cropAspectRatio  : ratio JS passé à Cropper.js (ex: "16 / 7", "2 / 3", "1")
--}}
<div x-data="singleImageCropper({
        aspectRatio: {{ $cropAspectRatio }},
        wireModel: '{{ $wireModel }}',
        initialUrl: @js($previewUrl),
    })" class="flex flex-col items-center ">
    <label class="block text-sm font-medium text-[#1E293B] mb-2">{{ $label }}</label>

    <div class="relative {{ $aspectClass }} w-full overflow-hidden border border-[#E2E8F0] bg-[#E2E8F0] {{ $round ? 'rounded-full mx-auto' : 'rounded-xl' }}">
        <img :src="previewUrl" alt="" x-show="previewUrl" x-cloak class="h-full w-full object-cover">

        <div x-show="!previewUrl" class="flex h-full w-full items-center justify-center text-xs text-center px-2 text-[#333333]/40">
            Aucune photo
        </div>

        <div x-show="uploading" x-cloak class="absolute inset-0 flex items-center justify-center bg-black/30 text-xs font-medium text-white">
            Envoi...
        </div>
    </div>

    <label class="mt-2 inline-flex cursor-pointer items-center gap-2 rounded-full border border-[#E2E8F0] px-4 py-2 text-sm font-medium text-[#1E293B] transition hover:bg-[#FDFBF7]">
        <span x-text="previewUrl ? 'Changer la photo' : 'Ajouter une photo'"></span>
        <input type="file" x-ref="fileInput" accept="image/*" class="hidden" @change="handleFile($event.target.files)">
    </label>

    @if(!empty($help))
        <p class="text-xs text-[#333333]/60 mt-1">{{ $help }}</p>
    @endif

    @error($wireModel) <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror

    {{-- Modale de recadrage --}}
    <div x-show="cropModalOpen" x-cloak
         class="fixed inset-0 z-[110] flex items-center justify-center bg-black/40 px-4"
         style="display: none;">
        <div class="bg-[#FDFBF7] rounded-2xl p-6 max-w-md w-full shadow-lg">
            <h3 class="text-base font-semibold text-[#1E293B] mb-3">Recadrer la photo</h3>

            <div class="max-h-80 overflow-hidden rounded-xl">
                <img x-ref="cropImage" class="max-w-full block">
            </div>

            <div class="flex gap-3 mt-5">
                <button type="button" @click="cancelCropping()"
                        class="rounded-full border border-[#E2E8F0] px-4 py-2 text-sm font-medium text-[#1E293B] hover:bg-white transition">
                    Annuler
                </button>
                <button type="button" @click="confirmCrop()"
                        class="flex-1 rounded-full bg-[#1E3D59] px-4 py-2 text-sm font-semibold text-[#FDFBF7] hover:bg-[#16293F] transition">
                    Valider ce recadrage
                </button>
            </div>
        </div>
    </div>
</div>