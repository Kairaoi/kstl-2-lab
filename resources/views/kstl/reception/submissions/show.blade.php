{{-- resources/views/kstl/reception/submissions/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('reception.dashboard') }}"
               class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <p class="rs-eyebrow">Reception &middot; Sample Intake Record</p>
                <h2 class="rs-title text-xl font-bold leading-tight mt-0.5">{{ $submission->reference_number }}</h2>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .rs-eyebrow { letter-spacing: .16em; text-transform: uppercase; font-size: 10px; color: var(--gold); font-weight: 700; }
        .rs-title { font-family: 'Noto Serif', serif; color: var(--navy); letter-spacing: .01em; }
        .rs-doc { background: var(--surface); border: 1px solid var(--border); }
        .rs-letterhead {
            border-bottom: 3px double var(--navy);
            background: linear-gradient(180deg, #fbfaf8 0%, #ffffff 100%);
        }
        .rs-crest {
            width: 46px; height: 46px; border-radius: 50%;
            border: 2px solid var(--gold);
            display: flex; align-items: center; justify-content: center;
        }
        .rs-crest svg { width: 23px; height: 23px; stroke: var(--navy); fill: none; }
        .rs-lab-title { font-family: 'Noto Serif', serif; color: var(--navy); letter-spacing: .01em; }
        .rs-section-title {
            font-family: 'Noto Serif', serif; color: var(--navy);
            font-size: 13px; font-weight: 700; letter-spacing: .02em;
            display: flex; align-items: center; gap: 8px;
        }
        .rs-section-title::before {
            content: ''; width: 3px; height: 14px; background: var(--gold); border-radius: 2px; display: inline-block;
        }
        .rs-meta-label { letter-spacing: .07em; text-transform: uppercase; font-size: 10px; color: var(--subtle); font-weight: 600; }
    </style>
    @endpush

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

            {{-- ════════ INTAKE RECORD DOCUMENT ════════ --}}
            <div class="rs-doc rounded-xl shadow-sm overflow-hidden">

                {{-- Letterhead band --}}
                <div class="rs-letterhead px-8 py-6">
                    <div class="flex items-start justify-between gap-6">
                        <div class="flex items-start gap-4">
                            <div class="rs-crest">
                                <svg viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                                </svg>
                            </div>
                            <div>
                                <p class="rs-eyebrow">Government of Kiribati &middot; Ministry of Fisheries &amp; Ocean Resources</p>
                                <h1 class="rs-lab-title text-lg font-bold mt-1">Kiribati Seafood Toxicology Laboratory</h1>
                                <p class="text-xs text-gray-500 mt-0.5">Sample Intake Record</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="font-mono text-sm font-semibold text-gray-800">{{ $submission->reference_number }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                Submitted {{ $submission->submitted_at?->format('d M Y') ?? $submission->created_at->format('d M Y') }}
                            </p>
                            <div class="mt-2 flex justify-end">
                                <x-kstl.status-badge :status="$submission->status" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Particulars — Client + Submission side by side --}}
                <div class="px-8 py-6 grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-6 border-b border-gray-100">

                    {{-- Client --}}
                    <div>
                        <p class="rs-section-title mb-3">Client</p>
                        <dl class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                            <div>
                                <dt class="rs-meta-label">Company</dt>
                                <dd class="font-medium text-gray-800 mt-0.5">{{ $submission->client->company_name ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="rs-meta-label">Responsible Officer</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $submission->client->responsible_officer_name ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="rs-meta-label">Contact</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $submission->client->company_phone ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="rs-meta-label">Email</dt>
                                <dd class="text-gray-700 mt-0.5 break-words">{{ $submission->client->user->email ?? '—' }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Submission --}}
                    <div>
                        <p class="rs-section-title mb-3">Submission Details</p>
                        <dl class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                            <div>
                                <dt class="rs-meta-label">Sample Name</dt>
                                <dd class="font-medium text-gray-800 mt-0.5">{{ $submission->sample_name }}</dd>
                            </div>
                            @if($submission->scientific_name)
                            <div>
                                <dt class="rs-meta-label">Scientific Name</dt>
                                <dd class="text-gray-700 italic mt-0.5">{{ $submission->scientific_name }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="rs-meta-label">Sample Type</dt>
                                <dd class="text-gray-700 capitalize mt-0.5">{{ $submission->sample_type }}</dd>
                            </div>
                            <div>
                                <dt class="rs-meta-label">Quantity</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $submission->sample_quantity }} {{ $submission->sample_quantity_unit }}</dd>
                            </div>
                            <div>
                                <dt class="rs-meta-label">Priority</dt>
                                <dd class="mt-0.5"><x-kstl.priority-badge :priority="$submission->priority" /></dd>
                            </div>
                            <div>
                                <dt class="rs-meta-label">Transport</dt>
                                <dd class="text-gray-700 mt-0.5 capitalize">
                                    {{ $submission->transport_method }}
                                    @if($submission->transport_detail)
                                        <span class="text-gray-400">— {{ str_replace('_', ' ', $submission->transport_detail) }}</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="rs-meta-label">Collection Date</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $submission->collected_at?->format('d M Y') ?? '—' }}</dd>
                            </div>
                            @if($submission->collection_location)
                            <div>
                                <dt class="rs-meta-label">Collection Location</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $submission->collection_location }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                {{-- Tests Requested — full width band --}}
                <div class="px-8 py-6 {{ ($submission->special_instructions || $submission->client_notes) ? 'border-b border-gray-100' : '' }}">
                    <p class="rs-section-title mb-3">Tests Requested</p>
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

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <p class="rs-meta-label mb-1.5">Microbiological</p>
                            @if(count($micro))
                                <div class="flex flex-wrap gap-1">
                                    @foreach($micro as $t)
                                        <span class="inline-flex px-2 py-0.5 text-xs bg-purple-50 text-purple-700 rounded-full">{{ str_replace('_', ' ', $t) }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-gray-400">None requested.</p>
                            @endif
                        </div>
                        <div>
                            <p class="rs-meta-label mb-1.5">Chemical</p>
                            @if(count($chem))
                                <div class="flex flex-wrap gap-1">
                                    @foreach($chem as $t)
                                        <span class="inline-flex px-2 py-0.5 text-xs bg-blue-50 text-blue-700 rounded-full">{{ str_replace('_', ' ', $t) }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-gray-400">None requested.</p>
                            @endif
                        </div>
                    </div>

                    @if($submission->tests_other)
                        <p class="text-xs text-gray-500 mt-3 bg-gray-50 rounded p-2">
                            <span class="font-medium">Other:</span> {{ $submission->tests_other }}
                        </p>
                    @endif
                </div>

                {{-- Instructions — full width band (only if present) --}}
                @if($submission->special_instructions || $submission->client_notes)
                    <div class="px-8 py-6">
                        <p class="rs-section-title mb-3">Instructions</p>
                        @if($submission->special_instructions)
                            <p class="text-sm text-gray-700">{{ $submission->special_instructions }}</p>
                        @endif
                        @if($submission->client_notes)
                            <p class="text-xs text-gray-500 mt-2">{{ $submission->client_notes }}</p>
                        @endif
                    </div>
                @endif

            </div>

            {{-- ════════ ACTION AREA (full width, below the record) ════════ --}}

            @if($submission->status === 'submitted')
                {{-- ── Receive Form ──────────────────────────────────── --}}
                <form method="POST"
                      action="{{ route('reception.submissions.receive', $submission->id) }}"
                      x-data="sampleReceiveForm()"
                      @submit.prevent="submitForm($el)">
                    @csrf

                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-5">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="rs-section-title">Log Physical Sample Arrival</h3>
                            <p class="text-xs text-gray-400 mt-1">
                                Enter details for each physical sample received. Add a row per specimen.
                            </p>
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
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Common Name *</label>
                                            <input type="text"
                                                   :name="`samples[${index}][common_name]`"
                                                   x-model="row.common_name"
                                                   placeholder="e.g. Yellowfin Tuna"
                                                   required
                                                   class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"/>
                                        </div>

                                        {{-- Scientific Name --}}
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Scientific Name</label>
                                            <input type="text"
                                                   :name="`samples[${index}][scientific_name]`"
                                                   x-model="row.scientific_name"
                                                   placeholder="e.g. Thunnus albacares"
                                                   class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"/>
                                        </div>

                                        {{-- Sampling Date --}}
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Sampling Date *</label>
                                            <input type="date"
                                                   :name="`samples[${index}][sampling_date]`"
                                                   x-model="row.sampling_date"
                                                   :max="today"
                                                   required
                                                   class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"/>
                                        </div>

                                        {{-- Quantity --}}
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Quantity *</label>
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
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Notes</label>
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
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 text-center">
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

                @if($samples->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mt-5">
                        <div class="px-5 py-3.5 border-b border-gray-100">
                            <h3 class="rs-section-title">Logged Samples</h3>
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
                                            <td class="px-5 py-3"><x-kstl.status-badge :status="$sample->status" /></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            @elseif($submission->status === 'rejected')
                {{-- ── Consent pending — awaiting client decision ── --}}
                <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
                    <div class="flex items-start gap-3 mb-5">
                        <svg class="w-6 h-6 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-red-800">Sample(s) Rejected — Awaiting Client Decision</p>
                            <p class="text-sm text-red-700 mt-0.5">
                                Send the client a consent request so they can choose to proceed with testing or cancel the submission.
                            </p>
                        </div>
                    </div>

                    @foreach($samples->filter(fn($s) => $s->assessment?->outcome === 'rejected') as $sample)
                        @php $a = $sample->assessment; @endphp
                        <div class="bg-white border border-red-100 rounded-xl p-4 mb-3 last:mb-0">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $sample->common_name }}</p>
                                    <p class="text-xs font-mono text-gray-400">{{ $sample->sample_code }}</p>
                                </div>
                                @if($a->client_decision)
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full
                                        {{ $a->client_decision === 'consent_to_proceed' ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $a->client_decision === 'consent_to_proceed' ? 'Client: Proceed' : 'Client: Cancelled' }}
                                    </span>
                                @elseif($a->consent_token)
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-50 text-amber-700">
                                        Awaiting Response
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">
                                        Not Notified Yet
                                    </span>
                                @endif
                            </div>

                            @if($a->rejection_reason)
                                <p class="text-xs text-red-600 mb-3 italic">Reason: {{ $a->rejection_reason }}</p>
                            @endif

                            @if(! $a->client_decision)
                                @if($a->consent_token)
                                    {{-- Consent link already generated — show copyable URL + resend --}}
                                    <div class="mb-3">
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Consent Link (share with client)</label>
                                        <div class="flex gap-2">
                                            <input type="text"
                                                   id="consent-link-{{ $a->id }}"
                                                   readonly
                                                   value="{{ route('client.consent.show', $a->consent_token) }}"
                                                   class="flex-1 text-xs font-mono border border-gray-200 rounded-lg px-3 py-2 bg-gray-50 text-gray-700 focus:outline-none"/>
                                            <button type="button"
                                                    onclick="navigator.clipboard.writeText('{{ route('client.consent.show', $a->consent_token) }}'); this.textContent='Copied!'; setTimeout(()=>this.textContent='Copy',2000)"
                                                    class="shrink-0 px-3 py-2 text-xs font-medium bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                                                Copy
                                            </button>
                                        </div>
                                        @if($a->consent_token_expires_at)
                                            <p class="text-xs text-gray-400 mt-1">Link expires {{ \Carbon\Carbon::parse($a->consent_token_expires_at)->format('d M Y') }}</p>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ route('reception.assessments.notify', $a->id) }}" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            Resend Email
                                        </button>
                                    </form>
                                @else
                                    {{-- No token yet — send the first notification --}}
                                    <form method="POST" action="{{ route('reception.assessments.notify', $a->id) }}">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            Send Consent Request to Client
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>

            @elseif($submission->status === 'accepted')
                {{-- All samples passed — send to testing --}}
                <div class="bg-green-50 border border-green-200 rounded-2xl p-6">
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

                @if($samples->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mt-5">
                        <div class="px-5 py-3.5 border-b border-gray-100">
                            <h3 class="rs-section-title">Accepted Samples ({{ $samples->count() }})</h3>
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
                                            <td class="px-5 py-3"><x-kstl.status-badge :status="$sample->status" /></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            @elseif($submission->status === 'consent_to_proceed')
                {{-- Client consented — send to testing --}}
                <div class="bg-orange-50 border border-orange-200 rounded-2xl p-6">
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

                @if($samples->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mt-5">
                        <div class="px-5 py-3.5 border-b border-gray-100">
                            <h3 class="rs-section-title">Samples</h3>
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
                                            <td class="px-5 py-3"><x-kstl.status-badge :status="$sample->status" /></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

            @else
                {{-- Any other status — read only --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
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
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mt-5">
                        <div class="px-5 py-3.5 border-b border-gray-100">
                            <h3 class="rs-section-title">Samples ({{ $samples->count() }})</h3>
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
                                            <td class="px-5 py-3"><x-kstl.status-badge :status="$sample->status" /></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            {{-- ════════ ASSESSMENT RECORD (shown once assessments exist) ════════ --}}
            @php
                $assessedSamples = $samples->filter(fn($s) => $s->assessment !== null);
            @endphp
            @if($assessedSamples->isNotEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="rs-section-title">Assessment Record</h3>
                            <p class="text-xs text-gray-400 mt-1">Physical sample inspection results logged by reception.</p>
                        </div>
                        @php
                            $allPassed = $assessedSamples->every(fn($s) => $s->assessment->outcome === 'accepted');
                            $anyFailed = $assessedSamples->some(fn($s)  => $s->assessment->outcome === 'rejected');
                        @endphp
                        @if($allPassed)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-green-50 text-green-700 ring-1 ring-green-600/20">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                All Accepted
                            </span>
                        @elseif($anyFailed)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full bg-red-50 text-red-700 ring-1 ring-red-600/20">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                Rejected
                            </span>
                        @endif
                    </div>

                    @foreach($assessedSamples as $sample)
                        @php $a = $sample->assessment; @endphp
                        <div class="px-6 py-5 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">

                            {{-- Sample header --}}
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $sample->common_name }}</p>
                                    <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $sample->sample_code }}</p>
                                </div>
                                <div class="flex items-center gap-3 text-right">
                                    @if($a->outcome === 'accepted')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold bg-green-50 text-green-700 rounded-full">Accepted</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold bg-red-50 text-red-700 rounded-full">Rejected</span>
                                    @endif
                                    @if($a->assessedBy)
                                        <div class="text-right">
                                            <p class="text-xs text-gray-500">{{ $a->assessedBy->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $a->assessed_at?->format('d M Y H:i') ?? $a->created_at->format('d M Y H:i') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Criteria grid --}}
                            @php
                                $criteria = [
                                    'Temperature'          => [$a->temperature_ok, $a->temperature_notes],
                                    'Storage Condition'    => [$a->storage_ok,     $a->storage_notes],
                                    'Transport Condition'  => [$a->transport_ok,   $a->transport_notes],
                                    'Packaging Integrity'  => [$a->packaging_ok,   $a->packaging_notes],
                                    'Colour / Appearance'  => [$a->colour_ok,      $a->colour_notes],
                                    'Weight / Quantity'    => [$a->weight_ok,      $a->weight_notes],
                                ];
                            @endphp
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($criteria as $label => [$pass, $notes])
                                    <div class="rounded-lg border {{ $pass ? 'border-green-100 bg-green-50/40' : 'border-red-100 bg-red-50/40' }} px-3 py-2.5">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-xs font-medium text-gray-700">{{ $label }}</span>
                                            @if($pass)
                                                <span class="text-xs font-semibold text-green-600 flex items-center gap-0.5">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                    Pass
                                                </span>
                                            @else
                                                <span class="text-xs font-semibold text-red-600 flex items-center gap-0.5">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    Fail
                                                </span>
                                            @endif
                                        </div>
                                        @if($notes)
                                            <p class="text-xs text-gray-500 mt-1 leading-snug">{{ $notes }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            {{-- Additional observations --}}
                            @if($a->additional_observations)
                                <div class="mt-3 bg-gray-50 rounded-lg px-3 py-2.5 text-xs text-gray-600">
                                    <span class="font-medium text-gray-700">Additional Observations:</span>
                                    {{ $a->additional_observations }}
                                </div>
                            @endif

                            {{-- Rejection reason --}}
                            @if($a->rejection_reason)
                                <div class="mt-3 bg-red-50 border border-red-100 rounded-lg px-3 py-2.5 text-xs text-red-700">
                                    <span class="font-semibold">Rejection Reason:</span>
                                    {{ $a->rejection_reason }}
                                </div>
                            @endif

                        </div>
                    @endforeach
                </div>
            @endif

            @endif

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