{{-- resources/views/kstl/client/submissions/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('client.submissions.index') }}"
                   class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $submission->reference_number }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        Submitted {{ $submission->submitted_at?->format('d M Y \a\t H:i') ?? $submission->created_at->format('d M Y \a\t H:i') }}
                    </p>
                </div>
            </div>

            {{-- Status Badge + Actions --}}
            <div class="flex items-center gap-3">
                @php
                    $statusConfig = [
                        'submitted'              => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
                        'received'               => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                        'assessing'              => 'bg-purple-50 text-purple-700 ring-purple-600/20',
                        'accepted'               => 'bg-green-50 text-green-700 ring-green-600/20',
                        'rejected'               => 'bg-red-50 text-red-700 ring-red-600/20',
                        'consent_to_proceed'     => 'bg-orange-50 text-orange-700 ring-orange-600/20',
                        'testing'                => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
                        'awaiting_authorisation' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                        'authorised'             => 'bg-teal-50 text-teal-700 ring-teal-600/20',
                        'completed'              => 'bg-green-50 text-green-700 ring-green-600/20',
                        'cancelled'              => 'bg-gray-50 text-gray-500 ring-gray-500/20',
                    ];
                    $sc = $statusConfig[$submission->status] ?? 'bg-gray-50 text-gray-500 ring-gray-500/20';
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ring-1 ring-inset {{ $sc }}">
                    {{ $submission->status_label }}
                </span>

                @if($submission->isEditable())
                    <a href="{{ route('client.submissions.edit', $submission->id) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2a2 2 0 01.586-1.414z"/>
                        </svg>
                        Edit
                    </a>
                @endif

                @if($submission->isCancellable())
                    <form method="POST" action="{{ route('client.submissions.destroy', $submission->id) }}"
                          onsubmit="return confirm('Are you sure you want to cancel this submission?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition">
                            Cancel Submission
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a1 1 0 112 0v4a1 1 0 11-2 0V9zm1-5a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            {{-- Rejection Notice --}}
            @if($submission->isRejected())
                <div class="bg-red-50 border border-red-200 rounded-xl p-5">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a1 1 0 112 0v4a1 1 0 11-2 0V9zm1-5a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-red-800">Sample Assessment — Not Accepted</p>
                            <p class="text-sm text-red-700 mt-1">
                                The lab has flagged an issue with this submission.
                                @if($submission->lab_notes)
                                    Lab note: <em>{{ $submission->lab_notes }}</em>
                                @endif
                                Please contact the lab for further details.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Result Available --}}
            @if($submission->hasResult())
                <div class="bg-green-50 border border-green-200 rounded-xl p-5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-green-800">Test Results Available</p>
                            <p class="text-sm text-green-700">Your results have been authorised and are ready to view.</p>
                        </div>
                    </div>
                    <a href="{{ route('client.results.index') }}"
                       class="shrink-0 inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">
                        View Results
                    </a>
                </div>
            @endif

            {{-- ── Schedule 1: Client & Company ──────────────────────────────── --}}
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1 px-4 sm:px-0">
                    <h3 class="text-lg font-medium text-gray-900">Client Details</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Company and responsible officer information from Schedule 1.
                    </p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="bg-white shadow rounded-xl overflow-hidden">
                        <dl class="divide-y divide-gray-100">
                            <div class="px-6 py-4 grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-xs text-gray-400 uppercase tracking-wide">Company Name</dt>
                                    <dd class="text-sm font-medium text-gray-800 mt-1">{{ $client->company_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-400 uppercase tracking-wide">Address</dt>
                                    <dd class="text-sm text-gray-800 mt-1">{{ $client->address }}</dd>
                                </div>
                            </div>
                            <div class="px-6 py-4 grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-xs text-gray-400 uppercase tracking-wide">Responsible Officer</dt>
                                    <dd class="text-sm text-gray-800 mt-1">{{ $client->responsible_officer_name ?? '—' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-400 uppercase tracking-wide">Tel No.</dt>
                                    <dd class="text-sm text-gray-800 mt-1">{{ $client->company_phone ?? $client->responsible_officer_phone ?? '—' }}</dd>
                                </div>
                            </div>
                            <div class="px-6 py-4 grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-xs text-gray-400 uppercase tracking-wide">Email</dt>
                                    <dd class="text-sm text-gray-800 mt-1">{{ $client->responsible_officer_email ?? '—' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-400 uppercase tracking-wide">Date of Application</dt>
                                    <dd class="text-sm text-gray-800 mt-1">
                                        {{ $submission->application_date?->format('d M Y') ?? $submission->submitted_at?->format('d M Y') ?? '—' }}
                                    </dd>
                                </div>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <x-section-border/>

            {{-- ── Schedule 1: Testing Method & Sample Type ───────────────────── --}}
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1 px-4 sm:px-0">
                    <h3 class="text-lg font-medium text-gray-900">Testing Method & Sample</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Testing method requested, sample type, and transport method from Schedule 1.
                    </p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="bg-white shadow rounded-xl overflow-hidden">
                        <dl class="divide-y divide-gray-100">

                            {{-- Testing Method --}}
                            <div class="px-6 py-4">
                                <dt class="text-xs text-gray-400 uppercase tracking-wide mb-2">Testing Method Requested</dt>
                                <dd>
                                    @php
                                        $tests = is_array($submission->tests_requested)
                                            ? $submission->tests_requested
                                            : json_decode($submission->tests_requested ?? '[]', true) ?? [];

                                        $testLabels = [
                                            'total_plate_count'   => 'Total Plate Count (TPC)',
                                            'e_coli'              => 'E. coli',
                                            'salmonella'          => 'Salmonella',
                                            'listeria'            => 'Listeria monocytogenes',
                                            'coliforms'           => 'Coliforms',
                                            'heavy_metals'        => 'Heavy Metals (Hg, Pb, Cd, As)',
                                            'histamine'           => 'Histamine',
                                            'moisture_content'    => 'Moisture Content',
                                            'salt_content'        => 'Salt Content',
                                            'protein_content'     => 'Protein Content',
                                            'sensory_evaluation'  => 'Sensory Evaluation',
                                            'net_weight'          => 'Net Weight',
                                            'packaging_integrity' => 'Packaging Integrity',
                                        ];

                                        $microTests = array_filter($tests, fn($t) => in_array($t, ['total_plate_count','e_coli','salmonella','listeria','coliforms']));
                                        $chemTests  = array_filter($tests, fn($t) => in_array($t, ['heavy_metals','histamine','moisture_content','salt_content','protein_content']));
                                        $physTests  = array_filter($tests, fn($t) => in_array($t, ['sensory_evaluation','net_weight','packaging_integrity']));
                                    @endphp

                                    @if(count($tests))
                                        <div class="space-y-3">
                                            @if(count($microTests))
                                                <div>
                                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Microbiological</p>
                                                    <div class="flex flex-wrap gap-1.5">
                                                        @foreach($microTests as $test)
                                                            <span class="inline-flex px-2 py-1 text-xs bg-purple-50 text-purple-700 rounded-full">
                                                                {{ $testLabels[$test] ?? str_replace('_', ' ', $test) }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            @if(count($chemTests))
                                                <div>
                                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Chemical</p>
                                                    <div class="flex flex-wrap gap-1.5">
                                                        @foreach($chemTests as $test)
                                                            <span class="inline-flex px-2 py-1 text-xs bg-blue-50 text-blue-700 rounded-full">
                                                                {{ $testLabels[$test] ?? str_replace('_', ' ', $test) }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            @if(count($physTests))
                                                <div>
                                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Physical</p>
                                                    <div class="flex flex-wrap gap-1.5">
                                                        @foreach($physTests as $test)
                                                            <span class="inline-flex px-2 py-1 text-xs bg-green-50 text-green-700 rounded-full">
                                                                {{ $testLabels[$test] ?? str_replace('_', ' ', $test) }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">—</span>
                                    @endif

                                    @if($submission->tests_other)
                                        <div class="mt-2 text-sm text-gray-600 bg-gray-50 rounded p-2">
                                            <span class="text-xs font-medium text-gray-500">Other: </span>{{ $submission->tests_other }}
                                        </div>
                                    @endif
                                </dd>
                            </div>

                            {{-- Sample Type --}}
                            <div class="px-6 py-4 grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-xs text-gray-400 uppercase tracking-wide">Sample Type</dt>
                                    <dd class="text-sm text-gray-800 mt-1 capitalize">{{ $submission->sample_type }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-400 uppercase tracking-wide">Sample Transport Method</dt>
                                    <dd class="text-sm text-gray-800 mt-1">
                                        @php
                                            $transport = [
                                                'frozen'  => '❄️ Frozen',
                                                'chilled' => '🧊 Chill',
                                                'fresh'   => '🌿 Fresh',
                                            ];
                                        @endphp
                                        {{ $transport[$submission->transport_method] ?? ucfirst($submission->transport_method ?? '—') }}
                                    </dd>
                                </div>
                            </div>

                        </dl>
                    </div>
                </div>
            </div>

            <x-section-border/>

            {{-- ── Schedule 1: Sample Table ────────────────────────────────────── --}}
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1 px-4 sm:px-0">
                    <h3 class="text-lg font-medium text-gray-900">Sample Details</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Sampling date, common name, scientific name, and quantity — Schedule 1 sample table.
                    </p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="bg-white shadow rounded-xl overflow-hidden">

                        {{-- Sample table matching Schedule 1 layout --}}
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-100">
                                    <tr>
                                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Sampling Date</th>
                                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Common Name</th>
                                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Scientific Name</th>
                                        <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b border-gray-50">
                                        <td class="px-4 py-4 text-gray-700">
                                            {{ $submission->collected_at?->format('d M Y') ?? '—' }}
                                        </td>
                                        <td class="px-4 py-4 text-gray-800 font-medium">
                                            {{ $submission->sample_name }}
                                        </td>
                                        <td class="px-4 py-4 text-gray-600 italic">
                                            {{ $submission->scientific_name ?? '—' }}
                                        </td>
                                        <td class="px-4 py-4 text-gray-700">
                                            @if($submission->sample_quantity)
                                                {{ number_format($submission->sample_quantity, 2) }}
                                                {{ $submission->sample_quantity_unit }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Additional details --}}
                        @if($submission->sample_description || $submission->collection_location)
                            <dl class="divide-y divide-gray-100 border-t border-gray-100">
                                @if($submission->collection_location)
                                    <div class="px-6 py-3 flex justify-between gap-4">
                                        <dt class="text-xs text-gray-400 uppercase tracking-wide shrink-0">Collection Location</dt>
                                        <dd class="text-sm text-gray-700 text-right">{{ $submission->collection_location }}</dd>
                                    </div>
                                @endif
                                @if($submission->sample_description)
                                    <div class="px-6 py-3">
                                        <dt class="text-xs text-gray-400 uppercase tracking-wide mb-1">Description</dt>
                                        <dd class="text-sm text-gray-700">{{ $submission->sample_description }}</dd>
                                    </div>
                                @endif
                            </dl>
                        @endif
                    </div>
                </div>
            </div>

            <x-section-border/>

            {{-- ── Special Instructions ────────────────────────────────────────── --}}
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1 px-4 sm:px-0">
                    <h3 class="text-lg font-medium text-gray-900">Instructions & Priority</h3>
                    <p class="mt-1 text-sm text-gray-600">Handling requirements and deadlines.</p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="bg-white shadow rounded-xl overflow-hidden">
                        <dl class="divide-y divide-gray-100">
                            <div class="px-6 py-4 grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-xs text-gray-400 uppercase tracking-wide">Priority</dt>
                                    <dd class="mt-1">
                                        @php
                                            $priorityConfig = [
                                                'routine'   => ['bg-gray-100 text-gray-700',   'Routine'],
                                                'urgent'    => ['bg-amber-50 text-amber-700',   'Urgent'],
                                                'emergency' => ['bg-red-50 text-red-700',       'Emergency'],
                                            ];
                                            $pc = $priorityConfig[$submission->priority] ?? ['bg-gray-100 text-gray-600', ucfirst($submission->priority ?? '—')];
                                        @endphp
                                        <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full {{ $pc[0] }}">
                                            {{ $pc[1] }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-400 uppercase tracking-wide">Results Required By</dt>
                                    <dd class="text-sm text-gray-800 mt-1">
                                        {{ $submission->results_required_by?->format('d M Y') ?? 'No deadline specified' }}
                                    </dd>
                                </div>
                            </div>
                            @if($submission->special_instructions)
                                <div class="px-6 py-4">
                                    <dt class="text-xs text-gray-400 uppercase tracking-wide mb-1">Special Instructions</dt>
                                    <dd class="text-sm text-gray-700">{{ $submission->special_instructions }}</dd>
                                </div>
                            @endif
                            @if($submission->client_notes)
                                <div class="px-6 py-4">
                                    <dt class="text-xs text-gray-400 uppercase tracking-wide mb-1">Additional Notes</dt>
                                    <dd class="text-sm text-gray-700">{{ $submission->client_notes }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>

            <x-section-border/>

            {{-- ── Lab Assessment (read-only for client) ──────────────────────── --}}
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1 px-4 sm:px-0">
                    <h3 class="text-lg font-medium text-gray-900">Lab Assessment</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Status updates and feedback from the lab team. Updated by reception and analysts.
                    </p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="bg-white shadow rounded-xl overflow-hidden">
                        <dl class="divide-y divide-gray-100">
                            <div class="px-6 py-4 grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-xs text-gray-400 uppercase tracking-wide">Current Status</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ring-1 ring-inset {{ $sc }}">
                                            {{ $submission->status_label }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-gray-400 uppercase tracking-wide">Received By Lab</dt>
                                    <dd class="text-sm text-gray-800 mt-1">
                                        @if($submission->received_at)
                                            {{ $submission->received_at->format('d M Y \a\t H:i') }}
                                        @else
                                            <span class="text-gray-400">Awaiting physical receipt</span>
                                        @endif
                                    </dd>
                                </div>
                            </div>
                            <div class="px-6 py-4">
                                <dt class="text-xs text-gray-400 uppercase tracking-wide mb-1">Lab Notes</dt>
                                <dd class="text-sm text-gray-700">
                                    {{ $submission->lab_notes ?? 'No notes from the lab yet.' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            {{-- ── Declaration ─────────────────────────────────────────────────── --}}
            <div class="bg-white shadow rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">Declaration</h3>
                </div>
                <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm text-gray-600">
                    <div>
                        Submitted by
                        <span class="font-medium text-gray-800">{{ $submission->submitter_name ?? $user->name }}</span>
                        @if($submission->submitter_designation)
                            <span class="text-gray-400">({{ $submission->submitter_designation }})</span>
                        @endif
                        on behalf of
                        <span class="font-medium text-gray-800">{{ $client->company_name }}</span>.
                    </div>
                    <div class="text-xs text-gray-400 shrink-0">
                        {{ $submission->submitted_at?->format('d M Y H:i') ?? $submission->created_at->format('d M Y H:i') }}
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between pb-8">
                <a href="{{ route('client.submissions.index') }}">
                    <x-secondary-button>← Back to Submissions</x-secondary-button>
                </a>
                @if($submission->isEditable())
                    <a href="{{ route('client.submissions.edit', $submission->id) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2a2 2 0 01.586-1.414z"/>
                        </svg>
                        Edit Submission
                    </a>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>