{{-- resources/views/kstl/reception/submissions/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('reception.dashboard') }}"
                   class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $submission->reference_number }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Submitted {{ $submission->submitted_at?->format('d M Y \a\t H:i') ?? $submission->created_at->format('d M Y \a\t H:i') }}
                    </p>
                </div>
            </div>

            {{-- Status badge --}}
            @php
                $statusConfig = [
                    'submitted'          => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
                    'received'           => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                    'assessing'          => 'bg-purple-50 text-purple-700 ring-purple-600/20',
                    'accepted'           => 'bg-green-50 text-green-700 ring-green-600/20',
                    'rejected'           => 'bg-red-50 text-red-700 ring-red-600/20',
                    'consent_to_proceed' => 'bg-orange-50 text-orange-700 ring-orange-600/20',
                    'testing'            => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
                ];
                $sc = $statusConfig[$submission->status] ?? 'bg-gray-50 text-gray-500 ring-gray-500/20';
            @endphp
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ring-1 ring-inset {{ $sc }}">
                {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash --}}
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg flex items-center gap-3">
                    <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg flex items-center gap-3">
                    <svg class="w-4 h-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a1 1 0 112 0v4a1 1 0 11-2 0V9zm1-5a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ── Left: Submission Details ──────────────────────────── --}}
                <div class="lg:col-span-1 space-y-5">

                    {{-- Client Info --}}
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100">
                            <h3 class="text-sm font-medium text-gray-800">Client</h3>
                        </div>
                        <dl class="px-5 py-4 space-y-3 text-sm">
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Company</dt>
                                <dd class="font-medium text-gray-800 mt-0.5">{{ $submission->client->company_name ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Responsible Officer</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $submission->client->responsible_officer_name ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Contact</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $submission->client->company_phone ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Email</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $submission->client->user->email ?? '—' }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Submission Info --}}
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100">
                            <h3 class="text-sm font-medium text-gray-800">Submission Details</h3>
                        </div>
                        <dl class="px-5 py-4 space-y-3 text-sm">
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Sample Name</dt>
                                <dd class="font-medium text-gray-800 mt-0.5">{{ $submission->sample_name }}</dd>
                            </div>
                            @if($submission->scientific_name)
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Scientific Name</dt>
                                <dd class="text-gray-700 italic mt-0.5">{{ $submission->scientific_name }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Sample Type</dt>
                                <dd class="text-gray-700 capitalize mt-0.5">{{ $submission->sample_type }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Quantity</dt>
                                <dd class="text-gray-700 mt-0.5">
                                    {{ $submission->sample_quantity }} {{ $submission->sample_quantity_unit }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Transport</dt>
                                <dd class="text-gray-700 mt-0.5 capitalize">
                                    {{ $submission->transport_method }}
                                    @if($submission->transport_detail)
                                        <span class="text-gray-400">— {{ str_replace('_', ' ', $submission->transport_detail) }}</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Priority</dt>
                                <dd class="mt-0.5">
                                    @php
                                        $pc = ['routine' => 'bg-gray-100 text-gray-600', 'urgent' => 'bg-amber-50 text-amber-700', 'emergency' => 'bg-red-50 text-red-700'];
                                    @endphp
                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full {{ $pc[$submission->priority ?? 'routine'] ?? 'bg-gray-100 text-gray-600' }} capitalize">
                                        {{ $submission->priority ?? 'Routine' }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Collection Date</dt>
                                <dd class="text-gray-700 mt-0.5">
                                    {{ $submission->collected_at?->format('d M Y') ?? '—' }}
                                </dd>
                            </div>
                            @if($submission->collection_location)
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Collection Location</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $submission->collection_location }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    {{-- Tests Requested --}}
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100">
                            <h3 class="text-sm font-medium text-gray-800">Tests Requested</h3>
                        </div>
                        <div class="px-5 py-4">
                            @php
                                $tests = is_array($submission->tests_requested)
                                    ? $submission->tests_requested
                                    : json_decode($submission->tests_requested ?? '[]', true) ?? [];

                                $micro = array_filter($tests, fn($t) => in_array($t, [
                                    'total_coliforms','e_coli','enterococci',
                                    'yeast_mold','apc','e_coli_coliform','staph_aureus'
                                ]));
                                $chem = array_filter($tests, fn($t) => in_array($t, [
                                    'histamine','moisture','ph','conductivity','water_activity'
                                ]));
                            @endphp

                            @if(count($micro))
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1.5">Microbiological</p>
                                <div class="flex flex-wrap gap-1 mb-3">
                                    @foreach($micro as $t)
                                        <span class="inline-flex px-2 py-0.5 text-xs bg-purple-50 text-purple-700 rounded-full">
                                            {{ str_replace('_', ' ', $t) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            @if(count($chem))
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1.5">Chemical</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach($chem as $t)
                                        <span class="inline-flex px-2 py-0.5 text-xs bg-blue-50 text-blue-700 rounded-full">
                                            {{ str_replace('_', ' ', $t) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            @if($submission->tests_other)
                                <p class="text-xs text-gray-500 mt-2 bg-gray-50 rounded p-2">
                                    <span class="font-medium">Other:</span> {{ $submission->tests_other }}
                                </p>
                            @endif

                            @if(!count($tests) && !$submission->tests_other)
                                <p class="text-sm text-gray-400">No tests specified.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Special Instructions --}}
                    @if($submission->special_instructions || $submission->client_notes)
                        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                            <div class="px-5 py-3.5 border-b border-gray-100">
                                <h3 class="text-sm font-medium text-gray-800">Instructions</h3>
                            </div>
                            <div class="px-5 py-4 space-y-3 text-sm">
                                @if($submission->special_instructions)
                                    <p class="text-gray-700">{{ $submission->special_instructions }}</p>
                                @endif
                                @if($submission->client_notes)
                                    <p class="text-gray-500 text-xs">{{ $submission->client_notes }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>

                {{-- ── Right: Receive Form ───────────────────────────────── --}}
                <div class="lg:col-span-2">

                    @if($submission->status === 'submitted')
                        {{-- ── Receive Form ──────────────────────────────────── --}}
                        <form method="POST"
                              action="{{ route('reception.submissions.receive', $submission->id) }}"
                              x-data="sampleReceiveForm()"
                              @submit.prevent="submitForm($el)">
                            @csrf

                            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden mb-5">
                                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-800">Log Physical Sample Arrival</h3>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            Enter details for each physical sample received. Add a row per specimen.
                                        </p>
                                    </div>
                                    <button type="button"
                                            @click="addRow()"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-600 border border-blue-200 rounded-lg hover:bg-blue-50 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Add Sample
                                    </button>
                                </div>

                                {{-- Sample Rows --}}
                                <div class="divide-y divide-gray-50">
                                    <template x-for="(row, index) in rows" :key="index">
                                        <div class="px-6 py-5">
                                            <div class="flex items-center justify-between mb-4">
                                                <p class="text-sm font-medium text-gray-700">
                                                    Sample <span x-text="index + 1"></span>
                                                </p>
                                                <button type="button"
                                                        @click="removeRow(index)"
                                                        x-show="rows.length > 1"
                                                        class="text-xs text-red-500 hover:text-red-700 transition">
                                                    Remove
                                                </button>
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                                {{-- Common Name --}}
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                                        Common Name *
                                                    </label>
                                                    <input type="text"
                                                           :name="`samples[${index}][common_name]`"
                                                           x-model="row.common_name"
                                                           placeholder="e.g. Yellowfin Tuna"
                                                           required
                                                           class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"/>
                                                </div>

                                                {{-- Scientific Name --}}
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                                        Scientific Name
                                                    </label>
                                                    <input type="text"
                                                           :name="`samples[${index}][scientific_name]`"
                                                           x-model="row.scientific_name"
                                                           placeholder="e.g. Thunnus albacares"
                                                           class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"/>
                                                </div>

                                                {{-- Sampling Date --}}
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                                        Sampling Date *
                                                    </label>
                                                    <input type="date"
                                                           :name="`samples[${index}][sampling_date]`"
                                                           x-model="row.sampling_date"
                                                           :max="today"
                                                           required
                                                           class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"/>
                                                </div>

                                                {{-- Quantity --}}
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                                        Quantity *
                                                    </label>
                                                    <div class="flex rounded-md shadow-sm">
                                                        <input type="number"
                                                               :name="`samples[${index}][quantity]`"
                                                               x-model="row.quantity"
                                                               placeholder="0"
                                                               min="0"
                                                               step="0.01"
                                                               required
                                                               class="flex-1 min-w-0 text-sm border-gray-300 rounded-l-md focus:border-indigo-500 focus:ring-indigo-500"/>
                                                        <select :name="`samples[${index}][quantity_unit]`"
                                                                x-model="row.quantity_unit"
                                                                class="border-l-0 border-gray-300 rounded-r-md text-sm px-2 focus:border-indigo-500 focus:ring-indigo-500">
                                                            <option value="g">g</option>
                                                            <option value="kg">kg</option>
                                                            <option value="ml">ml</option>
                                                            <option value="L">L</option>
                                                            <option value="pcs">pcs</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- Notes --}}
                                                <div class="sm:col-span-2">
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                                        Notes
                                                    </label>
                                                    <input type="text"
                                                           :name="`samples[${index}][notes]`"
                                                           x-model="row.notes"
                                                           placeholder="Condition, packaging observations..."
                                                           class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"/>
                                                </div>

                                            </div>
                                        </div>
                                    </template>
                                </div>

                                {{-- Validation errors --}}
                                <x-validation-errors class="mx-6 mb-4 bg-red-50 border border-red-200 rounded-xl p-4"/>
                            </div>

                            <div class="flex items-center justify-between">
                                <a href="{{ route('reception.dashboard') }}">
                                    <x-secondary-button type="button">Cancel</x-secondary-button>
                                </a>
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Confirm Receipt &amp; Continue to Assessment
                                </button>
                            </div>

                        </form>

                    @elseif($submission->status === 'received')
                        {{-- Already received — prompt to assess --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 text-center">
                            <svg class="w-10 h-10 text-blue-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-sm font-medium text-blue-800">Samples received. Ready for assessment.</p>
                            <p class="text-xs text-blue-600 mt-1 mb-4">
                                {{ $samples->count() }} sample{{ $samples->count() !== 1 ? 's' : '' }} logged.
                            </p>
                            <a href="{{ route('reception.submissions.assess', $submission->id) }}"
                               class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                Start Assessment →
                            </a>
                        </div>

                        {{-- Logged Samples --}}
                        @if($samples->isNotEmpty())
                            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden mt-5">
                                <div class="px-5 py-3.5 border-b border-gray-100">
                                    <h3 class="text-sm font-medium text-gray-800">Logged Samples</h3>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead class="bg-gray-50 border-b border-gray-100">
                                            <tr>
                                                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Code</th>
                                                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Name</th>
                                                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Qty</th>
                                                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50">
                                            @foreach($samples as $sample)
                                                <tr>
                                                    <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $sample->sample_code ?? '—' }}</td>
                                                    <td class="px-5 py-3">
                                                        <p class="font-medium text-gray-800">{{ $sample->common_name }}</p>
                                                        @if($sample->scientific_name)
                                                            <p class="text-xs text-gray-400 italic">{{ $sample->scientific_name }}</p>
                                                        @endif
                                                    </td>
                                                    <td class="px-5 py-3 text-gray-700">{{ $sample->quantity }} {{ $sample->quantity_unit }}</td>
                                                    <td class="px-5 py-3">
                                                        <span class="inline-flex px-2 py-0.5 text-xs bg-yellow-50 text-yellow-700 rounded-full capitalize">
                                                            {{ $sample->status }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                    @elseif($submission->status === 'accepted')
                        {{-- All samples passed — send to testing ──── --}}
                        <div class="bg-green-50 border border-green-200 rounded-xl p-6">
                            <div class="flex items-start gap-3 mb-5">
                                <svg class="w-6 h-6 text-green-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-green-800">All Samples Accepted</p>
                                    <p class="text-sm text-green-700 mt-0.5">
                                        All {{ $samples->count() }} sample{{ $samples->count() !== 1 ? 's' : '' }}
                                        passed the assessment. Ready to send to the testing queue.
                                    </p>
                                </div>
                            </div>

                            <form method="POST"
                                  action="{{ route('reception.submissions.send-to-testing', $submission->id) }}"
                                  onsubmit="return confirm('Send all accepted samples to the testing queue?')">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                    Send to Testing Queue
                                </button>
                            </form>
                        </div>

                        {{-- Accepted samples list --}}
                        @if($samples->isNotEmpty())
                            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden mt-5">
                                <div class="px-5 py-3.5 border-b border-gray-100">
                                    <h3 class="text-sm font-medium text-gray-800">
                                        Accepted Samples ({{ $samples->count() }})
                                    </h3>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead class="bg-gray-50 border-b border-gray-100">
                                            <tr>
                                                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Code</th>
                                                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Name</th>
                                                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Qty</th>
                                                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50">
                                            @foreach($samples as $sample)
                                                <tr>
                                                    <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $sample->sample_code ?? '—' }}</td>
                                                    <td class="px-5 py-3">
                                                        <p class="font-medium text-gray-800">{{ $sample->common_name }}</p>
                                                        @if($sample->scientific_name)
                                                            <p class="text-xs text-gray-400 italic">{{ $sample->scientific_name }}</p>
                                                        @endif
                                                    </td>
                                                    <td class="px-5 py-3 text-gray-700">{{ $sample->quantity }} {{ $sample->quantity_unit }}</td>
                                                    <td class="px-5 py-3">
                                                        <span class="inline-flex px-2 py-0.5 text-xs bg-green-50 text-green-700 rounded-full capitalize">
                                                            {{ str_replace('_', ' ', $sample->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                    @elseif($submission->status === 'consent_to_proceed')
                        {{-- Client consented — send to testing ────── --}}
                        <div class="bg-orange-50 border border-orange-200 rounded-xl p-6">
                            <div class="flex items-start gap-3 mb-5">
                                <svg class="w-6 h-6 text-orange-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-orange-800">Client Consented to Proceed</p>
                                    <p class="text-sm text-orange-700 mt-0.5">
                                        The client has acknowledged the assessment findings and consented to proceed with testing.
                                        Click below to send the sample(s) to the testing queue.
                                    </p>
                                </div>
                            </div>

                            <form method="POST"
                                  action="{{ route('reception.submissions.send-to-testing', $submission->id) }}"
                                  onsubmit="return confirm('Send consented samples to the testing queue?')">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                    Send to Testing Queue
                                </button>
                            </form>
                        </div>

                        {{-- Samples with consent status --}}
                        @if($samples->isNotEmpty())
                            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden mt-5">
                                <div class="px-5 py-3.5 border-b border-gray-100">
                                    <h3 class="text-sm font-medium text-gray-800">Samples</h3>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead class="bg-gray-50 border-b border-gray-100">
                                            <tr>
                                                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Code</th>
                                                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Name</th>
                                                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50">
                                            @foreach($samples as $sample)
                                                <tr>
                                                    <td class="px-5 py-3 font-mono text-xs">{{ $sample->sample_code }}</td>
                                                    <td class="px-5 py-3 font-medium text-gray-800">{{ $sample->common_name }}</td>
                                                    <td class="px-5 py-3">
                                                        @php
                                                            $sc = [
                                                                'accepted'           => 'bg-green-50 text-green-700',
                                                                'rejected'           => 'bg-red-50 text-red-700',
                                                                'consent_to_proceed' => 'bg-orange-50 text-orange-700',
                                                            ];
                                                        @endphp
                                                        <span class="inline-flex px-2 py-0.5 text-xs rounded-full capitalize {{ $sc[$sample->status] ?? 'bg-gray-100 text-gray-500' }}">
                                                            {{ str_replace('_', ' ', $sample->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                    @else
                        {{-- Any other status — read only view --}}
                        <div class="bg-white rounded-xl border border-gray-100 p-6">
                            <p class="text-sm text-gray-500">
                                This submission is currently
                                <span class="font-medium text-gray-800 capitalize">{{ str_replace('_', ' ', $submission->status) }}</span>
                                and is no longer pending reception action.
                            </p>
                            @if($submission->received_at)
                                <p class="text-xs text-gray-400 mt-2">
                                    Received: {{ $submission->received_at->format('d M Y \a\t H:i') }}
                                </p>
                            @endif
                        </div>

                        @if($samples->isNotEmpty())
                            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden mt-5">
                                <div class="px-5 py-3.5 border-b border-gray-100">
                                    <h3 class="text-sm font-medium text-gray-800">Samples ({{ $samples->count() }})</h3>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead class="bg-gray-50 border-b border-gray-100">
                                            <tr>
                                                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Code</th>
                                                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Name</th>
                                                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Qty</th>
                                                <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-50">
                                            @foreach($samples as $sample)
                                                <tr>
                                                    <td class="px-5 py-3 font-mono text-xs text-gray-600">{{ $sample->sample_code ?? '—' }}</td>
                                                    <td class="px-5 py-3">
                                                        <p class="font-medium text-gray-800">{{ $sample->common_name }}</p>
                                                        @if($sample->scientific_name)
                                                            <p class="text-xs text-gray-400 italic">{{ $sample->scientific_name }}</p>
                                                        @endif
                                                    </td>
                                                    <td class="px-5 py-3 text-gray-700">{{ $sample->quantity }} {{ $sample->quantity_unit }}</td>
                                                    <td class="px-5 py-3">
                                                        @php
                                                            $sampleStatus = [
                                                                'pending'            => 'bg-yellow-50 text-yellow-700',
                                                                'accepted'           => 'bg-green-50 text-green-700',
                                                                'rejected'           => 'bg-red-50 text-red-700',
                                                                'consent_to_proceed' => 'bg-orange-50 text-orange-700',
                                                                'testing'            => 'bg-indigo-50 text-indigo-700',
                                                                'completed'          => 'bg-green-50 text-green-700',
                                                            ];
                                                        @endphp
                                                        <span class="inline-flex px-2 py-0.5 text-xs rounded-full capitalize {{ $sampleStatus[$sample->status] ?? 'bg-gray-50 text-gray-600' }}">
                                                            {{ str_replace('_', ' ', $sample->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    @endif

                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function sampleReceiveForm() {
            return {
                today: new Date().toISOString().split('T')[0],
                rows: [
                    {
                        common_name:     '{{ $submission->sample_name }}',
                        scientific_name: '{{ $submission->scientific_name ?? '' }}',
                        sampling_date:   '{{ $submission->collected_at?->format('Y-m-d') ?? '' }}',
                        quantity:        '{{ $submission->sample_quantity ?? '' }}',
                        quantity_unit:   '{{ $submission->sample_quantity_unit ?? 'g' }}',
                        notes:           '',
                    }
                ],

                addRow() {
                    this.rows.push({
                        common_name:     '',
                        scientific_name: '',
                        sampling_date:   '',
                        quantity:        '',
                        quantity_unit:   'g',
                        notes:           '',
                    });
                },

                removeRow(index) {
                    if (this.rows.length > 1) {
                        this.rows.splice(index, 1);
                    }
                },

                submitForm(form) {
                    form.submit();
                }
            }
        }
    </script>
    @endpush

</x-app-layout>