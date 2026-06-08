{{-- resources/views/kstl/analyst/dashboard.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        @php
            $activeCount  = $activeSubmissions->count();
            $flaggedTotal = $counts['flagged'] ?? 0;
        @endphp
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Analyst Dashboard</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ now()->format('l, d F Y') }}</p>
            </div>
            <div class="flex items-center gap-2">
                @if($flaggedTotal > 0)
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-orange-700 bg-orange-50 ring-1 ring-inset ring-orange-600/20 px-3 py-1.5 rounded-full">
                        {{ $flaggedTotal }} flagged
                    </span>
                @endif
                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 bg-gray-100 px-3 py-1.5 rounded-full">
                    {{ $activeCount }} active
                </span>
                <span class="inline-flex items-center gap-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 px-3 py-1.5 rounded-full">
                    <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-pulse"></span>
                    Analyst
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <x-kstl.flash />

            {{-- ── Summary Cards ─────────────────────────────────── --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <a href="{{ route('analyst.tests.index') }}" class="block">
                    <x-kstl.stat-card label="Queued" :value="$counts['queued'] ?? 0"
                                      subtext="Awaiting analyst" color="yellow">
                        <x-slot:icon>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </x-slot:icon>
                    </x-kstl.stat-card>
                </a>

                <a href="{{ route('analyst.tests.index') }}" class="block">
                    <x-kstl.stat-card label="In Progress" :value="$counts['in_progress'] ?? 0"
                                      subtext="Currently running" color="blue">
                        <x-slot:icon>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </x-slot:icon>
                    </x-kstl.stat-card>
                </a>

                <a href="{{ route('analyst.results.index') }}" class="block">
                    <x-kstl.stat-card label="Completed" :value="$counts['completed'] ?? 0"
                                      subtext="Results entered" color="green">
                        <x-slot:icon>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </x-slot:icon>
                    </x-kstl.stat-card>
                </a>

                <a href="{{ route('analyst.tests.index') }}" class="block">
                    <x-kstl.stat-card label="Flagged" :value="$counts['flagged'] ?? 0"
                                      subtext="Needs review" color="red">
                        <x-slot:icon>
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                            </svg>
                        </x-slot:icon>
                    </x-kstl.stat-card>
                </a>
            </div>

            {{-- ── Result Summary Cards ─────────────────────────── --}}
            @php
                $allTests = $queue ?? collect();
                $completedTests = $allTests->whereIn('status', ['completed', 'flagged']);
                $passCount = $completedTests->whereIn('result_qualifier', ['pass', 'not_detected'])->count();
                $failCount = $completedTests->whereIn('result_qualifier', ['fail', 'detected'])->count();
                $otherCount = $completedTests->whereNotIn('result_qualifier', ['pass', 'not_detected', 'fail', 'detected', 'pending'])->count();
                $totalCompleted = $completedTests->count();
            @endphp

            @if($totalCompleted > 0)
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200 p-4">
                        <div class="flex items-start justify-between mb-2">
                            <span class="text-xs font-medium text-green-700 uppercase tracking-wider">Pass / Not Detected</span>
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-green-900">{{ $passCount }}</p>
                        <p class="text-xs text-green-600 mt-1">
                            {{ $totalCompleted > 0 ? round(($passCount / $totalCompleted) * 100) : 0 }}% of completed tests
                        </p>
                    </div>

                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl border border-red-200 p-4">
                        <div class="flex items-start justify-between mb-2">
                            <span class="text-xs font-medium text-red-700 uppercase tracking-wider">Fail / Detected</span>
                            <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-red-900">{{ $failCount }}</p>
                        <p class="text-xs text-red-600 mt-1">
                            {{ $totalCompleted > 0 ? round(($failCount / $totalCompleted) * 100) : 0 }}% of completed tests
                        </p>
                    </div>

                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200 p-4">
                        <div class="flex items-start justify-between mb-2">
                            <span class="text-xs font-medium text-gray-700 uppercase tracking-wider">Other Results</span>
                            <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <p class="text-3xl font-bold text-gray-900">{{ $otherCount }}</p>
                        <p class="text-xs text-gray-600 mt-1">
                            Less than, greater than, etc.
                        </p>
                    </div>
                </div>
            @endif

            {{-- ── Flagged for clarification (surfaced near the top) ───── --}}
            @php
                $flaggedGroups = $activeSubmissions->filter(fn($g) => ($g['flagged'] ?? 0) > 0);
            @endphp
            @if($flaggedGroups->isNotEmpty())
                <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-orange-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-orange-800">
                                {{ $flaggedGroups->sum('flagged') }} test{{ $flaggedGroups->sum('flagged') > 1 ? 's' : '' }}
                                flagged for clarification — these need your attention
                            </p>
                            <div class="mt-2 space-y-1">
                                @foreach($flaggedGroups as $g)
                                    @php $firstFlagged = $g['tests']->firstWhere('status', 'flagged'); @endphp
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-orange-700">
                                            <span class="font-mono font-medium">{{ $g['submission']->reference_number }}</span>
                                            — {{ $g['submission']->client->company_name ?? '?' }}
                                            <span class="ml-2 inline-flex px-1.5 py-0.5 text-xs bg-orange-100 text-orange-700 rounded">{{ $g['flagged'] }} flagged</span>
                                        </span>
                                        @if($firstFlagged)
                                            <a href="{{ route('analyst.tests.show', $firstFlagged->id) }}"
                                               class="text-xs text-orange-600 font-medium hover:underline">
                                                Review →
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── My Tests (Grouped by Submission) ──────────────────── --}}
            @php
                // Triage: groups with flagged tests first, then by priority, then least-complete first.
                $priorityRank = ['emergency' => 0, 'urgent' => 1, 'routine' => 2];
                $activeSorted = $activeSubmissions->sortBy(fn($g) => [
                    ($g['flagged'] ?? 0) > 0 ? 0 : 1,
                    $priorityRank[$g['submission']->priority ?? 'routine'] ?? 2,
                    ($g['total'] ?? 0) > 0 ? ($g['done'] / $g['total']) : 1,
                ])->values();
            @endphp
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-800">My Tests</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Grouped by submission — all tests assigned to or started by you</p>
                    </div>
                    <a href="{{ route('analyst.tests.index') }}"
                       class="text-xs text-blue-600 hover:underline">View all tests →</a>
                </div>

                @if($activeSubmissions->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-medium text-gray-400">No tests assigned</p>
                        <p class="text-xs text-gray-300 mt-1">Tests you work on will appear here.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($activeSorted as $group)
                            @php
                                $submission  = $group['submission'];
                                $tests       = $group['tests'];
                                $total       = $group['total'];
                                $done        = $group['done'];
                                $flagged     = $group['flagged'];
                                $progress    = $total > 0 ? round(($done / $total) * 100) : 0;
                            @endphp
                            <div class="px-6 py-4" x-data="{ open: true }">

                                {{-- Submission header row --}}
                                <div class="flex items-center justify-between cursor-pointer"
                                     @click="open = !open">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-mono text-sm font-semibold text-gray-800">
                                                    {{ $submission->reference_number }}
                                                </span>
                                                <span class="text-sm text-gray-600">
                                                    {{ $submission->client->responsible_officer_name ?? $submission->client->user->name ?? '—' }}
                                                </span>
                                                @if($flagged > 0)
                                                    <span class="inline-flex px-2 py-0.5 text-xs bg-orange-50 text-orange-700 rounded-full font-medium">
                                                        {{ $flagged }} flagged
                                                    </span>
                                                @endif
                                                @if($progress === 100)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs bg-green-50 text-green-700 rounded-full font-medium">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    All complete
                                                </span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                {{ $submission->client->company_name ?? '—' }}
                                                &bull; {{ $submission->sample_name }}
                                                &bull; {{ ucfirst($submission->sample_type) }}
                                                @if($submission->priority !== 'routine')
                                                    &bull; <span class="text-orange-600 font-medium capitalize">{{ $submission->priority }}</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        {{-- Progress bar --}}
                                        <div class="hidden sm:block w-32">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xs text-gray-500">{{ $done }}/{{ $total }} done</span>
                                                <span class="text-xs font-medium {{ $progress === 100 ? 'text-green-600' : 'text-gray-600' }}">
                                                    {{ $progress }}%
                                                </span>
                                            </div>
                                            <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full transition-all
                                                    {{ $progress === 100 ? 'bg-green-500' : 'bg-indigo-500' }}"
                                                     style="width: {{ $progress }}%"></div>
                                            </div>
                                        </div>

                                        {{-- Expand/collapse --}}
                                        <svg class="w-4 h-4 text-gray-400 transition-transform"
                                             :class="open ? 'rotate-180' : ''"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>

                                {{-- Tests list (collapsible) --}}
                                <div x-show="open" class="mt-3">
                                    <div class="rounded-lg border border-gray-100 overflow-hidden">
                                        <table class="w-full text-xs">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="text-left px-4 py-2 font-medium text-gray-500 uppercase">Test</th>
                                                    <th class="text-left px-4 py-2 font-medium text-gray-500 uppercase">Category</th>
                                                    <th class="text-left px-4 py-2 font-medium text-gray-500 uppercase">Status</th>
                                                    <th class="text-left px-4 py-2 font-medium text-gray-500 uppercase">Result</th>
                                                    <th class="px-4 py-2"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-50">
                                                @foreach($tests as $test)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-4 py-2.5 font-medium text-gray-800">
                                                            {{ $test->getDisplayLabel() }}
                                                        </td>
                                                        <td class="px-4 py-2.5">
                                                            <span class="inline-flex px-1.5 py-0.5 text-xs rounded-full capitalize
                                                                {{ $test->getDisplayCategory() === 'microbiological' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">
                                                                {{ $test->getDisplayCategory() }}
                                                            </span>
                                                        </td>
                                                        <td class="px-4 py-2.5">
                                                            <x-kstl.status-badge :status="$test->status" />
                                                        </td>
                                                        <td class="px-4 py-2.5 text-xs">
                                                            @if($test->status === 'completed' || $test->status === 'flagged')
                                                                @php
                                                                    $qualColors = [
                                                                        'pass'         => 'text-green-700',
                                                                        'fail'         => 'text-red-700',
                                                                        'detected'     => 'text-red-700',
                                                                        'not_detected' => 'text-green-700',
                                                                        'less_than'    => 'text-gray-700',
                                                                        'greater_than' => 'text-gray-700',
                                                                        'equal_to'     => 'text-gray-700',
                                                                    ];
                                                                    $qualColor = $qualColors[$test->result_qualifier] ?? 'text-gray-600';
                                                                @endphp
                                                                @if($test->result_qualifier && $test->result_qualifier !== 'pending')
                                                                    <span class="font-medium {{ $qualColor }} capitalize block">
                                                                        {{ str_replace('_', ' ', $test->result_qualifier) }}
                                                                    </span>
                                                                @endif
                                                                @if($test->result_value)
                                                                    <span class="text-gray-600">
                                                                        {{ $test->result_value }}
                                                                        @if($test->result_unit) <span class="text-gray-400">{{ $test->result_unit }}</span> @endif
                                                                    </span>
                                                                @endif
                                                                @if(!$test->result_qualifier || $test->result_qualifier === 'pending')
                                                                    <span class="text-gray-400">—</span>
                                                                @endif
                                                            @else
                                                                <span class="text-gray-400">—</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 py-2.5 text-right">
                                                            <a href="{{ route('analyst.tests.show', $test->id) }}"
                                                               class="px-2.5 py-1 text-xs font-medium rounded-md transition
                                                               {{ $test->status === 'completed' ? 'bg-gray-100 text-gray-600 hover:bg-gray-200' : ($test->status === 'flagged' ? 'bg-orange-100 text-orange-700 hover:bg-orange-200' : 'bg-indigo-600 text-white hover:bg-indigo-700') }}">
                                                                {{ $test->status === 'completed' ? 'View' : ($test->status === 'flagged' ? 'Review' : ($test->status === 'in_progress' ? 'Continue' : 'Start')) }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── Historical Record ────────────────────────────────── --}}
            <div class="mt-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-medium text-gray-800">Testing History</h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            @if(($search ?? '') !== '')
                                Search results for reference “<span class="font-mono text-gray-600">{{ $search }}</span>” — searched across all records
                            @else
                                All submissions in testing, awaiting authorisation, authorised or completed — last 20
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        {{-- Reference number search (audit lookup across full history) --}}
                        <form method="GET" action="{{ route('analyst.dashboard') }}" class="flex items-center gap-2">
                            <div class="relative">
                                <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                                </svg>
                                <input type="text" name="ref" value="{{ $search ?? '' }}"
                                       placeholder="Search reference no."
                                       class="pl-8 pr-3 py-1.5 text-xs border border-gray-200 rounded-lg w-48 focus:border-indigo-400 focus:ring-indigo-400">
                            </div>
                            <button type="submit"
                                    class="px-3 py-1.5 text-xs font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                                Search
                            </button>
                            @if(($search ?? '') !== '')
                                <a href="{{ route('analyst.dashboard') }}"
                                   class="px-3 py-1.5 text-xs font-medium text-gray-500 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                    Clear
                                </a>
                            @endif
                        </form>
                        <span class="text-xs text-gray-400 bg-gray-50 px-3 py-1 rounded-full border border-gray-100 whitespace-nowrap">
                            {{ $history->count() }} {{ ($search ?? '') !== '' ? 'found' : 'records' }}
                        </span>
                    </div>
                </div>

                @if($history->isEmpty())
                    <div class="px-6 py-10 text-center">
                        @if(($search ?? '') !== '')
                            <p class="text-sm text-gray-400">No submissions found matching reference “<span class="font-mono">{{ $search }}</span>”.</p>
                            <a href="{{ route('analyst.dashboard') }}" class="text-xs text-indigo-600 hover:underline mt-1 inline-block">Clear search</a>
                        @else
                            <p class="text-sm text-gray-400">No completed submissions yet.</p>
                        @endif
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Reference</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Client</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Sample</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Tests</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Completed</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($history as $submission)
                                    @php
                                        $allTests     = $submission->samples->flatMap->sampleTests;
                                        $completedCnt = $allTests->where('status', 'completed')->count();
                                        $totalCnt     = $allTests->count();
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-3.5 font-mono text-xs font-semibold text-gray-700">
                                            {{ $submission->reference_number }}
                                        </td>
                                        <td class="px-6 py-3.5 text-xs text-gray-600">
                                            <p class="font-medium">{{ $submission->client->company_name ?? '—' }}</p>
                                            <p class="text-gray-400">{{ $submission->client->responsible_officer_name ?? '' }}</p>
                                        </td>
                                        <td class="px-6 py-3.5 text-xs text-gray-700">
                                            {{ $submission->sample_name }}
                                            <span class="text-gray-400 capitalize">· {{ $submission->sample_type }}</span>
                                        </td>
                                        <td class="px-6 py-3.5 text-xs text-gray-600">
                                            <span class="font-medium text-green-700">{{ $completedCnt }}</span>
                                            <span class="text-gray-400">/ {{ $totalCnt }} tests done</span>
                                        </td>
                                        <td class="px-6 py-3.5">
                                            <x-kstl.status-badge :status="$submission->status" />
                                        </td>
                                        <td class="px-6 py-3.5 text-xs text-gray-400">
                                            {{ $submission->updated_at->diffForHumans() }}
                                            <p class="text-gray-300">{{ $submission->updated_at->format('d M Y') }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>