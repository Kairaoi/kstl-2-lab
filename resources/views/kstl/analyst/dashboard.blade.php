{{-- resources/views/kstl/analyst/dashboard.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Analyst Dashboard</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ now()->format('l, d F Y') }}</p>
            </div>
            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 px-3 py-1.5 rounded-full">
                <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-pulse"></span>
                Analyst
            </span>
        </div>
    </x-slot>

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

            {{-- ── Summary Cards ─────────────────────────────────── --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl border border-gray-100 p-4">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Queued</span>
                        <div class="w-7 h-7 rounded-lg bg-yellow-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-medium text-gray-900">{{ $counts['queued'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Awaiting analyst</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 p-4">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">In Progress</span>
                        <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-medium text-gray-900">{{ $counts['in_progress'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Currently running</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 p-4">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Completed</span>
                        <div class="w-7 h-7 rounded-lg bg-green-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-medium text-gray-900">{{ $counts['completed'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Results entered</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 p-4">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Flagged</span>
                        <div class="w-7 h-7 rounded-lg bg-red-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-medium text-gray-900">{{ $counts['flagged'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Needs review</p>
                </div>
            </div>

            {{-- ── Active Submissions (Grouped) ──────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-800">Active Submissions</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Grouped by submission — your tests in progress</p>
                    </div>
                    <a href="{{ route('analyst.tests.index') }}"
                       class="text-xs text-blue-600 hover:underline">View all tests →</a>
                </div>

                @if($activeSubmissions->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-medium text-gray-400">No active tests</p>
                        <p class="text-xs text-gray-300 mt-1">All tests have been completed.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($activeSubmissions as $group)
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
                                                @if($flagged > 0)
                                                    <span class="inline-flex px-2 py-0.5 text-xs bg-orange-50 text-orange-700 rounded-full font-medium">
                                                        {{ $flagged }} flagged
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
                                                    @php
                                                        $tColors = [
                                                            'queued'      => 'bg-yellow-50 text-yellow-700',
                                                            'in_progress' => 'bg-blue-50 text-blue-700',
                                                            'completed'   => 'bg-green-50 text-green-700',
                                                            'flagged'     => 'bg-orange-50 text-orange-700',
                                                        ];
                                                        $tColor = $tColors[$test->status] ?? 'bg-gray-100 text-gray-500';
                                                    @endphp
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
                                                            <span class="inline-flex px-1.5 py-0.5 text-xs rounded-full capitalize {{ $tColor }}">
                                                                {{ ucfirst(str_replace('_', ' ', $test->status)) }}
                                                            </span>
                                                        </td>
                                                        <td class="px-4 py-2.5 text-gray-500">
                                                            {{ $test->result_value ?? '—' }}
                                                            @if($test->result_unit) <span class="text-gray-400">{{ $test->result_unit }}</span> @endif
                                                        </td>
                                                        <td class="px-4 py-2.5 text-right">
                                                            <a href="{{ route('analyst.tests.show', $test->id) }}"
                                                               class="px-2.5 py-1 text-xs font-medium rounded-md transition
                                                               {{ $test->status === 'completed' ? 'bg-gray-100 text-gray-600 hover:bg-gray-200' : 'bg-indigo-600 text-white hover:bg-indigo-700' }}">
                                                                {{ $test->status === 'completed' ? 'View' : ($test->status === 'in_progress' ? 'Continue' : 'Start') }}
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
            <div class="mt-2 bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-800">Testing History</h3>
                        <p class="text-xs text-gray-400 mt-0.5">All submissions in testing, awaiting authorisation, authorised or completed — last 20</p>
                    </div>
                    <span class="text-xs text-gray-400 bg-gray-50 px-3 py-1 rounded-full border border-gray-100">
                        {{ $history->count() }} records
                    </span>
                </div>

                @if($history->isEmpty())
                    <div class="px-6 py-10 text-center">
                        <p class="text-sm text-gray-400">No completed submissions yet.</p>
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
                                        $hColors = [
                                            'testing'                => 'bg-blue-50 text-blue-700',
                                            'awaiting_authorisation' => 'bg-purple-50 text-purple-700',
                                            'authorised'             => 'bg-green-50 text-green-700',
                                            'completed'              => 'bg-green-100 text-green-800',
                                        ];
                                        $hColor = $hColors[$submission->status] ?? 'bg-gray-100 text-gray-500';
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
                                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full capitalize {{ $hColor }}">
                                                {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                                            </span>
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