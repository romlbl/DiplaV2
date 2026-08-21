<div>
    @auth
        <button wire:click="toggle"
                class="inline-flex items-center justify-center rounded-full border border-[#E2E8F0] w-11 h-11 transition hover:bg-[#FAFAFF] {{ $isFavorited ? 'bg-[#4A3B5C]/10 border-[#4A3B5C]' : '' }}">
            <span class="text-xl {{ $isFavorited ? 'text-[#4A3B5C]' : 'text-[#333333]/40' }}">
                {{ $isFavorited ? '♥' : '♡' }}
            </span>
        </button>
    @endauth
</div>