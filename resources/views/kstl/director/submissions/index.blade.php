{{-- resources/views/kstl/director/submissions/index.blade.php --}}
{{-- Director pipeline view — all submissions from intake to completion --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('director.dashboard') }}"
                   class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <p class="text-xs font-semibold tracking-widest text-amber-600 uppercase">Director &middot; Monitoring</p>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mt-0.5">All Submissions — Pipeline View</h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ── Status Summary Bar ──────────────────────────────── --}}
            @php
                $statuses = [
                    'new_submission'        => ['label' => 'New',              'color' => 'bg-gray-100 text-gray-700'],
                    'reception_review'      => ['label' => 'Reception Review', 'color' => 'bg-blue-50 text-blue-700'],
                    'sample_received'       => ['label' => 'Sample Received',  'color' => 'bg-cyan-50 text-cyan-700'],
                    'testing'               => ['label' => 'Testing',          'color' => 'bg-indigo-50 text-indigo-700'],
                    'awaiting_authorisation'=> ['label' => 'Awaiting Auth',    'color' => 'bg-amber-50 text-amber-700'],
                    'authorised'            => ['label' => 'Authorised',       'color' => 'bg-green-50 text-green-700'],
                    'completed'             => ['label' => 'Completed',        'color' => 'bg-teal-50 text-teal-700'],
                    'rejected'              => ['label' => 'Rejected',         'color' => 'bg-red-50 text-red-700'],
                ];
            @endphp
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-2">
                @foreach($statuses as $key => $meta)
                    <a href="?status={{ $key }}&search={{ request('search') }}"
                       class="rounded-xl border px-3 py-3 text-center transition hover:shadow-sm
                              {{ request('status') === $key ? 'ring-2 ring-offset-1 ring-indigo-500 ' . $meta['color'] : 'bg-white border-gray-200' }}">
                        <p class="text-2xl font-bold {{ request('status') === $key ? '' : 'text-gray-800' }}">
                            {{ $statusCounts[$key] ?? 0 }}
                        </p>
                        <p class="text-xs font-medium mt-0.5 {{ request('status') === $key ? '' : 'text-gray-500' }}">
                            {{ $meta['label'] }}
                        </p>
                    </a>
                @endforeach
            </div>

            {{-- ── Filters ─────────────────────────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-100 px-5 py-4">
                <form method="GET" class="flex flex-wrap items-end gap-3">
                    <div class="flex-1 min-w-48">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Reference, sample, or company…"
                               class="w-full border-gray-300 rounded-lg text-sm focus:border-teal-500 focus:ring-teal-500"/>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                        <select name="status" class="border-gray-300 rounded-lg text-sm focus:border-teal-500 focus:ring-teal-500">
                            <option value="">All statuses</option>
                            @foreach($statuses as $key => $meta)
                                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                    {{ $meta['label'] }} ({{ $statusCounts[$key] ?? 0 }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['status', 'search']))
                        <a href="{{ route('director.submissions.index') }}"
                           class="inline-flex items-center gap-1 px-3 py-2 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-lg">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            {{-- ── Submissions Table ───────────────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-700">
                        {{ $submissions->total() }} submission{{ $submissions->total() !== 1 ? 's' : '' }}
                        @if(request('status'))
                            &middot; <span class="text-indigo-600">{{ $statuses[request('status')]['label'] ?? request('status') }}</span>
                        @endif
                    </p>
                    <p class="text-xs text-gray-400">Showing {{ $submissions->firstItem() }}–{{ $submissions->lastItem() }}</p>
                </div>

                @if($submissions->isEmpty())
                    <div class="p-12 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-gray-500 font-medium">No submissions found</p>
                        <p class="text-gray-400 text-sm mt-1">Try adjusting your filters.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Reference</th>
                                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Client</th>
                                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Sample</th>
                                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Priority</th>
                                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Status</th>
                                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Submitted</th>
                                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Last Updated</th>
                                    <th class="px-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($submissions as $sub)
                                    @php
                                        $sc = [
                                            'new_submission'         => 'bg-gray-100 text-gray-700',
                                            'reception_review'       => 'bg-blue-50 text-blue-700',
                                            'sample_received'        => 'bg-cyan-50 text-cyan-700',
                                            'testing'                => 'bg-indigo-50 text-indigo-700',
                                            'awaiting_authorisation' => 'bg-amber-50 text-amber-700',
                                            'authorised'             => 'bg-green-50 text-green-700',
                                            'completed'              => 'bg-teal-50 text-teal-700',
                                            'rejected'               => 'bg-red-50 text-red-700',
                                        ];
                                        $sl = [
                                            'new_submission'         => 'New',
                                            'reception_review'       => 'Reception Review',
                                            'sample_received'        => 'Sample Received',
                                            'testing'                => 'Testing',
                                            'awaiting_authorisation' => 'Awaiting Authorisation',
                                            'authorised'             => 'Authorised',
                                            'completed'              => 'Completed',
                                            'rejected'               => 'Rejected',
                                        ];
                                        $pc = [
                                            'routine'   => 'bg-gray-100 text-gray-600',
                                            'urgent'    => 'bg-amber-50 text-amber-700',
                                            'emergency' => 'bg-red-50 text-red-700',
                                        ];
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-5 py-3">
                                            <p class="font-mono text-xs font-semibold text-indigo-600">{{ $sub->reference_number }}</p>
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="font-medium text-gray-800 text-xs">{{ $sub->client->company_name ?? '—' }}</p>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-600 max-w-xs truncate">
                                            {{ $sub->sample_name }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full capitalize {{ $pc[$sub->priority ?? 'routine'] ?? 'bg-gray-100 text-gray-600' }}">
                                                {{ ucfirst($sub->priority ?? 'routine') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full {{ $sc[$sub->status] ?? 'bg-gray-100 text-gray-500' }}">
                                                {{ $sl[$sub->status] ?? ucfirst(str_replace('_',' ',$sub->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-500">
                                            {{ $sub->submitted_at?->format('d M Y') ?? $sub->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-400">
                                            {{ $sub->updated_at->diffForHumans() }}
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('director.submissions.show', $sub->id) }}"
                                               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-600 hover:text-indigo-800 border border-indigo-200 rounded-lg hover:bg-indigo-50 transition">
                                                View
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($submissions->hasPages())
                        <div class="px-5 py-4 border-t border-gray-100">
                            {{ $submissions->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
