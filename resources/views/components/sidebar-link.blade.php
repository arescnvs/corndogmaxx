@props(['href', 'active' => false, 'icon'])

<a
    href="{{ $href }}"
    wire:navigate
    {{ $attributes->merge([
        'class' => 'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 ' .
            ($active
                ? 'bg-blue-600 text-white shadow-sm'
                : 'text-gray-300 hover:bg-zinc-800 hover:text-white')
    ]) }}
>
    <x-icon :name="$icon" class="w-5 h-5" />
    {{ $slot }}
</a>