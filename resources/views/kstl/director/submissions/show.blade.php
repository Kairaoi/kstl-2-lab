{{-- resources/views/kstl/director/submissions/show.blade.php --}}
{{-- Director review page: test results + file preview modal + per-test notes + text highlight --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('director.dashboard') }}"
                   class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Review Results — {{ $submission->reference_number }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ $submission->client->company_name }} · {{ $samples->count() }} sample{{ $samples->count() !== 1 ? 's' : '' }}
                    </p>
                </div>
            </div>
            @if($existingResult)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20">
                    Authorised
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20">
                    Awaiting Authorisation
                </span>
            @endif
        </div>
    </x-slot>

    @push('styles')
    <style>
        /* ── File preview modal ───────────────────────── */
        .preview-modal-backdrop { backdrop-filter: blur(4px); }

        /* ── Text highlight colours ───────────────────── */
        .hl-yellow { background: #fef08a; border-radius: 2px; padding: 0 1px; }
        .hl-green  { background: #bbf7d0; border-radius: 2px; padding: 0 1px; }
        .hl-blue   { background: #bfdbfe; border-radius: 2px; padding: 0 1px; }
        .hl-pink   { background: #fbcfe8; border-radius: 2px; padding: 0 1px; }

        /* Floating highlight toolbar */
        #hl-toolbar {
            position: fixed;
            z-index: 9999;
            display: none;
            gap: 4px;
            background: #1e293b;
            border-radius: 8px;
            padding: 5px 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,.35);
            align-items: center;
        }
        #hl-toolbar button {
            width: 20px; height: 20px; border-radius: 4px; border: 2px solid rgba(255,255,255,.2); cursor: pointer;
        }
        #hl-toolbar .hl-tb-label { font-size: 11px; color: #94a3b8; white-space: nowrap; }
        #hl-toolbar .hl-tb-clear { font-size: 11px; color: #f87171; cursor: pointer; background: none; border: none; padding: 0 2px; }

        /* Highlightable cells */
        .highlightable { cursor: text; }
    </style>
    @endpush

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6"
             x-data="{
                selectedTests: [],
                queryMode: false,
                previewOpen: false,
                previewUrl: '',
                previewName: '',
                previewMime: '',
                previewAttachmentId: '',
                previewNoteSaveUrl: '',
                previewNote: '',
                previewNoteSaved: false,
                previewNoteSaving: false,
                openPreview(url, name, mime, attachmentId, existingNote, saveUrl) {
                    this.previewUrl          = url;
                    this.previewName         = name;
                    this.previewMime         = mime;
                    this.previewAttachmentId = attachmentId;
                    this.previewNoteSaveUrl  = saveUrl || '';
                    this.previewNote         = existingNote || '';
                    this.previewNoteSaved    = false;
                    this.previewOpen         = true;
                },
                closePreview() {
                    this.previewOpen = false;
                    this.previewUrl  = '';
                },
                async savePreviewNote() {
                    if (!this.previewNoteSaveUrl) return;
                    this.previewNoteSaving = true;
                    await fetch(this.previewNoteSaveUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ director_note: this.previewNote }),
                    });
                    this.previewNoteSaving = false;
                    this.previewNoteSaved  = true;
                    setTimeout(() => this.previewNoteSaved = false, 2500);
                }
             }">

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            {{-- ── Already authorised notice ─────────────────────── --}}
            @if($existingResult)
                <div class="bg-green-50 border border-green-200 rounded-xl p-5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-green-800">
                                Authorised — {{ ucfirst($existingResult->overall_outcome) }}
                            </p>
                            <p class="text-xs text-green-600 mt-0.5">
                                By {{ $existingResult->authorisedBy?->name }} on {{ $existingResult->authorised_at?->format('d M Y \a\t H:i') }}
                                &middot; All {{ $samples->sum(fn($s) => ($testsBySample[$s->id] ?? collect())->count()) }} test result{{ $samples->sum(fn($s) => ($testsBySample[$s->id] ?? collect())->count()) !== 1 ? 's' : '' }} are shown below.
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('director.results.show', $submission->id) }}"
                       class="shrink-0 inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                        View Full Report →
                    </a>
                </div>
            @endif

            {{-- ── Submission summary strip ─────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Client</p>
                        <p class="font-medium text-gray-800 mt-0.5">{{ $submission->client->company_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Sample</p>
                        <p class="font-medium text-gray-800 mt-0.5">{{ $submission->sample_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Priority</p>
                        <p class="mt-0.5">
                            @php $pc = ['routine' => 'bg-gray-100 text-gray-600', 'urgent' => 'bg-amber-50 text-amber-700', 'emergency' => 'bg-red-50 text-red-700']; @endphp
                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full capitalize {{ $pc[$submission->priority ?? 'routine'] }}">
                                {{ $submission->priority ?? 'Routine' }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide">Results Required By</p>
                        <p class="font-medium text-gray-800 mt-0.5">
                            {{ $submission->results_required_by?->format('d M Y') ?? 'No deadline' }}
                        </p>
                    </div>
                </div>
            </div>

            @php
                $totalTestCount      = 0;
                $pendingDirectorCount = 0;
                foreach ($samples as $s) {
                    foreach (($testsBySample[$s->id] ?? collect()) as $t) {
                        $totalTestCount++;
                        if (! $t->director_outcome) $pendingDirectorCount++;
                    }
                }
                $authorisedCount = $totalTestCount - $pendingDirectorCount;
            @endphp

            {{-- ── Auth form wraps the test tables so outcome selects are submitted ── --}}
            @if(!$existingResult)
            <form id="auth-form"
                  method="POST"
                  action="{{ route('director.submissions.authorise-tests', $submission->id) }}">
                @csrf
            @endif

            {{-- ── Test Results per Sample ──────────────────────── --}}
            @foreach($samples as $sample)
                @php $tests = $testsBySample[$sample->id] ?? collect(); @endphp

                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">

                    {{-- Sample header --}}
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-900">
                                {{ $sample->sample_code }} — {{ $sample->common_name }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $sample->quantity }} {{ $sample->quantity_unit }}
                                @if($sample->scientific_name) · <em>{{ $sample->scientific_name }}</em> @endif
                            </p>
                        </div>
                        @if(!$existingResult)
                            @php
                                $pendingThis = collect($testsBySample[$sample->id] ?? [])->filter(fn($t) => !$t->director_outcome)->count();
                                $doneThis    = collect($testsBySample[$sample->id] ?? [])->filter(fn($t) =>  $t->director_outcome)->count();
                            @endphp
                            @if($pendingThis > 0)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs bg-amber-50 text-amber-700 rounded-full font-medium">
                                    {{ $pendingThis }} pending
                                </span>
                            @else
                                <span class="inline-flex px-2.5 py-1 text-xs bg-green-50 text-green-700 rounded-full">
                                    All authorised
                                </span>
                            @endif
                        @else
                            @php $flaggedCount = $tests->where('status', 'flagged')->count(); @endphp
                            @if($flaggedCount)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs bg-red-50 text-red-700 rounded-full font-medium">
                                    ⚑ {{ $flaggedCount }} flagged
                                </span>
                            @else
                                <span class="inline-flex px-2.5 py-1 text-xs bg-green-50 text-green-700 rounded-full">
                                    All complete
                                </span>
                            @endif
                        @endif
                    </div>

                    {{-- Tests table --}}
                    @if($tests->isEmpty())
                        <div class="px-6 py-6 text-sm text-gray-400">No tests recorded for this sample.</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr>
                                        @if(!$existingResult)
                                            <th class="px-4 py-3 w-10">
                                                <span class="sr-only">Select for query</span>
                                            </th>
                                        @endif
                                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">Test</th>
                                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">Result</th>
                                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">Value</th>
                                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">Notes</th>
                                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">Analyst</th>
                                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">
                                            Director Outcome
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($tests as $test)
                                        @php
                                            $isDone = (bool) $test->director_outcome;
                                            $rowBg  = $isDone ? 'bg-green-50/20' : ($test->status === 'flagged' ? 'bg-red-50/40' : '');
                                        @endphp
                                        <tr class="{{ $rowBg }} hover:bg-gray-50/50 transition">

                                            {{-- Select checkbox (for query analyst) --}}
                                            @if(!$existingResult)
                                                <td class="px-4 py-3 text-center">
                                                    @if(!$isDone)
                                                        <input type="checkbox"
                                                               :value="'{{ $test->id }}'"
                                                               x-model="selectedTests"
                                                               class="rounded text-amber-600 focus:ring-amber-500">
                                                    @else
                                                        <svg class="w-4 h-4 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                                                        </svg>
                                                    @endif
                                                </td>
                                            @endif

                                            {{-- Test name --}}
                                            <td class="px-4 py-3">
                                                <p class="font-medium text-gray-800 text-xs">{{ $test->getDisplayLabel() }}</p>
                                                <span class="inline-flex px-1.5 py-0.5 text-xs rounded capitalize mt-0.5
                                                    {{ $test->getDisplayCategory() === 'microbiological' ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600' }}">
                                                    {{ $test->getDisplayCategory() }}
                                                </span>
                                            </td>

                                            {{-- Analyst result qualifier --}}
                                            <td class="px-4 py-3">
                                                @php
                                                    $qc = [
                                                        'pass'         => 'bg-green-50 text-green-700',
                                                        'fail'         => 'bg-red-50 text-red-700',
                                                        'detected'     => 'bg-orange-50 text-orange-700',
                                                        'not_detected' => 'bg-green-50 text-green-700',
                                                        'less_than'    => 'bg-blue-50 text-blue-700',
                                                        'greater_than' => 'bg-blue-50 text-blue-700',
                                                        'equal_to'     => 'bg-blue-50 text-blue-700',
                                                        'pending'      => 'bg-gray-100 text-gray-500',
                                                    ];
                                                    $ql = [
                                                        'pass'         => 'Pass',
                                                        'fail'         => 'Fail',
                                                        'detected'     => 'Detected',
                                                        'not_detected' => 'Not Detected',
                                                        'less_than'    => '< Less Than',
                                                        'greater_than' => '> Greater Than',
                                                        'equal_to'     => '= Equal To',
                                                        'pending'      => 'Pending',
                                                    ];
                                                @endphp
                                                <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full {{ $qc[$test->result_qualifier] ?? 'bg-gray-100 text-gray-500' }}">
                                                    {{ $ql[$test->result_qualifier] ?? ucfirst($test->result_qualifier) }}
                                                </span>
                                            </td>

                                            {{-- Value (highlightable) --}}
                                            <td class="px-4 py-3 text-sm text-gray-700 highlightable"
                                                data-hl-cell="test-{{ $test->id }}-value">
                                                @if($test->result_value)
                                                    <span class="font-mono">{{ $test->result_value }}</span>
                                                    @if($test->result_unit)
                                                        <span class="text-xs text-gray-400">{{ $test->result_unit }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-gray-400">—</span>
                                                @endif
                                            </td>

                                            {{-- Notes (highlightable) --}}
                                            <td class="px-4 py-3 text-xs text-gray-500 max-w-xs highlightable"
                                                data-hl-cell="test-{{ $test->id }}-notes">
                                                {{ $test->result_notes ? \Illuminate\Support\Str::limit($test->result_notes, 80) : '—' }}
                                            </td>

                                            {{-- Analyst --}}
                                            <td class="px-4 py-3 text-xs text-gray-500">
                                                {{ $test->assignedTo?->name ?? '—' }}
                                            </td>

                                            {{-- Director outcome: dropdown (pending) or badge (done) --}}
                                            <td class="px-4 py-3">
                                                @if($isDone)
                                                    @php
                                                        $dc = [
                                                            'pass'         => 'bg-green-50 text-green-700 ring-green-600/20',
                                                            'fail'         => 'bg-red-50 text-red-700 ring-red-600/20',
                                                            'inconclusive' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                                        ];
                                                    @endphp
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ring-1 ring-inset {{ $dc[$test->director_outcome] ?? 'bg-gray-100 text-gray-500' }}">
                                                        {{ ucfirst($test->director_outcome) }}
                                                    </span>
                                                @elseif(!$existingResult)
                                                    <select name="outcomes[{{ $test->id }}]"
                                                            x-data="{ v: '' }"
                                                            x-model="v"
                                                            :class="{
                                                                'border-green-300 bg-green-50 text-green-800': v === 'pass',
                                                                'border-red-300 bg-red-50 text-red-800': v === 'fail',
                                                                'border-amber-300 bg-amber-50 text-amber-800': v === 'inconclusive',
                                                                'border-gray-200 text-gray-400': !v
                                                            }"
                                                            class="text-xs rounded-lg px-2 py-1.5 border focus:outline-none focus:ring-1 focus:ring-teal-400 transition w-36">
                                                        <option value="">— decide later —</option>
                                                        <option value="pass">Pass</option>
                                                        <option value="fail">Fail</option>
                                                        <option value="inconclusive">Inconclusive</option>
                                                    </select>
                                                @else
                                                    <span class="text-gray-400 text-xs">—</span>
                                                @endif
                                            </td>

                                        </tr>

                                        {{-- Supporting documents + per-test director note --}}
                                        @php $colspan = $existingResult ? 6 : 7; @endphp

                                        @if($test->attachments->isNotEmpty())
                                            <tr class="{{ $test->status === 'flagged' ? 'bg-red-50/20' : 'bg-gray-50/40' }}">
                                                <td colspan="{{ $colspan }}" class="px-4 pb-3 pt-0">
                                                    <div class="ml-1 rounded-lg border border-gray-100 bg-white px-3 py-2.5">
                                                        <p class="text-xs font-medium text-gray-500 mb-2 flex items-center gap-1.5">
                                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                            </svg>
                                                            Supporting documents ({{ $test->attachments->count() }})
                                                        </p>
                                                        <ul class="space-y-2">
                                                            @foreach($test->attachments as $attachment)
                                                                @php
                                                                    $isImage = str_starts_with($attachment->mime_type ?? '', 'image/');
                                                                    $isPdf   = ($attachment->mime_type ?? '') === 'application/pdf';
                                                                    $canPreview = $isImage || $isPdf;
                                                                @endphp
                                                                <li class="flex items-center gap-2 flex-wrap">
                                                                    {{-- File icon --}}
                                                                    <svg class="w-4 h-4 shrink-0 {{ $isPdf ? 'text-red-400' : ($isImage ? 'text-blue-400' : 'text-gray-400') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        @if($isPdf)
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                                        @elseif($isImage)
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                                        @else
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                                                        @endif
                                                                    </svg>

                                                                    <span class="text-xs font-medium text-gray-700">{{ $attachment->original_filename }}</span>
                                                                    <span class="text-xs text-gray-400">({{ $attachment->human_size }}
                                                                        @if($attachment->uploadedBy)
                                                                            &middot; {{ $attachment->uploadedBy->name ?? trim(($attachment->uploadedBy->first_name ?? '') . ' ' . ($attachment->uploadedBy->last_name ?? '')) }}
                                                                        @endif
                                                                    )</span>
                                                                    @if($attachment->description)
                                                                        <span class="text-xs text-gray-500 italic">— {{ $attachment->description }}</span>
                                                                    @endif

                                                                    <div class="ml-auto flex items-center gap-1.5">
                                                                        @if($canPreview)
                                                                            <button type="button"
                                                                                    @click="openPreview(
                                                                                        '{{ route('director.attachments.preview', $attachment->id) }}',
                                                                                        '{{ addslashes($attachment->original_filename) }}',
                                                                                        '{{ $attachment->mime_type }}',
                                                                                        '{{ $attachment->id }}',
                                                                                        {{ json_encode($attachment->director_note ?? '') }},
                                                                                        '{{ route('director.attachments.note', $attachment->id) }}'
                                                                                    )"
                                                                                    class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition">
                                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                                                Preview
                                                                                @if($attachment->director_note)
                                                                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 inline-block"></span>
                                                                                @endif
                                                                            </button>
                                                                        @endif
                                                                        <a href="{{ route('director.attachments.download', $attachment->id) }}"
                                                                           class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded bg-gray-100 text-gray-600 hover:bg-gray-200 transition">
                                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                                            Download
                                                                        </a>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif

                                        {{-- Per-test director note (collapsible) --}}
                                        <tr x-data="{
                                                open: {{ $test->director_review_note ? 'true' : 'false' }},
                                                note: {{ json_encode($test->director_review_note ?? '') }},
                                                saved: false,
                                                saving: false,
                                                async saveNote() {
                                                    this.saving = true;
                                                    await fetch('{{ route('director.tests.note', $test->id) }}', {
                                                        method: 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                                            'Accept': 'application/json',
                                                        },
                                                        body: JSON.stringify({ director_review_note: this.note }),
                                                    });
                                                    this.saving = false;
                                                    this.saved  = true;
                                                    setTimeout(() => this.saved = false, 2500);
                                                }
                                            }"
                                             class="bg-gray-50/30 border-t border-dashed border-gray-100">
                                            <td colspan="{{ $colspan }}" class="px-4 py-1.5">
                                                <div class="flex items-center gap-2">
                                                    <button type="button" @click="open = !open"
                                                            class="inline-flex items-center gap-1.5 text-xs text-gray-400 hover:text-indigo-600 transition">
                                                        <svg class="w-3.5 h-3.5 transition" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                        </svg>
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                                        <span x-text="open ? 'Hide note' : (note ? 'View note' : 'Add director note')"></span>
                                                        <span x-show="note && !open" class="w-2 h-2 rounded-full bg-indigo-400 inline-block"></span>
                                                    </button>
                                                    <span x-show="saved" x-cloak class="text-xs text-green-600 font-medium">Saved ✓</span>
                                                </div>
                                                <div x-show="open" x-cloak class="mt-2 space-y-2">
                                                    <textarea x-model="note" rows="2"
                                                              class="w-full text-xs border-gray-200 rounded-lg focus:border-indigo-400 focus:ring-indigo-400 resize-none"
                                                              placeholder="Director's internal review note for this test (not shown to client)..."></textarea>
                                                    <div class="flex items-center justify-end gap-2">
                                                        <button type="button" @click="open = false" class="text-xs text-gray-400 hover:text-gray-600">Cancel</button>
                                                        <button type="button" @click="saveNote()"
                                                                :disabled="saving"
                                                                class="px-3 py-1 text-xs font-medium rounded-md bg-indigo-600 text-white hover:bg-indigo-700 disabled:opacity-50 transition">
                                                            <span x-show="!saving">Save note</span>
                                                            <span x-show="saving" x-cloak>Saving…</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endforeach

            {{-- Close auth form (test tables + select inputs are now inside it) --}}
            @if(!$existingResult)
            </form>
            @endif

            {{-- ── Bottom panels ──────────────────────────────────── --}}
            @if(!$existingResult)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                    {{-- Authorise panel (submit button uses form="auth-form" to associate externally) --}}
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden"
                         x-show="!queryMode">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-medium text-gray-800">Authorise Tests</h3>
                                @if($authorisedCount > 0)
                                    <span class="text-xs text-green-600 font-medium">
                                        {{ $authorisedCount }} of {{ $totalTestCount }} authorised
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Set an outcome in the table above for each test you want to authorise now.
                                Tests left on "decide later" stay pending for your next visit.
                            </p>
                        </div>
                        <div class="px-6 py-5">
                            <label for="director_comments" class="block text-sm font-medium text-gray-700 mb-1">
                                Director Comments <span class="text-gray-400 font-normal">(optional)</span>
                            </label>
                            <textarea id="director_comments"
                                      name="director_comments"
                                      form="auth-form"
                                      rows="3"
                                      class="w-full border-gray-300 rounded-lg text-sm focus:border-teal-500 focus:ring-teal-500"
                                      placeholder="Comments to include in the final report..."></textarea>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                            <button type="button"
                                    @click="queryMode = true"
                                    x-show="selectedTests.length > 0"
                                    x-cloak
                                    class="text-sm text-amber-600 hover:text-amber-800 font-medium">
                                Query analyst about selected →
                            </button>
                            <p class="text-xs text-gray-400"
                               x-show="selectedTests.length === 0">
                                Tick rows above to query the analyst
                            </p>
                            <button type="submit"
                                    form="auth-form"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium rounded-lg transition bg-teal-600 hover:bg-teal-700 text-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                Authorise
                            </button>
                        </div>
                    </div>

                    {{-- Query Analyst form --}}
                    <div class="bg-white rounded-xl border border-amber-200 overflow-hidden"
                         x-show="queryMode"
                         x-cloak>
                        <div class="px-6 py-4 border-b border-amber-100 bg-amber-50">
                            <h3 class="text-sm font-medium text-amber-800">Query Analyst</h3>
                            <p class="text-xs text-amber-600 mt-0.5">
                                <span x-text="selectedTests.length"></span> test(s) selected. Describe your concern.
                            </p>
                        </div>
                        <form method="POST"
                              action="{{ route('director.submissions.query', $submission->id) }}">
                            @csrf
                            <template x-for="testId in selectedTests" :key="testId">
                                <input type="hidden" name="test_ids[]" :value="testId">
                            </template>
                            <div class="px-6 py-5 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Query / Concern <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="query_notes" rows="4" required
                                              class="w-full border-amber-300 rounded-lg text-sm focus:border-amber-500 focus:ring-amber-500"
                                              placeholder="Describe what needs clarification from the analyst..."></textarea>
                                </div>
                            </div>
                            <div class="px-6 py-4 border-t border-amber-100 bg-amber-50 flex items-center justify-between">
                                <button type="button"
                                        @click="queryMode = false"
                                        class="text-sm text-gray-500 hover:text-gray-700">
                                    ← Back to authorise
                                </button>
                                <button type="submit"
                                        x-bind:disabled="selectedTests.length === 0"
                                        x-bind:class="selectedTests.length === 0 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-amber-600 hover:bg-amber-700 text-white'"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium rounded-lg transition">
                                    Send Query to Analyst
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            @endif

            {{-- ── Internal Report Link ─────────────────────────────── --}}
            @if($existingResult)
                <div class="bg-white rounded-xl border border-gray-100 p-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Internal Report</p>
                        <p class="text-xs text-gray-400 mt-0.5">Full result report with determination and analyst details — Director use only.</p>
                    </div>
                    <a href="{{ route('director.results.show', $submission->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                        View Internal Report →
                    </a>
                </div>
            @endif

            {{-- ── Generate Invoice (if authorised) ──────────────── --}}
            @if($existingResult && $submission->status === 'authorised')
                @php $existingInvoice = $submission->invoice ?? null; @endphp
                <div class="bg-white rounded-xl border border-gray-100 p-5 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Invoice</p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            @if($existingInvoice)
                                Invoice {{ $existingInvoice->invoice_number }} already generated.
                            @else
                                Generate invoice from test results for this submission.
                            @endif
                        </p>
                    </div>
                    @if($existingInvoice)
                        <a href="{{ route('director.invoices.show', $existingInvoice->id) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                            View Invoice →
                        </a>
                    @else
                        <form method="POST"
                              action="{{ route('director.invoices.generate', $submission->id) }}"
                              onsubmit="return confirm('Generate invoice for this submission?')">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                                </svg>
                                Generate Invoice
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            <div class="pb-8"></div>

            {{-- ════════════════════════════════════════════════════════
                 File Preview Modal  (must be INSIDE the x-data scope)
            ════════════════════════════════════════════════════════ --}}
            <div x-show="previewOpen"
                 x-cloak
                 class="preview-modal-backdrop fixed inset-0 z-50 flex flex-col bg-black/60"
                 @keydown.escape.window="closePreview()">

                {{-- Header bar --}}
                <div class="flex items-center justify-between px-5 py-3 bg-gray-900 text-white shrink-0">
                    <div class="flex items-center gap-3 min-w-0">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-sm font-medium truncate" x-text="previewName"></span>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <a :href="previewUrl.replace('/preview', '/download')"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-md bg-gray-700 text-gray-200 hover:bg-gray-600 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download
                        </a>
                        <button @click="closePreview()"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-white hover:bg-gray-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Full-width file viewer with floating comment overlay --}}
                <div class="flex-1 overflow-hidden relative p-4"
                     x-data="{ commentOpen: false }">

                    {{-- File viewer — full width --}}
                    <template x-if="previewOpen && previewMime && previewMime.startsWith('image/')">
                        <div class="w-full h-full flex items-center justify-center">
                            <img :src="previewUrl" :alt="previewName"
                                 class="max-w-full max-h-full object-contain rounded shadow-lg">
                        </div>
                    </template>
                    <template x-if="previewOpen && previewMime === 'application/pdf'">
                        <iframe :src="previewUrl"
                                class="w-full h-full rounded bg-white"
                                frameborder="0"
                                title="PDF Preview"></iframe>
                    </template>
                    <template x-if="previewOpen && previewMime && !previewMime.startsWith('image/') && previewMime !== 'application/pdf'">
                        <div class="w-full h-full flex flex-col items-center justify-center gap-4 text-white">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-300 text-sm">Preview not available for this file type.</p>
                            <a :href="previewUrl.replace('/preview', '/download')"
                               class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
                                Download file
                            </a>
                        </div>
                    </template>

                    {{-- Toggle comment button (bottom-right, always visible) --}}
                    <button @click="commentOpen = !commentOpen"
                            class="absolute bottom-6 right-6 z-20 inline-flex items-center gap-2 px-3.5 py-2 rounded-xl shadow-xl text-sm font-medium transition"
                            :class="commentOpen
                                ? 'bg-indigo-600 text-white hover:bg-indigo-500'
                                : 'bg-gray-800 text-gray-200 hover:bg-gray-700 ring-1 ring-gray-600'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        <span x-text="commentOpen ? 'Hide comment' : 'Add comment'"></span>
                        {{-- dot if a note already exists --}}
                        <span x-show="!commentOpen && previewNote"
                              class="w-2 h-2 rounded-full bg-indigo-400 shrink-0"></span>
                    </button>

                    {{-- Floating comment panel (overlay, bottom-right) --}}
                    <div x-show="commentOpen"
                         x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-3"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-3"
                         class="absolute bottom-20 right-6 z-20 w-80 rounded-xl shadow-2xl overflow-hidden
                                flex flex-col"
                         style="max-height: 60vh; background:#1e293b; border:1px solid #334155;">

                        {{-- Panel header --}}
                        <div class="flex items-center justify-between px-4 py-2.5 border-b"
                             style="border-color:#334155;">
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                <span class="text-xs font-semibold" style="color:#e2e8f0;">Director's Comment</span>
                            </div>
                            <span class="text-xs" style="color:#64748b;">Internal only</span>
                        </div>

                        {{-- Textarea --}}
                        <div class="p-3 flex-1 flex flex-col gap-2">
                            <textarea
                                x-model="previewNote"
                                rows="7"
                                placeholder="Write your comment or observation about this document…"
                                class="w-full rounded-lg text-sm resize-none p-3 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                style="background:#0f172a; border:1px solid #334155; color:#f1f5f9; placeholder-color:#64748b;"
                            ></textarea>

                            {{-- Save row --}}
                            <div class="flex items-center justify-between">
                                <span x-show="previewNoteSaved" x-cloak
                                      class="text-xs font-medium flex items-center gap-1"
                                      style="color:#4ade80;">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Saved
                                </span>
                                <span x-show="!previewNoteSaved" style="color:#475569; font-size:11px;"></span>

                                <button @click="savePreviewNote()"
                                        :disabled="previewNoteSaving"
                                        class="px-3.5 py-1.5 text-xs font-semibold rounded-lg transition disabled:opacity-50"
                                        style="background:#4f46e5; color:#fff;">
                                    <span x-show="!previewNoteSaving">Save</span>
                                    <span x-show="previewNoteSaving" x-cloak>Saving…</span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════
         Text Highlight Toolbar (floating)
    ════════════════════════════════════════════════════════ --}}
    <div id="hl-toolbar" role="toolbar" aria-label="Highlight selected text">
        <span class="hl-tb-label">Highlight:</span>
        <button title="Yellow" style="background:#fef08a" onclick="applyHighlight('hl-yellow')"></button>
        <button title="Green"  style="background:#bbf7d0" onclick="applyHighlight('hl-green')"></button>
        <button title="Blue"   style="background:#bfdbfe" onclick="applyHighlight('hl-blue')"></button>
        <button title="Pink"   style="background:#fbcfe8" onclick="applyHighlight('hl-pink')"></button>
        <button class="hl-tb-clear" title="Remove highlight" onclick="removeHighlight()">✕</button>
    </div>

    @push('scripts')
    <script>
    (() => {
        const SUBMISSION_KEY = 'hl_{{ $submission->id }}';
        const toolbar = document.getElementById('hl-toolbar');
        let savedRange = null;

        // ── Restore highlights from localStorage ──────────────────
        function loadHighlights() {
            const data = JSON.parse(localStorage.getItem(SUBMISSION_KEY) || '[]');
            data.forEach(h => {
                // Find the element by its data-hl-cell attribute
                const cell = document.querySelector(`[data-hl-cell="${h.cell}"]`);
                if (!cell) return;
                const html = cell.innerHTML;
                // Replace exact text match with highlighted version
                const escaped = h.text.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                const regex = new RegExp(escaped, 'g');
                cell.innerHTML = html.replace(regex,
                    `<mark class="${h.color}" data-hl="${h.cell}">${h.text}</mark>`
                );
            });
        }

        // ── Save a highlight entry ─────────────────────────────────
        function saveHighlight(cellId, text, color) {
            const data = JSON.parse(localStorage.getItem(SUBMISSION_KEY) || '[]');
            // Avoid duplicates
            const exists = data.some(h => h.cell === cellId && h.text === text);
            if (!exists) {
                data.push({ cell: cellId, text, color });
                localStorage.setItem(SUBMISSION_KEY, JSON.stringify(data));
            }
        }

        // ── Remove a highlight entry ───────────────────────────────
        function removeHighlightEntry(cellId, text) {
            let data = JSON.parse(localStorage.getItem(SUBMISSION_KEY) || '[]');
            data = data.filter(h => !(h.cell === cellId && h.text === text));
            localStorage.setItem(SUBMISSION_KEY, JSON.stringify(data));
        }

        // ── Apply highlight to current selection ──────────────────
        window.applyHighlight = function(color) {
            if (!savedRange) return;
            const sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(savedRange);

            const text = savedRange.toString().trim();
            if (!text) return;

            // Find parent cell
            const container = savedRange.commonAncestorContainer;
            const cell = container.nodeType === 1
                ? container.closest('[data-hl-cell]')
                : container.parentElement?.closest('[data-hl-cell]');

            if (!cell) { hideToolbar(); return; }

            // Wrap with <mark>
            try {
                const mark = document.createElement('mark');
                mark.className = color;
                mark.dataset.hl = cell.dataset.hlCell;
                savedRange.surroundContents(mark);
                saveHighlight(cell.dataset.hlCell, text, color);
            } catch(e) {
                // surroundContents fails on partial tag spans — ignore
            }

            sel.removeAllRanges();
            hideToolbar();
        };

        // ── Remove highlight under cursor ──────────────────────────
        window.removeHighlight = function() {
            if (!savedRange) return;
            const container = savedRange.commonAncestorContainer;
            const mark = container.nodeType === 1
                ? container.closest('mark[data-hl]')
                : container.parentElement?.closest('mark[data-hl]');
            if (!mark) { hideToolbar(); return; }

            const text = mark.textContent;
            const cellId = mark.dataset.hl;
            // Unwrap the mark
            mark.replaceWith(document.createTextNode(text));
            removeHighlightEntry(cellId, text);
            hideToolbar();
        };

        function hideToolbar() {
            toolbar.style.display = 'none';
            savedRange = null;
        }

        // ── Show toolbar on text selection ─────────────────────────
        document.addEventListener('mouseup', (e) => {
            // Don't intercept toolbar button clicks
            if (toolbar.contains(e.target)) return;

            const sel = window.getSelection();
            if (!sel || sel.isCollapsed || !sel.toString().trim()) {
                setTimeout(() => {
                    const s2 = window.getSelection();
                    if (!s2 || s2.isCollapsed) hideToolbar();
                }, 100);
                return;
            }

            // Only show for highlightable cells
            const range = sel.getRangeAt(0);
            const container = range.commonAncestorContainer;
            const cell = container.nodeType === 1
                ? container.closest('.highlightable')
                : container.parentElement?.closest('.highlightable');

            if (!cell) return;

            savedRange = range.cloneRange();
            const rect = range.getBoundingClientRect();
            toolbar.style.display = 'flex';
            toolbar.style.left = Math.max(8, rect.left + rect.width / 2 - 100) + 'px';
            toolbar.style.top  = (rect.top + window.scrollY - 44) + 'px';
        });

        document.addEventListener('mousedown', (e) => {
            if (!toolbar.contains(e.target)) hideToolbar();
        });

        // Restore on load
        document.addEventListener('DOMContentLoaded', loadHighlights);
        // Also try immediately (if DOM already ready)
        if (document.readyState !== 'loading') loadHighlights();
    })();
    </script>
    @endpush

</x-app-layout>
