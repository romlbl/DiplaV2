@props(['title'])

<section class="space-y-3">
    <h2 class="text-lg font-semibold text-[#1E293B]">{{ $title }}</h2>
    <div class="space-y-3 text-sm leading-relaxed text-[#333333]/90 md:text-base">
        {{ $slot }}
    </div>
</section>