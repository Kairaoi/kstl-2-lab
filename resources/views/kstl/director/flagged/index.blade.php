{{-- resources/views/kstl/director/flagged/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('director.dashboard') }}"
               class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <p class="fl-eyebrow">Director &middot; Review Queue</p>
                <h2 class="fl-title text-xl font-bold leading-tight mt-0.5">Flagged Tests</h2>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .fl-eyebrow { letter-spacing: .16em; text-transform: uppercase; font-size: 10px; color: var(--gold); font-weight: 700; }
        .fl-title { font-family: 'Noto Serif', serif; color: var(--navy); letter-spacing: .01em; }
        .fl-section-title {
            font-family: 'Noto Serif', serif; color: var(--navy);
            font-size: 14px; font-weight: 700; letter-spacing: .02em;
        }
        .fl-meta-label { letter-spacing: .07em; text-transform: uppercase; font-size: 10px; color: var(--subtle); font-weight: 600; }
    </style>
    @endpush

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Intro --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">
                            Tests flagged for Director review — either raised by an analyst or returned by a previous query.
                        </p>
                    </div>
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-red-50 text-red-700 rounded-full text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2z"/>
                        </svg>
                        {{ $flaggedTests->count() }} flagged
                    </span>
                </div>
            </div>

            @if($flaggedTests->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
                    <svg class="w-14 h-14 text-green-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-600 font-medium">No flagged tests</p>
                    <p class="text-gray-400 text-sm mt-1">Nothing currently needs the Director's attention.</p>
                </div>
            @else
                @foreach($grouped as $submissionId => $tests)
                    @php $submission = $tests->first()->sample->submission ?? null; @endphp
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                        {{-- Submission header --}}
                        <div class="px-6 py-4 border-b border-gray-100 flex items-start justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-sm font-semibold text-indigo-600">
                                        {{ $submission->reference_number ?? '—' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                        {{ $tests->count() }} flagged
                                    </span>
                                </div>
                                <p class="text-sm text-gray-700 mt-1">
                                    {{ $submission->client->company_name ?? '—' }}
                                </p>
                                @if($submission && $submission->client && $submission->client->user)
                                    <p class="text-xs text-gray-400">
                                        {{ $submission->client->user->name ?? trim(($submission->client->user->first_name ?? '') . ' ' . ($submission->client->user->last_name ?? '')) }}
                                    </p>
                                @endif
                            </div>
                            @if($submission)
                                <a href="{{ route('director.submissions.show', $submission->id) }}"
                                   class="shrink-0 inline-flex items-center gap-1 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                                    Review
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif
                        </div>

                        {{-- Flagged tests in this submission --}}
                        <ul class="divide-y divide-gray-50">
                            @foreach($tests as $test)
                                <li class="px-6 py-4">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <p class="font-medium text-gray-800 text-sm">{{ $test->getDisplayLabel() }}</p>
                                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1 text-xs text-gray-500">
                                                <span class="inline-flex px-1.5 py-0.5 rounded capitalize
                                                    {{ $test->getDisplayCategory() === 'microbiological' ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600' }}">
                                                    {{ $test->getDisplayCategory() }}
                                                </span>
                                                @if($test->result_value)
                                                    <span><span class="fl-meta-label">Value:</span>
                                                        <span class="font-mono text-gray-700">{{ $test->result_value }}</span>
                                                        {{ $test->result_unit }}
                                                    </span>
                                                @endif
                                                @if($test->result_qualifier && $test->result_qualifier !== 'pending')
                                                    <span><span class="fl-meta-label">Result:</span>
                                                        <span class="capitalize text-gray-700">{{ str_replace('_', ' ', $test->result_qualifier) }}</span>
                                                    </span>
                                                @endif
                                                <span><span class="fl-meta-label">Analyst:</span>
                                                    {{ $test->assignedTo?->name ?? trim(($test->assignedTo->first_name ?? '') . ' ' . ($test->assignedTo->last_name ?? '')) ?: '—' }}
                                                </span>
                                            </div>

                                            @if($test->result_notes)
                                                <p class="text-xs text-gray-600 mt-2 bg-amber-50 border border-amber-100 rounded p-2 whitespace-pre-line">{{ $test->result_notes }}</p>
                                            @endif

                                            {{-- Supporting documents --}}
                                            @if($test->attachments->isNotEmpty())
                                                <div class="mt-2">
                                                    <p class="fl-meta-label mb-1">Supporting documents ({{ $test->attachments->count() }})</p>
                                                    <ul class="space-y-0.5">
                                                        @foreach($test->attachments as $attachment)
                                                            <li>
                                                                <a href="{{ route('director.attachments.download', $attachment->id) }}"
                                                                   class="text-xs font-medium text-indigo-600 hover:text-indigo-800 hover:underline inline-flex items-center gap-1">
                                                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                                    </svg>
                                                                    {{ $attachment->original_filename }}
                                                                </a>
                                                                <span class="text-xs text-gray-400">({{ $attachment->human_size }})</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>

                                        <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 text-xs bg-red-50 text-red-700 rounded-full font-medium">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2z"/>
                                            </svg>
                                            Flagged
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            @endif

        </div>
    </div>

</x-app-layout>