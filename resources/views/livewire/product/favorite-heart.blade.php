<div>
    @auth
        <button type="button"
                wire:click.stop.prevent="toggle"
                aria-label="{{ $isFavorited ? 'Retirer des favoris' : 'Ajouter aux favoris' }}"
                aria-pressed="{{ $isFavorited ? 'true' : 'false' }}"
                class="absolute top-5 right-5 z-10 flex h-8 w-8 items-center justify-center rounded-full bg-[#FDFBF7]/90 backdrop-blur-sm transition hover:scale-105 {{ $isFavorited ? 'text-red-500' : 'text-[#333333]/50 hover:text-red-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24"
                 fill="{{ $isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
            </svg>
        </button>
    @endauth
</div>