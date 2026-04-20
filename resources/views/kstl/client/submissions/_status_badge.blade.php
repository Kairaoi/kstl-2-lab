{{-- resources/views/kstl/client/submissions/_status_badge.blade.php --}}

@php
    $config = match($status) {
        'pending'     => ['bg-yellow-50 text-yellow-700 ring-yellow-600/20', 'Pending'],
        'accepted'    => ['bg-blue-50 text-blue-700 ring-blue-600/20',       'Accepted'],
        'rejected'    => ['bg-red-50 text-red-700 ring-red-600/20',          'Rejected'],
        'in_progress' => ['bg-purple-50 text-purple-700 ring-purple-600/20', 'In Progress'],
        'authorised'  => ['bg-green-50 text-green-700 ring-green-600/20',    'Authorised'],
        default       => ['bg-gray-50 text-gray-600 ring-gray-500/20',       ucfirst($status)],
    };
@endphp

<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ring-1 ring-inset {{ $config[0] }}">
    {{ $config[1] }}
</span>