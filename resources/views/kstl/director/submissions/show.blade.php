{{-- resources/views/kstl/director/submissions/show.blade.php --}}

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
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20">
                Awaiting Authorisation
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6"
             x-data="{ selectedTests: [], queryMode: false }">

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            {{-- ── Already authorised notice ─────────────────────── --}}
            @if($existingResult)
                <div class="bg-green-50 border border-green-200 rounded-xl p-5 flex items-center gap-3">
                    <svg class="w-6 h-6 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-green-800">
                            Authorised — {{ ucfirst($existingResult->overall_outcome) }}
                        </p>
                        <p class="text-xs text-green-600 mt-0.5">
                            By {{ $existingResult->authorisedBy?->name }} on {{ $existingResult->authorised_at?->format('d M Y \a\t H:i') }}
                        </p>
                    </div>
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
                        @php
                            $flaggedCount = $tests->where('status', 'flagged')->count();
                        @endphp
                        @if($flaggedCount)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs bg-red-50 text-red-700 rounded-full font-medium">
                                ⚑ {{ $flaggedCount }} flagged
                            </span>
                        @else
                            <span class="inline-flex px-2.5 py-1 text-xs bg-green-50 text-green-700 rounded-full">
                                All complete
                            </span>
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
                                                <span class="sr-only">Select</span>
                                            </th>
                                        @endif
                                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">Test</th>
                                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">Result</th>
                                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">Value</th>
                                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">Notes</th>
                                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">Analyst</th>
                                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($tests as $test)
                                        <tr class="{{ $test->status === 'flagged' ? 'bg-red-50/40' : '' }} hover:bg-gray-50 transition">
                                            @if(!$existingResult)
                                                <td class="px-4 py-3">
                                                    <input type="checkbox"
                                                           value="{{ $test->id }}"
                                                           x-model="selectedTests"
                                                           class="rounded text-red-600 focus:ring-red-500">
                                                </td>
                                            @endif
                                            <td class="px-4 py-3">
                                                <p class="font-medium text-gray-800 text-xs">{{ $test->getDisplayLabel() }}</p>
                                                <span class="inline-flex px-1.5 py-0.5 text-xs rounded capitalize mt-0.5
                                                    {{ $test->getDisplayCategory() === 'microbiological' ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600' }}">
                                                    {{ $test->getDisplayCategory() }}
                                                </span>
                                            </td>
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
                                            <td class="px-4 py-3 text-sm text-gray-700">
                                                @if($test->result_value)
                                                    <span class="font-mono">{{ $test->result_value }}</span>
                                                    @if($test->result_unit)
                                                        <span class="text-xs text-gray-400">{{ $test->result_unit }}</span>
                                                    @endif
                                                @else
                                                    <span class="text-gray-400">—</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-xs text-gray-500 max-w-xs">
                                                {{ $test->result_notes ? \Illuminate\Support\Str::limit($test->result_notes, 80) : '—' }}
                                            </td>
                                            <td class="px-4 py-3 text-xs text-gray-500">
                                                {{ $test->assignedTo?->name ?? '—' }}
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($test->status === 'flagged')
                                                    <span class="inline-flex px-2 py-0.5 text-xs bg-red-50 text-red-700 rounded-full font-medium">⚑ Flagged</span>
                                                @elseif($test->status === 'completed')
                                                    <span class="inline-flex px-2 py-0.5 text-xs bg-green-50 text-green-700 rounded-full">Completed</span>
                                                @else
                                                    <span class="inline-flex px-2 py-0.5 text-xs bg-gray-100 text-gray-500 rounded-full capitalize">{{ str_replace('_',' ',$test->status) }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endforeach

            {{-- ── Authorisation Panel ──────────────────────────── --}}
            @if(!$existingResult)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                    {{-- Authorise Form --}}
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden"
                         x-show="!queryMode">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-sm font-medium text-gray-800">Authorise Results</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Select the overall outcome and sign off.</p>
                        </div>
                        <form method="POST"
                              action="{{ route('director.submissions.authorise', $submission->id) }}"
                              x-data="{ outcome: '' }">
                            @csrf
                            <div class="px-6 py-5 space-y-4">

                                {{-- Overall Outcome --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Overall Outcome *</label>
                                    <div class="space-y-2">
                                        <label class="flex items-start gap-3 p-3 rounded-xl border-2 cursor-pointer transition"
                                               :class="outcome === 'pass' ? 'border-green-400 bg-green-50' : 'border-gray-200 hover:border-gray-300'">
                                            <input type="radio" name="overall_outcome" value="pass"
                                                   x-model="outcome"
                                                   class="mt-0.5 text-green-600 focus:ring-green-500">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800">Pass</p>
                                                <p class="text-xs text-gray-500 mt-0.5">All results within acceptable limits.</p>
                                            </div>
                                        </label>
                                        <label class="flex items-start gap-3 p-3 rounded-xl border-2 cursor-pointer transition"
                                               :class="outcome === 'fail' ? 'border-red-400 bg-red-50' : 'border-gray-200 hover:border-gray-300'">
                                            <input type="radio" name="overall_outcome" value="fail"
                                                   x-model="outcome"
                                                   class="mt-0.5 text-red-600 focus:ring-red-500">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800">Fail</p>
                                                <p class="text-xs text-gray-500 mt-0.5">One or more results outside acceptable limits.</p>
                                            </div>
                                        </label>
                                        <label class="flex items-start gap-3 p-3 rounded-xl border-2 cursor-pointer transition"
                                               :class="outcome === 'inconclusive' ? 'border-amber-400 bg-amber-50' : 'border-gray-200 hover:border-gray-300'">
                                            <input type="radio" name="overall_outcome" value="inconclusive"
                                                   x-model="outcome"
                                                   class="mt-0.5 text-amber-600 focus:ring-amber-500">
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800">Inconclusive</p>
                                                <p class="text-xs text-gray-500 mt-0.5">Results require further investigation.</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- Director Comments --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Director Comments</label>
                                    <textarea name="director_comments" rows="3"
                                              class="w-full border-gray-300 rounded-lg text-sm focus:border-teal-500 focus:ring-teal-500"
                                              placeholder="Optional comments to include in the result report..."></textarea>
                                </div>

                            </div>
                            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                                <button type="button"
                                        @click="queryMode = true"
                                        class="text-sm text-amber-600 hover:text-amber-800 font-medium">
                                    Query analyst instead →
                                </button>
                                <button type="submit"
                                        x-bind:disabled="!outcome"
                                        x-bind:class="!outcome ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-teal-600 hover:bg-teal-700 text-white'"
                                        onclick="return confirm('Authorise this submission? This action cannot be undone.')"
                                        class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    Authorise
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Query Analyst Form --}}
                    <div class="bg-white rounded-xl border border-amber-200 overflow-hidden"
                         x-show="queryMode"
                         x-cloak>
                        <div class="px-6 py-4 border-b border-amber-100 bg-amber-50">
                            <h3 class="text-sm font-medium text-amber-800">Query Analyst</h3>
                            <p class="text-xs text-amber-600 mt-0.5">
                                Select tests to query (tick checkboxes in table above) and describe your concern.
                            </p>
                        </div>
                        <form method="POST"
                              action="{{ route('director.submissions.query', $submission->id) }}">
                            @csrf
                            <div class="px-6 py-5 space-y-4">

                                {{-- Selected tests --}}
                                <div>
                                    <p class="text-xs font-medium text-gray-600 mb-2">
                                        Selected tests:
                                        <span class="text-amber-600 font-semibold" x-text="selectedTests.length + ' selected'"></span>
                                    </p>
                                    <template x-for="testId in selectedTests" :key="testId">
                                        <input type="hidden" name="test_ids[]" :value="testId">
                                    </template>
                                    <p x-show="selectedTests.length === 0" class="text-xs text-red-500">
                                        Please select at least one test from the table above.
                                    </p>
                                </div>

                                {{-- Query notes --}}
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

            {{-- ── Generate Invoice (if authorised) ──────────── --}}
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

        </div>
    </div>
</x-app-layout>