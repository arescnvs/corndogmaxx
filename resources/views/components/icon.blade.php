@props(['name'])

@php
$icons = [
    'home' => '<path d="M3 12l2-2m7-7l-2 2m2-2v7m5-5l2 2m-2-2v7m-9 4h12a1 1 0 001-1V8a1 1 0 00-.293-.707l-6-6a1 1 0 00-1.414 0l-6 6A1 1 0 003 8v8a1 1 0 001 1z"/>',
    'archive-box' => '<path d="M20 7l-1-1H5l-1 1m4 4h8m-8 4h8m-8-8h.01M5 3h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V4a1 1 0 011-1z"/>',
    'queue-list' => '<path d="M3 4h13M3 8h9m-9 4h6m4 4H3m13-4h3m-3 4h3"/>',
    'plus-circle' => '<path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>',
    'arrow-right-on-rectangle' => '<path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>',
];
@endphp

<svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" viewBox="0 0 24 24">
    {!! $icons[$name] ?? '<path d="M12 4v16m8-8H4"/>' !!}
</svg>