{{-- resources/views/kstl/reception/dashboard.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reception Dashboard</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ now()->format('l, d F Y') }}</p>
            </div>
            <div class="flex items-center gap-2">
                {{-- At-a-glance action summary --}}
                @php
                    $awaitingAction = $pending->count();
                    $urgentPending  = $pending->whereIn('priority', ['urgent', 'emergency'])->count();
                @endphp
                @if($urgentPending > 0)
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-700 bg-red-50 ring-1 ring-inset ring-red-600/20 px-3 py-1.5 rounded-full">
                        {{ $urgentPending }} urgent
                    </span>
                @endif
                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 bg-gray-100 px-3 py-1.5 rounded-full">
                    {{ $awaitingAction }} awaiting action
                </span>
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
            <x-kstl.flash />

            {{-- ── Summary Cards ─────────────────────────────────────────── --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">

                <x-kstl.stat-card label="Awaiting Receipt" :value="$pending->where('status', 'submitted')->count()"
                                  subtext="Submitted by client" color="yellow">
                    <x-slot:icon>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </x-slot:icon>
                </x-kstl.stat-card>

                <x-kstl.stat-card label="Assessing" :value="$pending->where('status', 'assessing')->count()"
                                  subtext="Sample assessment" color="purple">
                    <x-slot:icon>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </x-slot:icon>
                </x-kstl.stat-card>

                <x-kstl.stat-card label="Rejected" :value="$pending->where('status', 'rejected')->count()"
                                  subtext="Awaiting client decision" color="red">
                    <x-slot:icon>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                    </x-slot:icon>
                </x-kstl.stat-card>

                {{-- Driven by $receivedToday from the controller; falls back to 0 if not supplied --}}
                <x-kstl.stat-card label="Received Today" :value="$receivedToday ?? 0"
                                  subtext="Logged today" color="green">
                    <x-slot:icon>
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </x-slot:icon>
                </x-kstl.stat-card>

            </div>

            {{-- ── Emergency / Urgent callout (surfaced near the top) ──────── --}}
            @php
                $urgent = $pending->whereIn('priority', ['urgent', 'emergency'])
                                  ->sortBy(fn($s) => $s->priority === 'emergency' ? 0 : 1);
            @endphp
            @if($urgent->isNotEmpty())
                <div class="bg-red-50 border border-red-200 rounded-2xl p-5">
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

            {{-- ── Pending Submissions Queue ──────────────────────────────── --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">Submissions Queue</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Submissions requiring reception action — most urgent first</p>
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
                    @php
                        // Triage order: emergency → urgent → routine, then oldest-waiting first.
                        $priorityRank = ['emergency' => 0, 'urgent' => 1, 'routine' => 2];
                        $queue = $pending->sortBy(fn($s) => [
                            $priorityRank[$s->priority ?? 'routine'] ?? 2,
                            optional($s->submitted_at ?? $s->created_at)->timestamp,
                        ])->values();
                    @endphp
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
                                @foreach($queue as $submission)
                                    @php
                                        $isEmergency = ($submission->priority ?? '') === 'emergency';
                                        $isUrgent    = ($submission->priority ?? '') === 'urgent';
                                        $rowClass    = $isEmergency ? 'bg-red-50/40 hover:bg-red-50'
                                                     : ($isUrgent  ? 'bg-amber-50/30 hover:bg-amber-50'
                                                                   : 'hover:bg-gray-50');
                                        $accentClass = $isEmergency ? 'border-l-2 border-red-400'
                                                     : ($isUrgent  ? 'border-l-2 border-amber-400'
                                                                   : 'border-l-2 border-transparent');
                                    @endphp
                                    <tr class="{{ $rowClass }} transition">

                                        {{-- Reference (with priority accent bar) --}}
                                        <td class="px-6 py-4 {{ $accentClass }}">
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
                                                $daysWaiting = (int) floor(abs($submission->submitted_at?->diffInDays(now()) ?? 0));
                                            @endphp
                                            @if($daysWaiting >= 2)
                                                <span class="inline-flex items-center gap-1 text-xs text-amber-600 mt-0.5">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $daysWaiting }} days waiting
                                                </span>
                                            @else
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $submission->submitted_at?->diffForHumans() ?? '' }}</p>
                                            @endif
                                        </td>

                                        {{-- Priority --}}
                                        <td class="px-6 py-4">
                                            <x-kstl.priority-badge :priority="$submission->priority" />
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-6 py-4">
                                            <x-kstl.status-badge :status="$submission->status" />
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

            {{-- ── Recently Processed ──────────────────────────────────── --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
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
                                            <x-kstl.priority-badge :priority="$submission->priority" />
                                        </td>
                                        <td class="px-6 py-3.5">
                                            <x-kstl.status-badge :status="$submission->status" />
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