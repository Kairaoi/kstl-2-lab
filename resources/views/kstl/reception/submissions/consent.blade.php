{{-- resources/views/kstl/reception/submissions/consent.blade.php --}}
{{--
    Shown when a submission status = 'rejected'.
    Reception contacts the client, records their decision:
      - consent_to_proceed  → sample moves to testing despite issues
      - confirm_rejection   → sample is formally rejected, no testing
--}}

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
                        Client Consent — {{ $submission->reference_number }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Record client decision for rejected sample(s)
                    </p>
                </div>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20">
                Awaiting Client Decision
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

            {{-- ── Instructions Banner ──────────────────────────────── --}}
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-amber-800">Action Required — Contact Client</p>
                        <p class="text-sm text-amber-700 mt-1">
                            One or more samples failed the assessment. Contact
                            <span class="font-medium">{{ $submission->client->responsible_officer_name ?? $submission->client->company_name }}</span>
                            at <span class="font-medium">{{ $submission->client->company_phone ?? $submission->client->user->email ?? '—' }}</span>
                            to inform them of the rejection and record their decision below.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ── Left: Submission Summary ──────────────────────── --}}
                <div class="space-y-5">

                    {{-- Client contact card --}}
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100">
                            <h3 class="text-sm font-medium text-gray-800">Client Contact</h3>
                        </div>
                        <dl class="px-5 py-4 space-y-3 text-sm">
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Company</dt>
                                <dd class="font-medium text-gray-800 mt-0.5">{{ $submission->client->company_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Officer</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $submission->client->responsible_officer_name ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Phone</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $submission->client->company_phone ?? '—' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Email</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $submission->client->user->email ?? '—' }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Submission ref --}}
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-gray-100">
                            <h3 class="text-sm font-medium text-gray-800">Submission</h3>
                        </div>
                        <dl class="px-5 py-4 space-y-3 text-sm">
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Reference</dt>
                                <dd class="font-mono font-medium text-gray-800 mt-0.5">{{ $submission->reference_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Sample</dt>
                                <dd class="text-gray-700 mt-0.5">{{ $submission->sample_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-gray-400 uppercase tracking-wide">Submitted</dt>
                                <dd class="text-gray-700 mt-0.5">
                                    {{ $submission->submitted_at?->format('d M Y') ?? '—' }}
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
                        </dl>
                    </div>

                </div>

                {{-- ── Right: Rejected Samples + Consent Forms ──────── --}}
                <div class="lg:col-span-2 space-y-5">

                    @foreach($samples as $sample)
                        @if($sample->status === 'rejected' && $sample->assessment)

                            <div class="bg-white rounded-xl border border-red-100 overflow-hidden">

                                {{-- Sample header --}}
                                <div class="px-6 py-4 bg-red-50 border-b border-red-100 flex items-center justify-between">
                                    <div>
                                        <h3 class="font-medium text-gray-900">
                                            {{ $sample->sample_code }} — {{ $sample->common_name }}
                                        </h3>
                                        <p class="text-xs text-gray-500 mt-0.5">
                                            {{ $sample->quantity }} {{ $sample->quantity_unit }}
                                            @if($sample->scientific_name)
                                                · <em>{{ $sample->scientific_name }}</em>
                                            @endif
                                        </p>
                                    </div>
                                    <span class="inline-flex px-2.5 py-1 text-xs font-medium bg-red-100 text-red-700 rounded-full">
                                        Rejected
                                    </span>
                                </div>

                                <div class="px-6 py-5 space-y-5">

                                    {{-- Assessment summary --}}
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-3">Assessment Results</p>
                                        @php
                                            $a = $sample->assessment;
                                            $criteria = [
                                                'Temperature'  => $a->temperature_ok,
                                                'Storage'      => $a->storage_ok,
                                                'Transport'    => $a->transport_ok,
                                                'Packaging'    => $a->packaging_ok,
                                                'Colour'       => $a->colour_ok,
                                                'Odour'        => $a->odour_ok,
                                                'Weight'       => $a->weight_ok,
                                            ];
                                        @endphp
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                            @foreach($criteria as $label => $passed)
                                                <div class="flex items-center gap-1.5 text-xs">
                                                    @if($passed)
                                                        <svg class="w-3.5 h-3.5 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                                                        </svg>
                                                        <span class="text-gray-600">{{ $label }}</span>
                                                    @else
                                                        <svg class="w-3.5 h-3.5 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a1 1 0 112 0v4a1 1 0 11-2 0V9zm1-5a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                                                        </svg>
                                                        <span class="text-red-700 font-medium">{{ $label }}</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>

                                        @if($a->rejection_reason)
                                            <div class="mt-3 bg-red-50 rounded-lg p-3">
                                                <p class="text-xs font-medium text-red-700 mb-1">Rejection Reason</p>
                                                <p class="text-sm text-red-800">{{ $a->rejection_reason }}</p>
                                            </div>
                                        @endif

                                        @if($a->additional_observations)
                                            <div class="mt-2 bg-gray-50 rounded-lg p-3">
                                                <p class="text-xs font-medium text-gray-500 mb-1">Additional Observations</p>
                                                <p class="text-sm text-gray-700">{{ $a->additional_observations }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Client Decision --}}
                                    @if($a->client_decision)
                                        {{-- Already recorded --}}
                                        <div class="rounded-lg border p-4
                                            {{ $a->client_decision === 'consent_to_proceed'
                                                ? 'bg-orange-50 border-orange-200'
                                                : 'bg-gray-50 border-gray-200' }}">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-semibold
                                                        {{ $a->client_decision === 'consent_to_proceed'
                                                            ? 'text-orange-800'
                                                            : 'text-gray-700' }}">
                                                        @if($a->client_decision === 'consent_to_proceed')
                                                            ✓ Client consented to proceed despite rejection
                                                        @else
                                                            ✗ Client confirmed rejection — no testing
                                                        @endif
                                                    </p>
                                                    <p class="text-xs text-gray-400 mt-0.5">
                                                        Recorded {{ $a->client_decision_at?->format('d M Y \a\t H:i') ?? '—' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                    @else
                                        {{-- Send Email Button --}}
                                        <div class="mb-5 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                            <p class="text-sm font-medium text-blue-800 mb-1">Notify Client by Email</p>
                                            <p class="text-xs text-gray-500 mb-3">
                                                Send an email to <strong>{{ $submission->client->user->email ?? '—' }}</strong>
                                                with a secure link for the client to record their own decision.
                                            </p>
                                            @if($a->consent_notified_at)
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-green-700">
                                                        ✓ Email sent {{ $a->consent_notified_at->format('d M Y \a\t H:i') }}
                                                    </span>
                                                    <form method="POST" action="{{ route('reception.assessments.notify', $a->id) }}">
                                                        @csrf
                                                        <button type="submit" class="text-xs text-blue-600 hover:underline">Resend</button>
                                                    </form>
                                                </div>
                                            @else
                                                <form method="POST" action="{{ route('reception.assessments.notify', $a->id) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                        </svg>
                                                        Send Email to Client
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        <div class="relative mb-4">
                                            <div class="absolute inset-0 flex items-center">
                                                <div class="w-full border-t border-gray-200"></div>
                                            </div>
                                            <div class="relative flex justify-center text-xs">
                                                <span class="bg-white px-3 text-gray-400">or record manually after phone/in-person contact</span>
                                            </div>
                                        </div>

                                        {{-- Consent form --}}
                                        <div>
                                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-3">
                                                Record Client Decision
                                            </p>

                                            <form method="POST"
                                                  action="{{ route('reception.assessments.consent', $a->id) }}"
                                                  x-data="{ decision: '' }">
                                                @csrf

                                                <div class="space-y-3 mb-4">

                                                    {{-- Option 1: Consent to proceed --}}
                                                    <label class="flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition"
                                                           :class="decision === 'consent_to_proceed'
                                                               ? 'border-orange-400 bg-orange-50'
                                                               : 'border-gray-200 hover:border-gray-300'">
                                                        <input type="radio"
                                                               name="decision"
                                                               value="consent_to_proceed"
                                                               x-model="decision"
                                                               class="mt-0.5 text-orange-600 focus:ring-orange-500">
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-800">
                                                                Consent to Proceed
                                                            </p>
                                                            <p class="text-xs text-gray-500 mt-0.5">
                                                                Client acknowledges the issue but requests testing continues.
                                                                Results will include a note about the assessment findings.
                                                            </p>
                                                        </div>
                                                    </label>

                                                    {{-- Option 2: Confirm rejection --}}
                                                    <label class="flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition"
                                                           :class="decision === 'confirm_rejection'
                                                               ? 'border-red-400 bg-red-50'
                                                               : 'border-gray-200 hover:border-gray-300'">
                                                        <input type="radio"
                                                               name="decision"
                                                               value="confirm_rejection"
                                                               x-model="decision"
                                                               class="mt-0.5 text-red-600 focus:ring-red-500">
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-800">
                                                                Confirm Rejection
                                                            </p>
                                                            <p class="text-xs text-gray-500 mt-0.5">
                                                                Client accepts the rejection. Sample will not be tested.
                                                                Client will need to resubmit with a new sample.
                                                            </p>
                                                        </div>
                                                    </label>

                                                </div>

                                                <button type="submit"
                                                        x-bind:disabled="!decision"
                                                        x-bind:class="decision
                                                            ? (decision === 'consent_to_proceed'
                                                                ? 'bg-orange-600 hover:bg-orange-700 text-white'
                                                                : 'bg-red-600 hover:bg-red-700 text-white')
                                                            : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                                                        class="w-full py-2.5 text-sm font-medium rounded-lg transition">
                                                    <span x-text="decision === 'consent_to_proceed'
                                                        ? 'Record: Consent to Proceed'
                                                        : (decision === 'confirm_rejection'
                                                            ? 'Record: Confirm Rejection'
                                                            : 'Select a decision above')">
                                                    </span>
                                                </button>

                                            </form>
                                        </div>
                                    @endif

                                </div>
                            </div>

                        @elseif($sample->status === 'accepted')
                            {{-- Show accepted samples briefly --}}
                            <div class="bg-white rounded-xl border border-green-100 px-6 py-4 flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ $sample->sample_code }} — {{ $sample->common_name }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $sample->quantity }} {{ $sample->quantity_unit }}</p>
                                </div>
                                <span class="inline-flex px-2.5 py-1 text-xs font-medium bg-green-50 text-green-700 rounded-full">
                                    Accepted ✓
                                </span>
                            </div>
                        @endif
                    @endforeach

                    {{-- Actions --}}
                    <div class="flex items-center justify-between pt-2 pb-8">
                        <a href="{{ route('reception.dashboard') }}">
                            <x-secondary-button>← Back to Dashboard</x-secondary-button>
                        </a>

                        @php
                            $pendingDecisions = $samples->filter(fn($s) =>
                                $s->status === 'rejected' && $s->assessment && !$s->assessment->client_decision
                            )->count();
                        @endphp

                        @if($pendingDecisions === 0)
                            @php
                                $hasConsent = $samples->contains(fn($s) =>
                                    $s->assessment && $s->assessment->client_decision === 'consent_to_proceed'
                                );
                            @endphp
                            @if($hasConsent && $submission->status !== 'testing')
                                <form method="POST"
                                      action="{{ route('reception.submissions.send-to-testing', $submission->id) }}">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('Send this submission to the testing queue?')"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                        Send to Testing Queue
                                    </button>
                                </form>
                            @elseif($submission->status === 'testing')
                                <span class="inline-flex items-center gap-2 text-sm text-indigo-700 font-medium">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                                    </svg>
                                    Sent to testing queue
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 text-sm text-green-700 font-medium">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                                    </svg>
                                    All decisions recorded
                                </span>
                            @endif
                        @else
                            <span class="text-xs text-amber-600">
                                {{ $pendingDecisions }} decision{{ $pendingDecisions !== 1 ? 's' : '' }} pending
                            </span>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>