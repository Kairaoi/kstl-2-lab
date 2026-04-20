{{-- resources/views/kstl/client/consent/confirmed.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decision Recorded — KSTL</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen">

    <div class="bg-blue-900 text-white py-4 px-6">
        <div class="max-w-2xl mx-auto flex items-center gap-3">
            <div class="w-8 h-8 bg-blue-700 rounded-lg flex items-center justify-center text-xs font-bold">KL</div>
            <div>
                <p class="font-semibold text-sm">Kiribati Seafood Toxicology Laboratory</p>
                <p class="text-blue-300 text-xs">Sample Assessment — Client Response</p>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 py-16 text-center">

        @if($assessment->client_decision === 'consent_to_proceed')
            <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-3">Decision Recorded</h1>
            <p class="text-gray-600 mb-2">
                Thank you. We have recorded your consent to proceed with testing
                despite the assessment findings.
            </p>
            <p class="text-gray-500 text-sm">
                Our lab team will commence testing and you will be notified when results are ready.
            </p>
        @else
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-3">Rejection Confirmed</h1>
            <p class="text-gray-600 mb-2">
                Thank you. We have recorded your decision to cancel this submission.
            </p>
            <p class="text-gray-500 text-sm">
                You are welcome to resubmit with a fresh sample. Please contact us if you need assistance.
            </p>
        @endif

        <div class="mt-8 bg-white rounded-xl border border-gray-200 p-5 text-left text-sm">
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Reference</span>
                <span class="font-mono font-medium">{{ $assessment->sample->submission->reference_number }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Sample</span>
                <span class="font-medium">{{ $assessment->sample->common_name }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-gray-100">
                <span class="text-gray-500">Decision</span>
                <span class="font-medium {{ $assessment->client_decision === 'consent_to_proceed' ? 'text-amber-700' : 'text-red-700' }}">
                    {{ $assessment->client_decision === 'consent_to_proceed' ? 'Consent to Proceed' : 'Confirmed Rejection' }}
                </span>
            </div>
            <div class="flex justify-between py-2">
                <span class="text-gray-500">Recorded</span>
                <span class="text-gray-700">{{ $assessment->client_decision_at?->format('d M Y \a\t H:i') }}</span>
            </div>
        </div>

        <p class="mt-8 text-sm text-gray-400">
            Questions? Contact us at
            <a href="mailto:{{ config('mail.from.address') }}" class="text-blue-600 underline">
                {{ config('mail.from.address') }}
            </a>
        </p>

    </div>

</body>
</html>