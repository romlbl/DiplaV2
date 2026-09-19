@props(['title', 'updated' => null])

<x-layouts::public>
    <article class="mx-auto max-w-3xl space-y-8 rounded-2xl border border-[#E2E8F0] bg-[#FAFAFF] p-5 shadow-sm sm:p-8">
        <header class="space-y-2 border-b border-[#E2E8F0] pb-5">
            <h1 class="text-2xl font-bold text-[#1E293B] md:text-3xl">{{ $title }}</h1>
            @if($updated)
                <p class="text-sm text-[#333333]/60">Dernière mise à jour : {{ $updated }}</p>
            @endif
        </header>

        {{ $slot }}
    </article>
</x-layouts::public>