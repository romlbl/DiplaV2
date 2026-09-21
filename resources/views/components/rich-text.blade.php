@props(['text' => null])

@php
    $html = e(str_replace("\r\n", "\n", (string) $text));
    $html = preg_replace('/\*\*(.+?)\*\*/u', '<strong>$1</strong>', $html);
    $html = preg_replace('/__(.+?)__/u', '<u>$1</u>', $html);
@endphp

<div {{ $attributes->merge(['class' => 'whitespace-pre-line break-words']) }}>{!! $html !!}</div>

