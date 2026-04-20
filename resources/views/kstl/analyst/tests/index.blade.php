{{-- resources/views/kstl/analyst/tests/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Test Queue</h2>
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('analyst.tests.index') }}">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox"
                               name="mine"
                               value="1"
                               {{ request('mine') ? 'checked' : '' }}
                               onchange="this.form.submit()"
                               class="rounded text-indigo-600 focus:ring-indigo-500">
                        My tests only
                    </label>
                </form>
            </div>
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

            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-800">
                        All Pending Tests
                        <span class="ml-2 text-xs text-gray-400 font-normal">({{ $queue->count() }} total)</span>
                    </h3>
                </div>

                @if($queue->isEmpty())
                    <div class="px-6 py-16 text-center">
                        <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-medium text-gray-400">No tests pending</p>
                        <p class="text-xs text-gray-300 mt-1">All tests have been completed or no submissions are in testing.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-100">
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Reference</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Sample</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Test</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Category</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Assigned To</th>
                                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Status</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($queue as $test)
                                    <tr class="hover:bg-gray-50 transition {{ $test->status === 'in_progress' ? 'bg-blue-50/30' : '' }}">
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-xs text-gray-600">
                                                {{ $test->sample->submission->reference_number }}
                                            </span>
                                            @php $p = $test->sample->submission->priority ?? 'routine'; @endphp
                                            @if($p !== 'routine')
                                                <span class="ml-1 inline-flex px-1.5 py-0.5 text-xs rounded capitalize
                                                    {{ $p === 'emergency' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                                                    {{ $p }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-medium text-gray-800 text-xs">{{ $test->sample->sample_code }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $test->sample->common_name }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $test->getDisplayLabel() }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex px-2 py-0.5 text-xs rounded-full capitalize
                                                {{ $test->getDisplayCategory() === 'microbiological' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">
                                                {{ $test->getDisplayCategory() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-gray-500">
                                            {{ $test->assignedTo?->name ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($test->status === 'in_progress')
                                                <span class="inline-flex px-2 py-0.5 text-xs bg-blue-50 text-blue-700 rounded-full">In Progress</span>
                                            @else
                                                <span class="inline-flex px-2 py-0.5 text-xs bg-yellow-50 text-yellow-700 rounded-full">Queued</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('analyst.tests.show', $test->id) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 transition">
                                                {{ $test->status === 'in_progress' ? 'Continue →' : 'Start →' }}
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