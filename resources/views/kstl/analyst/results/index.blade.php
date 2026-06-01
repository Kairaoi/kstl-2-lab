{{-- resources/views/kstl/analyst/results/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Authorised Results</h2>
                <p class="text-sm text-gray-500 mt-0.5">Read-only — final reports signed off by the Director</p>
            </div>
            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 px-3 py-1.5 rounded-full">
                <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span>
                Analyst
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">Submissions with authorised results</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Authorised and completed reports</p>
                    </div>
                    <span class="text-xs text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                        {{ $submissions->count() }} total
                    </span>
                </div>

                @if($submissions->isEmpty())
                    <div class="px-6 py-16 text-center">
                        <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm font-medium text-gray-400">No authorised results yet</p>
                        <p class="text-xs text-gray-300 mt-1">Reports appear here once the Director authorises them.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Reference</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Client</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Outcome</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Status</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Authorised</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($submissions as $submission)
                                    @php
                                        $outcome = $submission->result?->overall_outcome;
                                        $outcomeClasses = [
                                            'pass'         => 'bg-green-50 text-green-700',
                                            'fail'         => 'bg-red-50 text-red-700',
                                            'inconclusive' => 'bg-amber-50 text-amber-700',
                                        ][$outcome] ?? 'bg-gray-100 text-gray-500';
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 font-mono text-xs font-medium text-gray-700">
                                            {{ $submission->reference_number }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-medium text-gray-800">{{ $submission->client->company_name ?? '—' }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($outcome)
                                                <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full capitalize {{ $outcomeClasses }}">
                                                    {{ $outcome }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <x-kstl.status-badge :status="$submission->status" />
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-500">
                                            @if($submission->result?->authorised_at)
                                                {{ $submission->result->authorised_at->format('d M Y') }}
                                                <p class="text-gray-400">{{ $submission->result->authorisedBy?->name }}</p>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('analyst.results.show', $submission->id) }}"
                                               class="text-xs text-gray-600 px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                                View report
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>