@props(['rows' => 4])

<div x-data="{
        wrap(marker) {
            const el = this.$refs.field;
            const start = el.selectionStart;
            const end = el.selectionEnd;
            el.value = el.value.slice(0, start) + marker + el.value.slice(start, end) + marker + el.value.slice(end);
            el.focus();
            el.setSelectionRange(start + marker.length, end + marker.length);
            el.dispatchEvent(new Event('input', { bubbles: true }));
        },
     }">
    <div class="flex items-center gap-1 rounded-t-xl border border-b-0 border-[#E2E8F0] bg-white px-2 py-1">
        <button type="button" @click="wrap('**')" title="Gras" aria-label="Gras"
                class="h-9 w-9 rounded-lg text-sm font-bold text-[#1E293B] transition hover:bg-[#FDFBF7]">B</button>
        <button type="button" @click="wrap('__')" title="Souligné" aria-label="Souligné"
                class="h-9 w-9 rounded-lg text-sm font-semibold text-[#1E293B] underline transition hover:bg-[#FDFBF7]">U</button>
        <span class="ml-auto hidden text-xs text-[#333333]/50 sm:inline">Entrée = saut de ligne</span>
    </div>

    <textarea x-ref="field" rows="{{ $rows }}"
              {{ $attributes->merge(['class' => 'w-full rounded-b-xl rounded-t-none border border-[#E2E8F0] bg-[#FDFBF7] px-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20']) }}>{{ $slot }}</textarea>
</div>