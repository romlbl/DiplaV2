<div>
    @if(session('storefront-updated'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 mb-4">
            Devanture mise à jour.
        </div>
    @endif

    <form wire:submit="save" class="flex flex-col gap-5">

        {{-- Nom --}}
        <div>
            <label for="storefront-name" class="block text-sm font-medium text-[#1E293B] mb-1">Nom de l'entreprise</label>
            <input type="text" id="storefront-name" wire:model="name"
                   class="w-full rounded-xl border border-[#E2E8F0] bg-[#FDFBF7] px-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20">
            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Photos de la devanture --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="sm:col-span-2">
                @include('partials.storefront-image-picker', [
                    'label' => 'Photo de couverture',
                    'help' => "Affichée en bannière sur la page devanture.",
                    'previewUrl' => $company->cover_image_url,
                    'wireModel' => 'newCoverImage',
                    'aspectClass' => 'aspect-[16/7]',
                    'round' => false,
                    'cropAspectRatio' => '16 / 7',
                ])
            </div>

            @include('partials.storefront-image-picker', [
                'label' => 'Photo carte (résultats)',
                'help' => "Utilisée quand votre commerce apparaît dans les résultats de recherche.",
                'previewUrl' => $company->card_image_url,
                'wireModel' => 'newCardImage',
                'aspectClass' => 'aspect-[2/3]',
                'round' => false,
                'cropAspectRatio' => '2 / 3',
            ])

            @include('partials.storefront-image-picker', [
                'label' => 'Photo ronde (fiche produit)',
                'help' => "Utilisée comme icône de votre commerce sur les fiches produit.",
                'previewUrl' => $company->avatar_image_url,
                'wireModel' => 'newAvatarImage',
                'aspectClass' => 'aspect-square max-w-[9rem]',
                'round' => true,
                'cropAspectRatio' => '1',
            ])
        </div>


        {{-- Adresse avec carte --}}
        <div wire:ignore>
            <div data-location-picker class="relative">
                <label for="storefront-address" class="block text-sm font-medium text-[#1E293B] mb-1">Adresse</label>

                <input type="text" id="storefront-address" autocomplete="off"
                    data-role="address-input"
                    wire:model="address"
                    class="w-full rounded-xl border border-[#E2E8F0] bg-[#FDFBF7] px-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20">

                <div data-role="suggestions" style="z-index: 9999;"
                    class="hidden absolute mt-1 w-full rounded-xl border border-[#E2E8F0] bg-[#FAFAFF] shadow-lg max-h-60 overflow-y-auto"></div>

                <div data-role="map" class="mt-3 h-48 w-full rounded-xl border border-[#E2E8F0] overflow-hidden"></div>

                <input type="hidden" data-role="latitude" wire:model="latitude">
                <input type="hidden" data-role="longitude" wire:model="longitude">
            </div>
        </div>
        @error('address') <p class="text-sm text-red-600 -mt-3">{{ $message }}</p> @enderror

        {{-- Description --}}
        <div>
            <label for="storefront-description" class="block text-sm font-medium text-[#1E293B] mb-1">Description</label>
            <textarea id="storefront-description" wire:model="description" rows="4"
                      class="w-full rounded-xl border border-[#E2E8F0] bg-[#FDFBF7] px-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20"></textarea>
            @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Horaires d'ouverture --}}
        <div>
            <label class="block text-sm font-medium text-[#1E293B] mb-2">Horaires d'ouverture</label>
            <div class="flex flex-col gap-2">
                @foreach($this->days as $key => $label)
                    <div class="flex flex-wrap items-center gap-3 rounded-xl border border-[#E2E8F0] bg-[#FDFBF7] px-3 py-2">
                        <span class="w-20 shrink-0 text-sm font-medium text-[#1E293B]">{{ $label }}</span>

                        <label class="flex items-center gap-1.5 text-xs text-[#333333]/70">
                            <input type="checkbox" wire:model="openingHours.{{ $key }}.closed" class="rounded border-[#E2E8F0]">
                            Fermé
                        </label>

                        @if(!($openingHours[$key]['closed'] ?? false))
                            <input type="time" wire:model="openingHours.{{ $key }}.open"
                                   class="rounded-lg border border-[#E2E8F0] bg-white px-2 py-1 text-sm text-[#333333]">
                            <span class="text-sm text-[#333333]/50">à</span>
                            <input type="time" wire:model="openingHours.{{ $key }}.close"
                                   class="rounded-lg border border-[#E2E8F0] bg-white px-2 py-1 text-sm text-[#333333]">
                        @else
                            <span class="text-sm text-[#333333]/40 italic">Fermé toute la journée</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="button" @click="showDevantureModal = false"
                    class="flex-1 rounded-full border border-[#E2E8F0] px-4 py-2.5 text-sm font-medium text-[#1E293B] transition hover:bg-[#FDFBF7]">
                Annuler
            </button>
            <button type="submit"
                    class="flex-1 rounded-full bg-[#1E3D59] px-4 py-2.5 text-sm font-semibold text-[#FDFBF7] transition hover:bg-[#16293F]">
                Enregistrer
            </button>
        </div>
    </form>
</div>