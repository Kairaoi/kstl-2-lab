{{-- resources/views/kstl/analyst/tests/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="aq-eyebrow">Analyst</p>
                <h2 class="aq-title text-xl font-bold leading-tight mt-0.5">Test Queue</h2>
            </div>
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('analyst.tests.index') }}">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox"
                               name="mine"
                               value="1"
                               {{ request('mine') ? 'checked' : '' }}
                               onchange="this.form.submit()"
                               class="rounded text-indigo-600 focus:ring-indigo-500">
                        My tests only
                    </label>
                </form>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .aq-eyebrow { letter-spacing: .16em; text-transform: uppercase; font-size: 10px; color: var(--gold); font-weight: 700; }
        .aq-title { font-family: 'Noto Serif', serif; color: var(--navy); letter-spacing: .01em; }
        .aq-section-title {
            font-family: 'Noto Serif', serif; color: var(--navy);
            font-size: 13px; font-weight: 700; letter-spacing: .02em;
            display: flex; align-items: center; gap: 8px;
        }
        .aq-section-title::before {
            content: ''; width: 3px; height: 14px; background: var(--gold); border-radius: 2px; display: inline-block;
        }
    </style>
    @endpush

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg flex items-center gap-3">
                    <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="aq-section-title">
                        All Tests
                        <span class="ml-2 text-xs text-gray-400 font-normal">({{ $queue->count() }} total)</span>
                    </h3>
                </div>

                @if($queue->isEmpty())
                    <div class="px-6 py-16 text-center">
                        <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-medium text-gray-400">No tests assigned</p>
                        <p class="text-xs text-gray-300 mt-1">Tests you work on will appear here.</p>
                    </div>
                @else
                    @php
                        // Group tests by submission
                        $grouped = $queue->groupBy(function($test) {
                            return $test->sample->submission->id;
                        });
                    @endphp

                    <div class="divide-y divide-gray-100">
                        @foreach($grouped as $submissionId => $tests)
                            @php
                                $submission = $tests->first()->sample->submission;
                                $client = $submission->client;
                                $completedCount = $tests->whereIn('status', ['completed', 'flagged'])->count();
                                $totalCount = $tests->count();
                                $progress = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
                                $flaggedCount = $tests->where('status', 'flagged')->count();
                            @endphp

                            <div class="px-6 py-4" x-data="{ open: true }">
                                {{-- Submission header --}}
                                <div class="flex items-center justify-between cursor-pointer mb-4"
                                     @click="open = !open">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-mono text-sm font-semibold text-gray-800">
                                                    {{ $submission->reference_number }}
                                                </span>
                                                <span class="text-sm text-gray-600">
                                                    {{ $client->responsible_officer_name ?? $client->user->name ?? '—' }}
                                                </span>
                                                @if($flaggedCount > 0)
                                                    <span class="inline-flex px-2 py-0.5 text-xs bg-orange-50 text-orange-700 rounded-full font-medium">
                                                        {{ $flaggedCount }} flagged
                                                    </span>
                                                @endif
                                                @if($progress === 100)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs bg-green-50 text-green-700 rounded-full font-medium">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                        All complete
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                {{ $client->company_name ?? '—' }}
                                                &bull; {{ $submission->sample_name }}
                                                &bull; {{ ucfirst($submission->sample_type) }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        {{-- Progress --}}
                                        <div class="flex items-center gap-2">
                                            <div class="w-24 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-green-500 rounded-full transition-all"
                                                     style="width: {{ $progress }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-400 font-medium whitespace-nowrap">
                                                {{ $completedCount }}/{{ $totalCount }}
                                            </span>
                                        </div>

                                        {{-- View authorised result (analyst's own read-only report) --}}
                                        @if($progress === 100 && $submission->result)
                                            <a href="{{ route('analyst.results.show', $submission->id) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition"
                                               title="View the authorised result">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                View Result
                                            </a>
                                        @endif

                                        {{-- Chevron --}}
                                        <svg class="w-4 h-4 text-gray-400 transition-transform"
                                             :class="{ 'rotate-180': open }"
                                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>

                                {{-- Tests table --}}
                                <div x-show="open" x-collapse>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-sm">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="text-left px-4 py-2 text-xs font-medium text-gray-500 uppercase">Sample</th>
                                                    <th class="text-left px-4 py-2 text-xs font-medium text-gray-500 uppercase">Test</th>
                                                    <th class="text-left px-4 py-2 text-xs font-medium text-gray-500 uppercase">Category</th>
                                                    <th class="text-left px-4 py-2 text-xs font-medium text-gray-500 uppercase">Assigned To</th>
                                                    <th class="text-left px-4 py-2 text-xs font-medium text-gray-500 uppercase">Status</th>
                                                    <th class="text-left px-4 py-2 text-xs font-medium text-gray-500 uppercase">Result</th>
                                                    <th class="px-4 py-2"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-50">
                                                @foreach($tests as $test)
                                                    @php
                                                        $rowBg = $test->status === 'in_progress' ? 'bg-blue-50/30' : ($test->status === 'completed' ? 'bg-green-50/20' : ($test->status === 'flagged' ? 'bg-amber-50/50' : ''));
                                                        $directorQuery = null;
                                                        if ($test->status === 'flagged' && $test->result_notes) {
                                                            preg_match('/\[Director query\]\s*(.+?)(?=\n\n|$)/s', $test->result_notes, $dqMatch);
                                                            $directorQuery = isset($dqMatch[1]) ? trim($dqMatch[1]) : null;
                                                        }
                                                    @endphp
                                                    <tr class="hover:bg-gray-50 transition {{ $rowBg }}" x-data="{ showModal: false }">
                                                        <td class="px-4 py-2.5">
                                                            <p class="font-medium text-gray-800 text-xs">{{ $test->sample->sample_code }}</p>
                                                            <p class="text-xs text-gray-400 mt-0.5">{{ $test->sample->common_name }}</p>
                                                        </td>
                                                        <td class="px-4 py-2.5 text-sm text-gray-700">
                                                            {{ $test->getDisplayLabel() }}
                                                        </td>
                                                        <td class="px-4 py-2.5">
                                                            <span class="inline-flex px-2 py-0.5 text-xs rounded-full capitalize
                                                                {{ $test->getDisplayCategory() === 'microbiological' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">
                                                                {{ $test->getDisplayCategory() }}
                                                            </span>
                                                        </td>
                                                        <td class="px-4 py-2.5 text-xs text-gray-500">
                                                            {{ $test->assignedTo?->name ?? '—' }}
                                                        </td>
                                                        <td class="px-4 py-2.5">
                                                            <x-kstl.status-badge :status="$test->status" />
                                                            @if($test->status === 'flagged' && $directorQuery)
                                                                <p class="mt-1.5 text-xs text-amber-700 leading-snug max-w-[200px]">
                                                                    <span class="font-semibold">Director:</span>
                                                                    {{ Str::limit($directorQuery, 80) }}
                                                                </p>
                                                            @endif
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
                                                                <div class="flex flex-col gap-0.5">
                                                                    @if($test->result_qualifier && $test->result_qualifier !== 'pending')
                                                                        <span class="font-medium {{ $qualColor }} capitalize">
                                                                            {{ str_replace('_', ' ', $test->result_qualifier) }}
                                                                        </span>
                                                                    @endif
                                                                    @if($test->result_value)
                                                                        <span class="text-gray-600">
                                                                            {{ $test->result_value }}
                                                                            @if($test->result_unit)
                                                                                <span class="text-gray-400">{{ $test->result_unit }}</span>
                                                                            @endif
                                                                        </span>
                                                                    @endif
                                                                    @if($test->result_notes)
                                                                        <button type="button"
                                                                                @click="showModal = true"
                                                                                class="inline-flex items-center gap-1 text-xs text-blue-600 hover:underline text-left mt-1">
                                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                                            View details
                                                                        </button>

                                                                        {{-- Modal for comprehensive result details --}}
                                                                        <div x-show="showModal"
                                                                             x-cloak
                                                                             @click.away="showModal = false"
                                                                             class="fixed inset-0 z-50 overflow-y-auto"
                                                                             style="display: none;">
                                                                            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                                                                                <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>

                                                                                <div class="bg-white rounded-xl overflow-hidden shadow-2xl transform transition-all sm:max-w-4xl sm:w-full relative max-h-[90vh] overflow-y-auto">
                                                                                    {{-- Header with close button --}}
                                                                                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 px-6 py-5 sticky top-0 z-10">
                                                                                        <div class="flex items-center justify-between">
                                                                                            <div class="flex items-center gap-3">
                                                                                                <svg class="w-6 h-6 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                                                </svg>
                                                                                                <div>
                                                                                                    <h3 class="text-xl font-bold text-white">
                                                                                                        Test Results — {{ $test->sample->submission->reference_number }}
                                                                                                    </h3>
                                                                                                    <p class="text-sm text-indigo-100 mt-0.5">{{ $test->sample->common_name }}</p>
                                                                                                </div>
                                                                                            </div>
                                                                                            <button @click="showModal = false" class="text-indigo-200 hover:text-white transition">
                                                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                                                </svg>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="p-6">
                                                                                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                                                                            {{-- Left: Submission Info --}}
                                                                                            <div class="space-y-4">
                                                                                                <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
                                                                                                    <h4 class="text-xs font-bold text-gray-700 uppercase mb-3 flex items-center gap-2">
                                                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                                                                        </svg>
                                                                                                        Submission
                                                                                                    </h4>
                                                                                                    <dl class="space-y-3 text-sm">
                                                                                                        <div>
                                                                                                            <dt class="text-xs text-gray-500 font-medium">Reference</dt>
                                                                                                            <dd class="font-mono text-gray-900 mt-1 font-semibold">{{ $test->sample->submission->reference_number }}</dd>
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <dt class="text-xs text-gray-500 font-medium">Client</dt>
                                                                                                            <dd class="text-gray-900 mt-1 font-medium">{{ $test->sample->submission->client->responsible_officer_name ?? $test->sample->submission->client->user->name }}</dd>
                                                                                                            <dd class="text-xs text-gray-600">{{ $test->sample->submission->client->company_name }}</dd>
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <dt class="text-xs text-gray-500 font-medium">Sample</dt>
                                                                                                            <dd class="text-gray-900 mt-1">{{ $test->sample->sample_code }}</dd>
                                                                                                            <dd class="text-sm text-gray-700">{{ $test->sample->common_name }}</dd>
                                                                                                        </div>
                                                                                                        <div>
                                                                                                            <dt class="text-xs text-gray-500 font-medium">Type</dt>
                                                                                                            <dd class="text-gray-900 mt-1 capitalize">{{ $test->sample->submission->sample_type }}</dd>
                                                                                                        </div>
                                                                                                    </dl>
                                                                                                </div>
                                                                                            </div>

                                                                                            {{-- Right: Test Results --}}
                                                                                            <div class="lg:col-span-2 space-y-4">
                                                                                                {{-- Main Result Card --}}
                                                                                                @php
                                                                                                    $isPass = in_array($test->result_qualifier, ['pass', 'not_detected']);
                                                                                                    $cardBg = $isPass ? 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-300' : 'bg-gradient-to-br from-red-50 to-orange-50 border-red-300';
                                                                                                @endphp
                                                                                                <div class="border-2 {{ $cardBg }} rounded-xl p-5 shadow-sm">
                                                                                                    <div class="flex items-start justify-between mb-4">
                                                                                                        <div>
                                                                                                            <h4 class="text-lg font-bold text-gray-900">{{ $test->getDisplayLabel() }}</h4>
                                                                                                            <p class="text-sm text-gray-600 mt-1 capitalize flex items-center gap-1.5">
                                                                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                                                                                </svg>
                                                                                                                {{ $test->getDisplayCategory() }}
                                                                                                            </p>
                                                                                                        </div>
                                                                                                        <span class="inline-flex px-4 py-2 text-base font-bold rounded-full capitalize shadow-sm {{ $isPass ? 'bg-green-100 text-green-800 border-2 border-green-300' : 'bg-red-100 text-red-800 border-2 border-red-300' }}">
                                                                                                            {{ str_replace('_', ' ', $test->result_qualifier) }}
                                                                                                        </span>
                                                                                                    </div>

                                                                                                    <div class="grid grid-cols-2 gap-4 mt-4">
                                                                                                        @if($test->result_value)
                                                                                                            <div class="bg-white bg-opacity-60 rounded-lg p-3 border border-gray-200">
                                                                                                                <dt class="text-xs font-semibold text-gray-600 uppercase">Result Value</dt>
                                                                                                                <dd class="mt-1.5 text-2xl font-bold text-gray-900">
                                                                                                                    {{ $test->result_value }}
                                                                                                                    @if($test->result_unit)
                                                                                                                        <span class="text-base text-gray-600 font-medium ml-1">{{ $test->result_unit }}</span>
                                                                                                                    @endif
                                                                                                                </dd>
                                                                                                            </div>
                                                                                                        @endif

                                                                                                        @if($test->completed_at)
                                                                                                            <div class="bg-white bg-opacity-60 rounded-lg p-3 border border-gray-200">
                                                                                                                <dt class="text-xs font-semibold text-gray-600 uppercase">Completed</dt>
                                                                                                                <dd class="mt-1.5 text-sm font-medium text-gray-900">
                                                                                                                    {{ $test->completed_at->format('d M Y') }}
                                                                                                                </dd>
                                                                                                                <dd class="text-xs text-gray-600 mt-0.5">
                                                                                                                    {{ $test->completed_at->format('H:i') }}
                                                                                                                    @if($test->assignedTo)
                                                                                                                        • {{ $test->assignedTo->name }}
                                                                                                                    @endif
                                                                                                                </dd>
                                                                                                            </div>
                                                                                                        @endif
                                                                                                    </div>
                                                                                                </div>

                                                                                                {{-- Analyst Notes --}}
                                                                                                @if($test->result_notes)
                                                                                                    <div class="bg-blue-50 rounded-xl border-2 border-blue-200 p-4">
                                                                                                        <h4 class="text-sm font-bold text-blue-900 uppercase mb-2 flex items-center gap-2">
                                                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                                                                            </svg>
                                                                                                            Analyst Notes
                                                                                                        </h4>
                                                                                                        <p class="text-sm text-blue-900 leading-relaxed whitespace-pre-line">{{ $test->result_notes }}</p>
                                                                                                    </div>
                                                                                                @endif

                                                                                                {{-- All Tests in Submission --}}
                                                                                                @php
                                                                                                    $allTests = $test->sample->submission->samples->flatMap->sampleTests->whereIn('status', ['completed', 'flagged']);
                                                                                                @endphp
                                                                                                @if($allTests->count() > 1)
                                                                                                    <div class="bg-white rounded-xl border-2 border-gray-200 overflow-hidden">
                                                                                                        <div class="px-5 py-3 border-b border-gray-200 bg-gray-50">
                                                                                                            <h4 class="text-sm font-bold text-gray-800 uppercase flex items-center gap-2">
                                                                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                                                                                                </svg>
                                                                                                                All Completed Tests ({{ $allTests->count() }})
                                                                                                            </h4>
                                                                                                        </div>
                                                                                                        <table class="w-full text-sm">
                                                                                                            <thead class="bg-gray-50 text-xs">
                                                                                                                <tr>
                                                                                                                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Test</th>
                                                                                                                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Category</th>
                                                                                                                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Result</th>
                                                                                                                    <th class="text-left px-4 py-3 font-semibold text-gray-600">Qualifier</th>
                                                                                                                </tr>
                                                                                                            </thead>
                                                                                                            <tbody class="divide-y divide-gray-100">
                                                                                                                @foreach($allTests as $t)
                                                                                                                    @php
                                                                                                                        $isCurrent = $t->id === $test->id;
                                                                                                                        $tQualColor = match($t->result_qualifier) {
                                                                                                                            'pass', 'not_detected' => 'bg-green-100 text-green-800 border-green-200',
                                                                                                                            'fail', 'detected' => 'bg-red-100 text-red-800 border-red-200',
                                                                                                                            default => 'bg-gray-100 text-gray-700 border-gray-200'
                                                                                                                        };
                                                                                                                    @endphp
                                                                                                                    <tr class="{{ $isCurrent ? 'bg-indigo-50 border-l-4 border-l-indigo-500' : 'hover:bg-gray-50' }}">
                                                                                                                        <td class="px-4 py-3 {{ $isCurrent ? 'font-bold text-indigo-900' : 'font-medium text-gray-800' }}">
                                                                                                                            {{ $t->getDisplayLabel() }}
                                                                                                                            @if($isCurrent)
                                                                                                                                <span class="ml-2 text-xs text-indigo-600">(viewing)</span>
                                                                                                                            @endif
                                                                                                                        </td>
                                                                                                                        <td class="px-4 py-3 text-gray-600 text-xs capitalize">{{ $t->getDisplayCategory() }}</td>
                                                                                                                        <td class="px-4 py-3 text-gray-800 font-medium">
                                                                                                                            {{ $t->result_value ?? '—' }}
                                                                                                                            @if($t->result_unit)
                                                                                                                                <span class="text-gray-500 text-xs ml-1">{{ $t->result_unit }}</span>
                                                                                                                            @endif
                                                                                                                        </td>
                                                                                                                        <td class="px-4 py-3">
                                                                                                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full capitalize border {{ $tQualColor }}">
                                                                                                                                {{ str_replace('_', ' ', $t->result_qualifier ?? '—') }}
                                                                                                                            </span>
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

                                                                                    {{-- Footer Actions --}}
                                                                                    <div class="bg-gray-50 px-6 py-4 border-t-2 border-gray-200 flex justify-between items-center sticky bottom-0">
                                                                                        <button type="button"
                                                                                                @click="showModal = false"
                                                                                                class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition">
                                                                                            Close
                                                                                        </button>
                                                                                        <a href="{{ route('analyst.tests.show', $test->id) }}"
                                                                                           class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-sm hover:shadow transition">
                                                                                            View Full Test Details →
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    @if(!$test->result_qualifier || $test->result_qualifier === 'pending')
                                                                        <span class="text-gray-400">—</span>
                                                                    @endif
                                                                </div>
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

        </div>
    </div>
</x-app-layout>