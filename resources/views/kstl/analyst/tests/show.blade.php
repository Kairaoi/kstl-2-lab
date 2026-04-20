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
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Enter Result — {{ $test->getDisplayLabel() }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ $submission->reference_number }} · {{ $sample->sample_code }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ── Left: Context ──────────────────────────────── --}}
                <div class="space-y-5">

                    {{-- Test Info --}}
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100">
                            <h3 class="text-sm font-medium text-gray-800">Test Details</h3>
                        </div>
                        <dl class="px-5 py-4 space-y-3 text-sm">
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Test</dt>
                                <dd class="font-medium text-gray-800 mt-0.5">{{ $test->getDisplayLabel() }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Category</dt>
                                <dd class="mt-0.5">
                                    <span class="inline-flex px-2 py-0.5 text-xs rounded-full capitalize
                                        {{ $test->getDisplayCategory() === 'microbiological' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">
                                        {{ $test->getDisplayCategory() }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Status</dt>
                                <dd class="mt-0.5">
                                    <span class="inline-flex px-2 py-0.5 text-xs bg-blue-50 text-blue-700 rounded-full capitalize">
                                        {{ str_replace('_', ' ', $test->status) }}
                                    </span>
                                </dd>
                            </div>
                            @if($test->started_at)
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Started</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $test->started_at->format('d M Y H:i') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    {{-- Sample Info --}}
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100">
                            <h3 class="text-sm font-medium text-gray-800">Sample</h3>
                        </div>
                        <dl class="px-5 py-4 space-y-3 text-sm">
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Code</dt>
                                <dd class="font-mono font-medium text-gray-800 mt-0.5">{{ $sample->sample_code }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Common Name</dt>
                                <dd class="font-medium text-gray-800 mt-0.5">{{ $sample->common_name }}</dd>
                            </div>
                            @if($sample->scientific_name)
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Scientific Name</dt>
                                <dd class="italic text-gray-600 mt-0.5">{{ $sample->scientific_name }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Quantity</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $sample->quantity }} {{ $sample->quantity_unit }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Sampling Date</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $sample->sampling_date->format('d M Y') }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Submission Info --}}
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100">
                            <h3 class="text-sm font-medium text-gray-800">Submission</h3>
                        </div>
                        <dl class="px-5 py-4 space-y-3 text-sm">
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Reference</dt>
                                <dd class="font-mono text-xs font-medium text-gray-800 mt-0.5">{{ $submission->reference_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Client</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $submission->client->company_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Priority</dt>
                                <dd class="mt-0.5">
                                    @php
                                        $pc = ['routine' => 'bg-gray-100 text-gray-600', 'urgent' => 'bg-amber-50 text-amber-700', 'emergency' => 'bg-red-50 text-red-700'];
                                    @endphp
                                    <span class="inline-flex px-2 py-0.5 text-xs rounded-full capitalize {{ $pc[$submission->priority ?? 'routine'] }}">
                                        {{ $submission->priority ?? 'Routine' }}
                                    </span>
                                </dd>
                            </div>
                            @if($submission->results_required_by)
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Required By</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $submission->results_required_by->format('d M Y') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                </div>

                {{-- ── Right: Result Form ──────────────────────────── --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-sm font-medium text-gray-800">Enter Test Result</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Record the result for this test. All fields except qualifier are optional.</p>
                        </div>

                        <form method="POST"
                              action="{{ route('analyst.tests.result', $test->id) }}"
                              x-data="{ qualifier: '{{ $test->result_qualifier ?? 'pending' }}', flagged: false }">
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
                                    </label>
                                    <textarea name="result_notes"
                                              rows="3"
                                              class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                              placeholder="Additional observations, instrument readings, methodology notes...">{{ old('result_notes', $test->result_notes) }}</textarea>
                                    <x-input-error for="result_notes" class="mt-1"/>
                                </div>

                                {{-- Flag for Director --}}
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4"
                                     x-data>
                                    <label class="flex items-start gap-3 cursor-pointer">
                                        <input type="checkbox"
                                               name="flag"
                                               value="1"
                                               x-model="flagged"
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
                                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Save Result
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>