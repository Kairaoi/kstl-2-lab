@props([
    'label',
    'value',
    'subtext' => null,
    'color'   => 'gray',
])

@php
    $chip = [
        'yellow' => 'bg-yellow-100 text-yellow-600',
        'blue'   => 'bg-blue-100 text-blue-600',
        'green'  => 'bg-green-100 text-green-600',
        'red'    => 'bg-red-100 text-red-500',
        'purple' => 'bg-purple-100 text-purple-600',
        'indigo' => 'bg-indigo-100 text-indigo-600',
        'orange' => 'bg-orange-100 text-orange-600',
        'gray'   => 'bg-gray-100 text-gray-600',
    ][$color] ?? 'bg-gray-100 text-gray-600';
@endphp

<div class="bg-white rounded-xl border border-gray-100 p-4 transition hover:border-gray-200 hover:shadow-sm">
    <div class="flex items-start justify-between mb-3">
        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $label }}</span>
        @isset($icon)
            <div class="w-7 h-7 rounded-lg flex items-center justify-center {{ $chip }} [&>svg]:w-4 [&>svg]:h-4">
                {{ $icon }}
            </div>
        @endisset
    </div>
    <p class="text-3xl font-medium text-gray-900">{{ $value }}</p>
    @if($subtext)
        <p class="text-xs text-gray-400 mt-1">{{ $subtext }}</p>
    @endif
</div>