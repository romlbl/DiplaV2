@props(['rows' => 4])

<div x-data="{
        text: '',
        init() { this.text = this.$refs.field.value; },
        wrap(marker) {
            const el = this.$refs.field;
            const start = el.selectionStart;
            const end = el.selectionEnd;
            el.value = el.value.slice(0, start) + marker + el.value.slice(start, end) + marker + el.value.slice(end);
            el.focus();
            el.setSelectionRange(start + marker.length, end + marker.length);
            el.dispatchEvent(new Event('input', { bubbles: true }));
        },
        preview() {
            let html = this.text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            html = html.replace(/\*\*(.+?)\*\*/gs, '<strong>$1</strong>');
            html = html.replace(/__(.+?)__/gs, '<u>$1</u>');
            return html.replace(/\n/g, '<br>');
        },
     }">
    <div class="flex items-center gap-1 rounded-t-xl border border-b-0 border-[#E2E8F0] bg-white px-2 py-1">
        <button type="button" @click="wrap('**')" title="Gras" aria-label="Gras"
                class="h-9 w-9 rounded-lg text-sm font-bold text-[#1E293B] transition hover:bg-[#FDFBF7]">B</button>
        <button type="button" @click="wrap('__')" title="Souligné" aria-label="Souligné"
                class="h-9 w-9 rounded-lg text-sm font-semibold text-[#1E293B] underline transition hover:bg-[#FDFBF7]">U</button>
    </div>

    <textarea x-ref="field" x-model="text" rows="{{ $rows }}"
              {{ $attributes->merge(['class' => 'w-full rounded-b-xl rounded-t-none border border-[#E2E8F0] bg-[#FDFBF7] px-4 py-2.5 text-sm text-[#333333] focus:border-[#1E3D59] focus:outline-none focus:ring-2 focus:ring-[#1E3D59]/20']) }}>{{ $slot }}</textarea>

    <div class="mt-2 rounded-xl border border-[#E2E8F0] bg-white p-3">
        <p class="mb-1 text-xs font-medium text-[#333333]/50">Aperçu</p>
        <div class="whitespace-pre-line break-words text-sm text-[#333333]" x-html="preview()"></div>
    </div>
</div>