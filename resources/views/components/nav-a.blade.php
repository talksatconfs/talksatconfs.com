@props(['active'])

@php
$classes = ($active ?? false)
            ? 'px-3 py-2 rounded-md text-sm font-medium bg-gray-900 text-white'
            : 'px-3 py-2 rounded-md text-sm font-medium text-gray-300 border-1 border-gray-400 hover:bg-gray-700 hover:text-white';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
