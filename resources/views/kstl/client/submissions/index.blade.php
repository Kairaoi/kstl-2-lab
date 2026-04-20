{{-- resources/views/kstl/client/submissions/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Submissions
            </h2>
            @if($client && $client->service_agreement_signed_at)
                <a href="{{ route('client.submissions.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Submission
                </a>
            @endif
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

            {{-- Service Agreement Warning --}}
            @if($client && !$client->service_agreement_signed_at)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-yellow-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-yellow-800">
                            <span class="font-medium">Service agreement required.</span>
                            You must sign the service agreement before submitting samples.
                            Please contact the lab.
                        </p>
                    </div>
                </div>
            @endif

            {{-- Filters & Search --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <form method="GET" action="{{ route('client.submissions.index') }}"
                      class="flex flex-col sm:flex-row gap-3">

                    {{-- Search --}}
                    <div class="flex-1 relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by reference or sample name..."
                               class="w-full pl-10 pr-4 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"/>
                    </div>

                    {{-- Status Filter --}}
                    <select name="status"
                            class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="submitted"              {{ request('status') === 'submitted'              ? 'selected' : '' }}>Submitted</option>
                        <option value="received"               {{ request('status') === 'received'               ? 'selected' : '' }}>Received</option>
                        <option value="assessing"              {{ request('status') === 'assessing'              ? 'selected' : '' }}>Assessing</option>
                        <option value="accepted"               {{ request('status') === 'accepted'               ? 'selected' : '' }}>Accepted</option>
                        <option value="rejected"               {{ request('status') === 'rejected'               ? 'selected' : '' }}>Rejected</option>
                        <option value="testing"                {{ request('status') === 'testing'                ? 'selected' : '' }}>Testing</option>
                        <option value="awaiting_authorisation" {{ request('status') === 'awaiting_authorisation' ? 'selected' : '' }}>Awaiting Authorisation</option>
                        <option value="authorised"             {{ request('status') === 'authorised'             ? 'selected' : '' }}>Authorised</option>
                        <option value="completed"              {{ request('status') === 'completed'              ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled"              {{ request('status') === 'cancelled'              ? 'selected' : '' }}>Cancelled</option>
                    </select>

                    {{-- Date Filter --}}
                    <input type="date"
                           name="from"
                           value="{{ request('from') }}"
                           class="text-sm border border-gray-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"/>

                    <button type="submit"
                            class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900 transition">
                        Filter
                    </button>

                    @if(request()->hasAny(['search', 'status', 'from']))
                        <a href="{{ route('client.submissions.index') }}"
                           class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            {{-- Submissions Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

                {{-- Table Header --}}
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">
                        All Submissions
                        @if(isset($submissions) && method_exists($submissions, 'total'))
                            <span class="ml-2 text-sm text-gray-400 font-normal">({{ $submissions->total() }} total)</span>
                        @endif
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">
                                    Reference
                                </th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">
                                    Sample Name
                                </th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">
                                    Tests Requested
                                </th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">
                                    Submitted
                                </th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">
                                    Status
                                </th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">
                                    Result
                                </th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">

                            @forelse($submissions as $submission)
                                <tr class="hover:bg-gray-50 transition">

                                    {{-- Reference --}}
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-xs font-medium text-gray-700">
                                            {{ $submission->reference_number }}
                                        </span>
                                    </td>

                                    {{-- Sample Name + Type --}}
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-medium text-gray-800">{{ $submission->sample_name }}</p>
                                        <p class="text-xs text-gray-400 capitalize mt-0.5">{{ $submission->sample_type }}</p>
                                    </td>

                                    {{-- Tests Requested --}}
                                    <td class="px-6 py-4">
                                        @php
                                            $tests = is_array($submission->tests_requested)
                                                ? $submission->tests_requested
                                                : json_decode($submission->tests_requested ?? '[]', true) ?? [];
                                        @endphp
                                        @if(count($tests))
                                            <div class="flex flex-wrap gap-1">
                                                @foreach(array_slice($tests, 0, 3) as $test)
                                                    <span class="inline-flex px-1.5 py-0.5 text-xs bg-blue-50 text-blue-700 rounded">
                                                        {{ str_replace('_', ' ', $test) }}
                                                    </span>
                                                @endforeach
                                                @if(count($tests) > 3)
                                                    <span class="text-xs text-gray-400">
                                                        +{{ count($tests) - 3 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-xs">—</span>
                                        @endif
                                    </td>
                                    {{-- Submitted date --}}
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $submission->submitted_at?->format('d M Y') ?? $submission->created_at->format('d M Y') }}
                                    </td>

                                    {{-- Status badge --}}
                                    <td class="px-6 py-4">
                                        @php
                                            $statusConfig = [
                                                'submitted'              => ['bg-yellow-50 text-yellow-700 ring-yellow-600/20',  'Submitted'],
                                                'received'               => ['bg-blue-50 text-blue-700 ring-blue-600/20',        'Received'],
                                                'assessing'              => ['bg-purple-50 text-purple-700 ring-purple-600/20',  'Assessing'],
                                                'accepted'               => ['bg-green-50 text-green-700 ring-green-600/20',     'Accepted'],
                                                'rejected'               => ['bg-red-50 text-red-700 ring-red-600/20',           'Rejected'],
                                                'consent_to_proceed'     => ['bg-orange-50 text-orange-700 ring-orange-600/20',  'Consent to Proceed'],
                                                'testing'                => ['bg-indigo-50 text-indigo-700 ring-indigo-600/20',  'Testing'],
                                                'awaiting_authorisation' => ['bg-amber-50 text-amber-700 ring-amber-600/20',    'Awaiting Auth.'],
                                                'authorised'             => ['bg-teal-50 text-teal-700 ring-teal-600/20',        'Authorised'],
                                                'completed'              => ['bg-green-50 text-green-700 ring-green-600/20',     'Completed'],
                                                'cancelled'              => ['bg-gray-50 text-gray-500 ring-gray-500/20',        'Cancelled'],
                                            ];
                                            $sc = $statusConfig[$submission->status] ?? ['bg-gray-50 text-gray-500 ring-gray-500/20', ucfirst($submission->status)];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ring-1 ring-inset {{ $sc[0] }}">
                                            {{ $sc[1] }}
                                        </span>
                                    </td>

                                    {{-- Result --}}
                                    <td class="px-6 py-4">
                                        @if($submission->hasResult())
                                            <span class="inline-flex items-center gap-1 text-xs text-green-700 font-medium">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                                                </svg>
                                                Ready
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">Pending</span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('client.submissions.show', $submission->id) }}"
                                               class="text-xs text-gray-600 px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                                View
                                            </a>
                                            @if($submission->isEditable())
                                                <a href="{{ route('client.submissions.edit', $submission->id) }}"
                                                   class="text-xs text-blue-600 px-3 py-1 border border-blue-200 rounded-lg hover:bg-blue-50 transition">
                                                    Edit
                                                </a>
                                            @endif
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                {{-- Empty State --}}
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center">
                                        <svg class="w-12 h-12 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="text-gray-400 text-sm font-medium">No submissions yet</p>
                                        <p class="text-gray-300 text-xs mt-1">Submit your first sample to get started.</p>
                                        @if($client && $client->service_agreement_signed_at)
                                            <a href="{{ route('client.submissions.create') }}"
                                               class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                                New Submission
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if(isset($submissions) && method_exists($submissions, 'hasPages') && $submissions->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $submissions->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>