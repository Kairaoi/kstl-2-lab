@props(['priority' => 'routine'])

@php
    $p = $priority ?: 'routine';
    $classes = [
        'routine'   => 'bg-gray-100 text-gray-600',
        'urgent'    => 'bg-amber-50 text-amber-700',
        'emergency' => 'bg-red-50 text-red-700',
    ][$p] ?? 'bg-gray-100 text-gray-600';
@endphp

<span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full capitalize {{ $classes }}">
    {{ $p }}
</span>