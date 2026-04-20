{{-- resources/views/kstl/reception/dashboard.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reception Dashboard</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ now()->format('l, d F Y') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-blue-700 bg-blue-50 px-3 py-1.5 rounded-full">
                    <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span>
                    Reception
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-4 flex items-center gap-3">
                    <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('warning'))
                <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4 flex items-center gap-3">
                    <svg class="w-4 h-4 text-yellow-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-yellow-800">{{ session('warning') }}</p>
                </div>
            @endif

            {{-- ── Summary Cards ─────────────────────────────────────────── --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">

                <div class="bg-white rounded-xl border border-gray-100 p-4">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Awaiting Receipt</span>
                        <div class="w-7 h-7 rounded-lg bg-yellow-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-medium text-gray-900">{{ $pending->where('status', 'submitted')->count() }}</p>
                    <p class="text-xs text-gray-400 mt-1">Submitted by client</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 p-4">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Assessing</span>
                        <div class="w-7 h-7 rounded-lg bg-purple-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-medium text-gray-900">{{ $pending->where('status', 'assessing')->count() }}</p>
                    <p class="text-xs text-gray-400 mt-1">Sample assessment</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 p-4">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Rejected</span>
                        <div class="w-7 h-7 rounded-lg bg-red-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-medium text-gray-900">{{ $pending->where('status', 'rejected')->count() }}</p>
                    <p class="text-xs text-gray-400 mt-1">Awaiting client decision</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 p-4">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Received Today</span>
                        <div class="w-7 h-7 rounded-lg bg-green-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-medium text-gray-900">
                        {{ $pending->where('received_at', '>=', now()->startOfDay())->count() }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Logged today</p>
                </div>

            </div>

            {{-- ── Pending Submissions Queue ──────────────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-800">Submissions Queue</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Submissions requiring reception action</p>
                    </div>
                    <span class="text-xs text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                        {{ $pending->count() }} total
                    </span>
                </div>

                @if($pending->isEmpty())
                    <div class="px-6 py-16 text-center">
                        <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-medium text-gray-400">No submissions pending</p>
                        <p class="text-xs text-gray-300 mt-1">All submissions have been processed.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Reference</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Client</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Sample Type</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Submitted</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Priority</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Status</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($pending as $submission)
                                    <tr class="hover:bg-gray-50 transition">

                                        {{-- Reference --}}
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-xs font-medium text-gray-700">
                                                {{ $submission->reference_number }}
                                            </span>
                                        </td>

                                        {{-- Client --}}
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-medium text-gray-800">
                                                {{ $submission->client->company_name ?? '—' }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                {{ $submission->client->responsible_officer_name ?? '' }}
                                            </p>
                                        </td>

                                        {{-- Sample Type --}}
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-700 capitalize">
                                                {{ $submission->sample_type }}
                                            </span>
                                        </td>

                                        {{-- Submitted --}}
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-700">
                                                {{ $submission->submitted_at?->format('d M Y') ?? $submission->created_at->format('d M Y') }}
                                            </p>
                                            @php
                                                $daysWaiting = $submission->submitted_at?->diffInDays(now()) ?? 0;
                                            @endphp
                                            @if($daysWaiting >= 2)
                                                <p class="text-xs text-amber-600 mt-0.5">⚠ {{ $daysWaiting }} days ago</p>
                                            @else
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $submission->submitted_at?->diffForHumans() ?? '' }}</p>
                                            @endif
                                        </td>

                                        {{-- Priority --}}
                                        <td class="px-6 py-4">
                                            @php
                                                $priorityConfig = [
                                                    'routine'   => 'bg-gray-100 text-gray-600',
                                                    'urgent'    => 'bg-amber-50 text-amber-700',
                                                    'emergency' => 'bg-red-50 text-red-700',
                                                ];
                                                $pc = $priorityConfig[$submission->priority ?? 'routine'] ?? 'bg-gray-100 text-gray-600';
                                            @endphp
                                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full {{ $pc }} capitalize">
                                                {{ $submission->priority ?? 'Routine' }}
                                            </span>
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-6 py-4">
                                            @php
                                                $statusConfig = [
                                                    'submitted'          => ['bg-yellow-50 text-yellow-700 ring-yellow-600/20', 'Awaiting Receipt'],
                                                    'received'           => ['bg-blue-50 text-blue-700 ring-blue-600/20',       'Received'],
                                                    'assessing'          => ['bg-purple-50 text-purple-700 ring-purple-600/20', 'Assessing'],
                                                    'rejected'           => ['bg-red-50 text-red-700 ring-red-600/20',          'Rejected'],
                                                    'accepted'           => ['bg-green-50 text-green-700 ring-green-600/20',   'Accepted'],
                                                    'consent_to_proceed' => ['bg-orange-50 text-orange-700 ring-orange-600/20', 'Consent Pending'],
                                                ];
                                                $sc = $statusConfig[$submission->status] ?? ['bg-gray-50 text-gray-500 ring-gray-500/20', ucfirst($submission->status)];
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ring-1 ring-inset {{ $sc[0] }}">
                                                {{ $sc[1] }}
                                            </span>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="px-6 py-4 text-right">
                                            @if($submission->status === 'submitted')
                                                <a href="{{ route('reception.submissions.show', $submission->id) }}"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    Receive
                                                </a>
                                            @elseif($submission->status === 'received')
                                                <a href="{{ route('reception.submissions.assess', $submission->id) }}"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-600 text-white text-xs font-medium rounded-lg hover:bg-purple-700 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                    </svg>
                                                    Assess
                                                </a>
                                            @elseif($submission->status === 'accepted')
                                                <a href="{{ route('reception.submissions.show', $submission->id) }}"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                    </svg>
                                                    Send to Testing
                                                </a>
                                            @elseif($submission->status === 'consent_to_proceed')
                                                <a href="{{ route('reception.submissions.show', $submission->id) }}"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                    </svg>
                                                    Send to Testing
                                                </a>
                                            @elseif($submission->status === 'rejected')
                                                <a href="{{ route('reception.submissions.consent', $submission->id) }}"
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-lg hover:bg-red-700 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Get Consent
                                                </a>
                                            @else
                                                <a href="{{ route('reception.submissions.show', $submission->id) }}"
                                                   class="text-xs text-gray-600 px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                                    View
                                                </a>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- ── Emergency / Urgent callout ─────────────────────────────── --}}
            @php
                $urgent = $pending->whereIn('priority', ['urgent', 'emergency']);
            @endphp
            @if($urgent->isNotEmpty())
                <div class="bg-red-50 border border-red-200 rounded-xl p-5">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-red-800">
                                {{ $urgent->count() }} urgent / emergency submission{{ $urgent->count() > 1 ? 's' : '' }} require immediate attention
                            </p>
                            <div class="mt-2 space-y-1">
                                @foreach($urgent as $u)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-red-700">
                                            <span class="font-mono font-medium">{{ $u->reference_number }}</span>
                                            — {{ $u->client->company_name ?? '?' }}
                                            <span class="ml-2 inline-flex px-1.5 py-0.5 text-xs bg-red-100 text-red-700 rounded capitalize">{{ $u->priority }}</span>
                                        </span>
                                        <a href="{{ route('reception.submissions.show', $u->id) }}"
                                           class="text-xs text-red-600 font-medium hover:underline">
                                            Action →
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif


            {{-- ── Recently Processed ──────────────────────────────────── --}}
            <div class="mt-6 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">Recently Processed</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Submissions sent to testing or completed — last 20 records</p>
                    </div>
                    <span class="text-xs text-gray-400 bg-gray-50 px-3 py-1 rounded-full border border-gray-100">
                        {{ $processed->count() }} records
                    </span>
                </div>

                @if($processed->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <svg class="w-8 h-8 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-sm text-gray-400">No processed submissions yet</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Reference</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Client</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Sample</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Priority</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Status</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Processed</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($processed as $submission)
                                    @php
                                        $statusColors = [
                                            'testing'                => 'bg-blue-50 text-blue-700',
                                            'awaiting_authorisation' => 'bg-purple-50 text-purple-700',
                                            'authorised'             => 'bg-green-50 text-green-700',
                                            'completed'              => 'bg-green-100 text-green-800',
                                            'rejected'               => 'bg-red-50 text-red-700',
                                            'cancelled'              => 'bg-gray-100 text-gray-500',
                                        ];
                                        $statusColor = $statusColors[$submission->status] ?? 'bg-gray-100 text-gray-500';
                                        $statusLabels = [
                                            'testing'                => 'Testing',
                                            'awaiting_authorisation' => 'Awaiting Auth.',
                                            'authorised'             => 'Authorised',
                                            'completed'              => 'Completed',
                                            'rejected'               => 'Rejected',
                                            'cancelled'              => 'Cancelled',
                                        ];
                                        $icon = '';
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-3.5 font-mono text-xs text-gray-600 font-medium">
                                            {{ $submission->reference_number }}
                                        </td>
                                        <td class="px-6 py-3.5">
                                            <p class="text-gray-800 font-medium text-xs">{{ $submission->client->company_name ?? '—' }}</p>
                                            <p class="text-gray-400 text-xs">{{ $submission->client->responsible_officer_name ?? '' }}</p>
                                        </td>
                                        <td class="px-6 py-3.5 text-xs text-gray-600">
                                            {{ $submission->sample_name }}
                                            <span class="ml-1 text-gray-400 capitalize">· {{ $submission->sample_type }}</span>
                                        </td>
                                        <td class="px-6 py-3.5">
                                            <span class="inline-flex px-2 py-0.5 text-xs rounded-full capitalize
                                                {{ $submission->priority === 'urgent' ? 'bg-orange-50 text-orange-700' :
                                                   ($submission->priority === 'emergency' ? 'bg-red-50 text-red-700' : 'bg-gray-100 text-gray-500') }}">
                                                {{ $submission->priority ?? 'routine' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3.5">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-xs font-medium rounded-full {{ $statusColor }}">
                                                {{ $statusLabels[$submission->status] ?? ucfirst(str_replace('_', ' ', $submission->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3.5 text-xs text-gray-400">
                                            {{ $submission->updated_at->diffForHumans() }}
                                            <p class="text-gray-300 text-xs">{{ $submission->updated_at->format('d M Y H:i') }}</p>
                                        </td>
                                        <td class="px-6 py-3.5 text-right">
                                            <a href="{{ route('reception.submissions.show', $submission->id) }}"
                                               class="text-xs text-gray-500 hover:text-gray-700 px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        <div class="pb-12"></div>

        </div>
    </div>
</x-app-layout>