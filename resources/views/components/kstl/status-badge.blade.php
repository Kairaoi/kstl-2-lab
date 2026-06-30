@props(['status'])

@php
    $map = [
        // ── Submission lifecycle ──
        'submitted'              => ['Awaiting Receipt', 'bg-yellow-50 text-yellow-700 ring-yellow-600/20'],
        'received'               => ['Received',         'bg-blue-50 text-blue-700 ring-blue-600/20'],
        'assessing'              => ['Assessing',        'bg-purple-50 text-purple-700 ring-purple-600/20'],
        'accepted'               => ['Accepted',         'bg-green-50 text-green-700 ring-green-600/20'],
        'consent_to_proceed'     => ['Consent Pending',  'bg-orange-50 text-orange-700 ring-orange-600/20'],
        'testing'                => ['Testing',          'bg-blue-50 text-blue-700 ring-blue-600/20'],
        'awaiting_authorisation' => ['Awaiting Auth.',   'bg-purple-50 text-purple-700 ring-purple-600/20'],
        'authorised'             => ['Authorised',       'bg-green-50 text-green-700 ring-green-600/20'],
        'completed'              => ['Completed',        'bg-green-100 text-green-800 ring-green-700/20'],
        'rejected'               => ['Rejected',         'bg-red-50 text-red-700 ring-red-600/20'],
        'cancelled'              => ['Cancelled',        'bg-gray-100 text-gray-500 ring-gray-500/20'],

        // ── Sample-test status ──
        'queued'                 => ['Queued',           'bg-yellow-50 text-yellow-700 ring-yellow-600/20'],
        'in_progress'            => ['In Progress',      'bg-blue-50 text-blue-700 ring-blue-600/20'],
        'flagged'                => ['Queried',           'bg-amber-100 text-amber-800 ring-amber-600/30'],
    ];

    [$label, $classes] = $map[$status]
        ?? [ucfirst(str_replace('_', ' ', $status)), 'bg-gray-50 text-gray-500 ring-gray-500/20'];
@endphp

<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ring-1 ring-inset {{ $classes }}">
    {{ $label }}
</span>