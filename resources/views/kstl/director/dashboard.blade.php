{{-- resources/views/kstl/director/dashboard.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Director Dashboard</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ now()->format('l, d F Y') }}</p>
            </div>
            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-teal-700 bg-teal-50 px-3 py-1.5 rounded-full">
                <span class="w-1.5 h-1.5 bg-teal-500 rounded-full animate-pulse"></span>
                Director
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg flex items-center gap-3">
                    <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('warning'))
                <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-lg flex items-center gap-3">
                    <svg class="w-4 h-4 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-amber-800">{{ session('warning') }}</p>
                </div>
            @endif

            {{-- ── Summary Cards ─────────────────────────────────── --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Awaiting Authorisation</span>
                        <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">{{ $pending->count() }}</p>
                    <p class="text-xs text-gray-400 mt-1">Need your sign-off</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Flagged Tests</span>
                        <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">{{ $flagged }}</p>
                    <p class="text-xs text-gray-400 mt-1">Anomalous results</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 p-5">
                    <div class="flex items-start justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Authorised Today</span>
                        <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-900">{{ $authorised_today }}</p>
                    <p class="text-xs text-gray-400 mt-1">Signed off today</p>
                </div>

            </div>

            {{-- ── Pending Agreements ─────────────────────────────── --}}
            @php
                $pendingAgreements = \App\Models\Kstl\Client::whereNotNull('service_agreement_signed_at')
                    ->whereNull('director_signed_at')->count();
            @endphp
            @if($pendingAgreements > 0)
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        <p class="text-sm font-medium text-amber-800">
                            {{ $pendingAgreements }} service agreement{{ $pendingAgreements !== 1 ? 's' : '' }} awaiting your countersignature
                        </p>
                    </div>
                    <a href="{{ route('director.agreements.index') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-600 text-white text-xs font-medium rounded-lg hover:bg-amber-700 transition">
                        Review →
                    </a>
                </div>
            @endif

            {{-- ── Pending Authorisation Queue ───────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-medium text-gray-800">Awaiting Your Authorisation</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Review test results and sign off each submission</p>
                </div>

                @if($pending->isEmpty())
                    <div class="px-6 py-16 text-center">
                        <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-medium text-gray-400">No submissions pending authorisation</p>
                        <p class="text-xs text-gray-300 mt-1">All results have been signed off.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Reference</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Client</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Sample</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Tests</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Priority</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Flagged</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($pending as $submission)
                                    @php
                                        $hasFlagged = $submission->samples
                                            ->flatMap->sampleTests
                                            ->where('status', 'flagged')
                                            ->count();
                                        $testCount = $submission->samples
                                            ->flatMap->sampleTests
                                            ->count();
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition {{ $hasFlagged ? 'bg-red-50/20' : '' }}">
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-xs font-medium text-gray-700">
                                                {{ $submission->reference_number }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-gray-800 text-sm">{{ $submission->client->company_name ?? '—' }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $submission->client->responsible_officer_name ?? '' }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $submission->sample_name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-700">{{ $testCount }} test{{ $testCount !== 1 ? 's' : '' }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $pc = ['routine' => 'bg-gray-100 text-gray-600', 'urgent' => 'bg-amber-50 text-amber-700', 'emergency' => 'bg-red-50 text-red-700'];
                                            @endphp
                                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full capitalize {{ $pc[$submission->priority ?? 'routine'] }}">
                                                {{ $submission->priority ?? 'Routine' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($hasFlagged)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs bg-red-50 text-red-700 rounded-full font-medium">
                                                    ⚑ {{ $hasFlagged }} flagged
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('director.submissions.show', $submission->id) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-teal-600 text-white text-xs font-medium rounded-lg hover:bg-teal-700 transition">
                                                Review →
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>


            {{-- ── Recently Authorised History ─────────────────────── --}}
            <div class="mt-2 bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-800">Authorisation History</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Recently authorised and completed submissions — last 20</p>
                    </div>
                    <span class="text-xs text-gray-400 bg-gray-50 px-3 py-1 rounded-full border border-gray-100">
                        {{ $history->count() }} records
                    </span>
                </div>

                @if($history->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <svg class="w-8 h-8 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-gray-400">No authorised results yet</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Reference</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Client</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Sample</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Outcome</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Authorised By</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">When</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($history as $submission)
                                    @php
                                        $result  = $submission->result;
                                        $outcome = $result?->overall_outcome ?? 'pending';
                                        $outcomeColors = [
                                            'pass'         => 'bg-green-50 text-green-700',
                                            'fail'         => 'bg-red-50 text-red-700',
                                            'inconclusive' => 'bg-yellow-50 text-yellow-700',
                                        ];
                                        $outcomeColor = $outcomeColors[$outcome] ?? 'bg-gray-100 text-gray-400';
                                        $statusColors = [
                                            'authorised' => 'bg-green-50 text-green-700',
                                            'completed'  => 'bg-green-100 text-green-800',
                                        ];
                                        $statusColor = $statusColors[$submission->status] ?? 'bg-gray-100 text-gray-500';
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-3.5 font-mono text-xs font-semibold text-gray-700">
                                            {{ $submission->reference_number }}
                                        </td>
                                        <td class="px-6 py-3.5 text-xs">
                                            <p class="font-medium text-gray-800">{{ $submission->client->company_name ?? '—' }}</p>
                                            <p class="text-gray-400">{{ $submission->client->responsible_officer_name ?? '' }}</p>
                                        </td>
                                        <td class="px-6 py-3.5 text-xs text-gray-700">
                                            {{ $submission->sample_name }}
                                            <span class="text-gray-400 capitalize">· {{ $submission->sample_type }}</span>
                                        </td>
                                        <td class="px-6 py-3.5">
                                            @if($result)
                                                <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full capitalize {{ $outcomeColor }}">
                                                    {{ $outcome === 'pass' ? '✓ Pass' : ($outcome === 'fail' ? '✗ Fail' : ucfirst($outcome)) }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3.5 text-xs text-gray-600">
                                            {{ $result?->authorisedBy?->name ?? '—' }}
                                            @if($result?->authorised_at)
                                                <p class="text-gray-400">{{ $result->authorised_at->format('d M Y') }}</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-3.5">
                                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full capitalize {{ $statusColor }}">
                                                {{ ucfirst($submission->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3.5 text-xs text-gray-400">
                                            {{ $submission->updated_at->diffForHumans() }}
                                            <p class="text-gray-300">{{ $submission->updated_at->format('d M Y H:i') }}</p>
                                        </td>
                                        <td class="px-6 py-3.5 text-right">
                                            <a href="{{ route('director.submissions.show', $submission->id) }}"
                                               class="text-xs text-gray-500 px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        <div class="pb-12"></div>
    </div>
</x-app-layout>