{{-- resources/views/kstl/client/consent/show.blade.php --}}
{{-- Public page — no x-app-layout, no navigation --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Assessment Decision — KSTL</title>
    @vite(['resources/css/app.css'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- Header --}}
    <div class="bg-blue-900 text-white py-4 px-6">
        <div class="max-w-2xl mx-auto flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-700 rounded-lg flex items-center justify-center text-xs font-bold">KL</div>
            <div>
                <p class="font-semibold text-sm">Kiribati Seafood Toxicology Laboratory</p>
                <p class="text-blue-300 text-xs">Sample Assessment — Client Response</p>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-10 space-y-6">

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        {{-- Intro --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-sm text-gray-500 mb-1">Reference</p>
            <p class="text-xl font-bold text-gray-900 font-mono mb-4">{{ $submission->reference_number }}</p>

            <p class="text-gray-700 text-sm leading-relaxed">
                Dear <strong>{{ $submission->client->responsible_officer_name ?? $submission->client->company_name }}</strong>,
                the sample listed below did not pass our reception assessment.
                Please review the findings and indicate your decision.
            </p>
        </div>

        {{-- Sample Info --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-100 bg-gray-50">
                <h3 class="text-sm font-medium text-gray-800">Sample Details</h3>
            </div>
            <dl class="px-5 py-4 space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Sample Code</dt>
                    <dd class="font-mono font-medium text-gray-800">{{ $sample->sample_code }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Common Name</dt>
                    <dd class="font-medium text-gray-800">{{ $sample->common_name }}</dd>
                </div>
                @if($sample->scientific_name)
                <div class="flex justify-between">
                    <dt class="text-gray-500">Scientific Name</dt>
                    <dd class="italic text-gray-700">{{ $sample->scientific_name }}</dd>
                </div>
                @endif
                <div class="flex justify-between">
                    <dt class="text-gray-500">Quantity</dt>
                    <dd class="text-gray-700">{{ $sample->quantity }} {{ $sample->quantity_unit }}</dd>
                </div>
            </dl>
        </div>

        {{-- Assessment Criteria --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-100 bg-gray-50">
                <h3 class="text-sm font-medium text-gray-800">Assessment Results</h3>
            </div>
            <div class="px-5 py-4">
                @php
                    $criteria = [
                        'Temperature'  => $assessment->temperature_ok,
                        'Storage'      => $assessment->storage_ok,
                        'Transport'    => $assessment->transport_ok,
                        'Packaging'    => $assessment->packaging_ok,
                        'Colour'       => $assessment->colour_ok,
                        'Odour'        => $assessment->odour_ok,
                        'Weight'       => $assessment->weight_ok,
                    ];
                @endphp
                <div class="grid grid-cols-2 gap-2 mb-4">
                    @foreach($criteria as $label => $passed)
                        <div class="flex items-center gap-2 text-sm py-1">
                            @if($passed)
                                <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-600">{{ $label }}</span>
                            @else
                                <svg class="w-4 h-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a1 1 0 112 0v4a1 1 0 11-2 0V9zm1-5a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-red-700 font-medium">{{ $label }} — FAILED</span>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($assessment->rejection_reason)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <p class="text-xs font-medium text-red-700 mb-1">Reason for Rejection</p>
                        <p class="text-sm text-red-800">{{ $assessment->rejection_reason }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Decision Form --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden"
             x-data="{ decision: '' }">
            <div class="px-5 py-3.5 border-b border-gray-100 bg-gray-50">
                <h3 class="text-sm font-medium text-gray-800">Your Decision</h3>
                <p class="text-xs text-gray-400 mt-0.5">Please select one option and confirm.</p>
            </div>
            <div class="px-5 py-5 space-y-4">

                <form method="POST" action="{{ route('client.consent.store', $token) }}">
                    @csrf

                    {{-- Option A --}}
                    <label class="flex items-start gap-4 p-4 rounded-xl border-2 cursor-pointer transition mb-3"
                           :class="decision === 'consent_to_proceed' ? 'border-amber-400 bg-amber-50' : 'border-gray-200 hover:border-gray-300'">
                        <input type="radio" name="decision" value="consent_to_proceed"
                               x-model="decision"
                               class="mt-1 text-amber-600 focus:ring-amber-500">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Proceed with Testing</p>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                I acknowledge the assessment findings but request that testing proceeds.
                                I understand results will include a note about the sample condition.
                            </p>
                        </div>
                    </label>

                    {{-- Option B --}}
                    <label class="flex items-start gap-4 p-4 rounded-xl border-2 cursor-pointer transition mb-5"
                           :class="decision === 'confirm_rejection' ? 'border-red-400 bg-red-50' : 'border-gray-200 hover:border-gray-300'">
                        <input type="radio" name="decision" value="confirm_rejection"
                               x-model="decision"
                               class="mt-1 text-red-600 focus:ring-red-500">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Cancel Submission</p>
                            <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                I accept the rejection. I will arrange to submit a new sample.
                                No testing will be conducted on this sample.
                            </p>
                        </div>
                    </label>

                    <button type="submit"
                            x-bind:disabled="!decision"
                            x-bind:class="!decision
                                ? 'bg-gray-100 text-gray-400 cursor-not-allowed'
                                : (decision === 'consent_to_proceed'
                                    ? 'bg-amber-600 hover:bg-amber-700 text-white'
                                    : 'bg-red-600 hover:bg-red-700 text-white')"
                            class="w-full py-3 text-sm font-semibold rounded-xl transition"
                            onclick="return confirm('Are you sure? This decision cannot be changed.')">
                        <span x-text="!decision
                            ? 'Please select an option above'
                            : (decision === 'consent_to_proceed'
                                ? 'Confirm: Proceed with Testing'
                                : 'Confirm: Cancel Submission')">
                        </span>
                    </button>
                </form>

                <p class="text-xs text-gray-400 text-center">
                    This link expires {{ $assessment->consent_token_expires_at?->format('d M Y \a\t H:i') }}.
                    Contact <a href="mailto:{{ config('mail.from.address') }}" class="underline">{{ config('mail.from.address') }}</a> if you need help.
                </p>
            </div>
        </div>

    </div>

    @vite(['resources/js/app.js'])
</body>
</html>