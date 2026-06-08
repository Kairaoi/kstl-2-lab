{{-- resources/views/kstl/analyst/tests/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('analyst.tests.index') }}"
               class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <p class="at-eyebrow">Analyst &middot; Enter Result</p>
                <h2 class="at-title text-xl font-bold leading-tight mt-0.5">
                    {{ $test->getDisplayLabel() }}
                </h2>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ $submission->reference_number }} &middot; {{ $sample->sample_code }}
                </p>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .at-eyebrow { letter-spacing: .16em; text-transform: uppercase; font-size: 10px; color: var(--gold); font-weight: 700; }
        .at-title { font-family: 'Noto Serif', serif; color: var(--navy); letter-spacing: .01em; }
        .at-section-title {
            font-family: 'Noto Serif', serif; color: var(--navy);
            font-size: 13px; font-weight: 700; letter-spacing: .02em;
            display: flex; align-items: center; gap: 8px;
        }
        .at-section-title::before {
            content: ''; width: 3px; height: 14px; background: var(--gold); border-radius: 2px; display: inline-block;
        }
        .at-meta-label { letter-spacing: .07em; text-transform: uppercase; font-size: 10px; color: var(--subtle); font-weight: 600; }
    </style>
    @endpush

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Director query banner (when this test was flagged back for clarification) --}}
            @if($test->status === 'flagged')
                <div class="bg-orange-50 border border-orange-200 rounded-2xl p-5">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-orange-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-orange-800">Queried by the Director</p>
                            <p class="text-xs text-orange-700 mt-0.5">
                                This test was returned for clarification. Review the Director's query in the result notes below,
                                amend the result as needed, and save to send it back for authorisation.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ── Left: Context ──────────────────────────────── --}}
                <div class="space-y-5">

                    {{-- Test Info --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100">
                            <h3 class="at-section-title">Test Details</h3>
                        </div>
                        <dl class="px-5 py-4 space-y-3 text-sm">
                            <div>
                                <dt class="at-meta-label">Test</dt>
                                <dd class="font-medium text-gray-800 mt-0.5">{{ $test->getDisplayLabel() }}</dd>
                            </div>
                            <div>
                                <dt class="at-meta-label">Category</dt>
                                <dd class="mt-0.5">
                                    <span class="inline-flex px-2 py-0.5 text-xs rounded-full capitalize
                                        {{ $test->getDisplayCategory() === 'microbiological' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">
                                        {{ $test->getDisplayCategory() }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="at-meta-label">Status</dt>
                                <dd class="mt-0.5">
                                    <x-kstl.status-badge :status="$test->status" />
                                </dd>
                            </div>
                            @if($test->started_at)
                            <div>
                                <dt class="at-meta-label">Started</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $test->started_at->format('d M Y H:i') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    {{-- Sample Info --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100">
                            <h3 class="at-section-title">Sample</h3>
                        </div>
                        <dl class="px-5 py-4 space-y-3 text-sm">
                            <div>
                                <dt class="at-meta-label">Code</dt>
                                <dd class="font-mono font-medium text-gray-800 mt-0.5">{{ $sample->sample_code }}</dd>
                            </div>
                            <div>
                                <dt class="at-meta-label">Common Name</dt>
                                <dd class="font-medium text-gray-800 mt-0.5">{{ $sample->common_name }}</dd>
                            </div>
                            @if($sample->scientific_name)
                            <div>
                                <dt class="at-meta-label">Scientific Name</dt>
                                <dd class="italic text-gray-600 mt-0.5">{{ $sample->scientific_name }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="at-meta-label">Quantity</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $sample->quantity }} {{ $sample->quantity_unit }}</dd>
                            </div>
                            <div>
                                <dt class="at-meta-label">Sampling Date</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $sample->sampling_date->format('d M Y') }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Submission Info --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100">
                            <h3 class="at-section-title">Submission</h3>
                        </div>
                        <dl class="px-5 py-4 space-y-3 text-sm">
                            <div>
                                <dt class="at-meta-label">Reference</dt>
                                <dd class="font-mono text-xs font-medium text-gray-800 mt-0.5">{{ $submission->reference_number }}</dd>
                            </div>
                            <div>
                                <dt class="at-meta-label">Client</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $submission->client->company_name }}</dd>
                            </div>
                            <div>
                                <dt class="at-meta-label">Priority</dt>
                                <dd class="mt-0.5">
                                    <x-kstl.priority-badge :priority="$submission->priority" />
                                </dd>
                            </div>
                            @if($submission->results_required_by)
                            <div>
                                <dt class="at-meta-label">Required By</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $submission->results_required_by->format('d M Y') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                </div>

                {{-- ── Right: Result Form ──────────────────────────── --}}
                <div class="lg:col-span-2">
                    @if(($locked ?? false) || $test->status === 'completed')
                        {{-- ── LOCKED: read-only finalised result (audit view) ── --}}
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <div>
                                    <h3 class="at-section-title">Finalised Result</h3>
                                    <p class="text-xs text-gray-400 mt-1">This result is locked for audit and can only be viewed.</p>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Locked
                                </span>
                            </div>
                            <dl class="px-6 py-5 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                                @php
                                    $qlabels = ['pass'=>'Pass','fail'=>'Fail','detected'=>'Detected','not_detected'=>'Not Detected','less_than'=>'Less Than','greater_than'=>'Greater Than','equal_to'=>'Equal To','pending'=>'Pending'];
                                @endphp
                                <div>
                                    <dt class="at-meta-label">Result Qualifier</dt>
                                    <dd class="mt-1 text-sm font-semibold text-gray-800">{{ $qlabels[$test->result_qualifier] ?? ucfirst(str_replace('_',' ',$test->result_qualifier)) }}</dd>
                                </div>
                                <div>
                                    <dt class="at-meta-label">Result Value</dt>
                                    <dd class="mt-1 text-sm text-gray-800 font-mono">{{ $test->result_value ?: '—' }} <span class="text-gray-400 font-sans">{{ $test->result_unit }}</span></dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="at-meta-label">Result Notes</dt>
                                    <dd class="mt-1 text-sm text-gray-700 whitespace-pre-line">{{ $test->result_notes ?: '—' }}</dd>
                                </div>
                                <div>
                                    <dt class="at-meta-label">Finalised By</dt>
                                    <dd class="mt-1 text-sm text-gray-800">{{ $test->assignedTo?->name ?? trim(($test->assignedTo->first_name ?? '') . ' ' . ($test->assignedTo->last_name ?? '')) ?: '—' }}</dd>
                                </div>
                                <div>
                                    <dt class="at-meta-label">Completed At</dt>
                                    <dd class="mt-1 text-sm text-gray-800">{{ $test->completed_at?->format('d M Y \a\t H:i') ?? '—' }}</dd>
                                </div>
                            </dl>
                        </div>
                    @else
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="at-section-title">Enter Test Result</h3>
                            <p class="text-xs text-gray-400 mt-1">Record the result for this test. All fields except qualifier are optional.</p>
                        </div>

                        <form method="POST"
                              id="result-form"
                              action="{{ route('analyst.tests.result', $test->id) }}"
                              x-data="{ qualifier: '{{ $test->result_qualifier ?? 'pending' }}', flagged: {{ $test->status === 'flagged' ? 'true' : 'false' }} }">
                            @csrf

                            <div class="px-6 py-5 space-y-6">

                                {{-- Result Qualifier --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-3">
                                        Result Qualifier <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                        @php
                                            $qualifiers = [
                                                'pass'         => ['label' => 'Pass',         'color' => 'green'],
                                                'fail'         => ['label' => 'Fail',         'color' => 'red'],
                                                'detected'     => ['label' => 'Detected',     'color' => 'orange'],
                                                'not_detected' => ['label' => 'Not Detected', 'color' => 'green'],
                                                'less_than'    => ['label' => '< (Less Than)', 'color' => 'blue'],
                                                'greater_than' => ['label' => '> (Greater Than)', 'color' => 'blue'],
                                                'equal_to'     => ['label' => '= (Equal To)', 'color' => 'blue'],
                                            ];
                                        @endphp
                                        @foreach($qualifiers as $value => $cfg)
                                            <label class="flex items-center gap-2 p-3 rounded-lg border-2 cursor-pointer transition text-sm"
                                                   :class="qualifier === '{{ $value }}'
                                                       ? 'border-indigo-400 bg-indigo-50 text-indigo-700 font-medium'
                                                       : 'border-gray-200 text-gray-600 hover:border-gray-300'">
                                                <input type="radio"
                                                       name="result_qualifier"
                                                       value="{{ $value }}"
                                                       x-model="qualifier"
                                                       class="sr-only">
                                                {{ $cfg['label'] }}
                                            </label>
                                        @endforeach
                                    </div>
                                    <x-input-error for="result_qualifier" class="mt-1"/>
                                </div>

                                {{-- Result Value + Unit --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Result Value
                                        </label>
                                        <x-input type="text"
                                                 name="result_value"
                                                 value="{{ old('result_value', $test->result_value) }}"
                                                 class="w-full"
                                                 placeholder="e.g. 12.4, <10, Detected"/>
                                        <p class="text-xs text-gray-400 mt-1">Numeric or qualitative value</p>
                                        <x-input-error for="result_value" class="mt-1"/>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                            Unit
                                        </label>
                                        <x-input type="text"
                                                 name="result_unit"
                                                 value="{{ old('result_unit', $test->result_unit) }}"
                                                 class="w-full"
                                                 placeholder="e.g. mg/kg, CFU/g, pH units"/>
                                        <p class="text-xs text-gray-400 mt-1">Leave blank if not applicable</p>
                                        <x-input-error for="result_unit" class="mt-1"/>
                                    </div>
                                </div>

                                {{-- Notes --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Result Notes
                                        @if($test->status === 'flagged')
                                            <span class="ml-1 text-xs font-normal text-orange-600">(includes the Director's query)</span>
                                        @endif
                                    </label>
                                    <textarea name="result_notes"
                                              rows="3"
                                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                              placeholder="Additional observations, instrument readings, methodology notes...">{{ old('result_notes', $test->result_notes) }}</textarea>
                                    <x-input-error for="result_notes" class="mt-1"/>
                                </div>

                                {{-- Flag for Director and Save are rendered below, after Supporting Files --}}

                            </div>
                        </form>
                    </div>
                    @endif

                    {{-- ── Supporting Files (shown for both active and locked) ── --}}
                    @include('kstl.analyst.tests._attachments', ['test' => $test, 'locked' => ($locked ?? false)])

                    {{-- ── Flag for Director Review + Save (placed after Supporting Files) ── --}}
                    @unless(($locked ?? false) || $test->status === 'completed')
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mt-5">
                            <div class="px-6 py-5">
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                    <label class="flex items-start gap-3 cursor-pointer">
                                        <input type="checkbox"
                                               name="flag"
                                               value="1"
                                               form="result-form"
                                               @checked($test->status === 'flagged')
                                               class="mt-0.5 text-amber-600 focus:ring-amber-500 rounded"/>
                                        <div>
                                            <p class="text-sm font-medium text-amber-800">Flag for Director Review</p>
                                            <p class="text-xs text-amber-600 mt-0.5">
                                                Check this if the result is anomalous, exceeds limits, or requires Director attention before authorisation.
                                            </p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            {{-- Actions --}}
                            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between bg-gray-50">
                                <a href="{{ route('analyst.tests.index') }}">
                                    <x-secondary-button type="button">Cancel</x-secondary-button>
                                </a>
                                <button type="submit"
                                        form="result-form"
                                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Save Result
                                </button>
                            </div>
                        </div>
                    @endunless
                </div>

            </div>
        </div>
    </div>
</x-app-layout>