{{-- resources/views/kstl/auditor/audit/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Audit Log</h2>
                <p class="text-xs text-gray-400 mt-0.5">ISO 17025 — Immutable append-only record of all system actions</p>
            </div>
            <div class="flex items-center gap-6 text-sm">
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($summary['today']) }}</p>
                    <p class="text-xs text-gray-400">Today</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($summary['this_week']) }}</p>
                    <p class="text-xs text-gray-400">This week</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($summary['total']) }}</p>
                    <p class="text-xs text-gray-400">All time</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- Filters --}}
            <form method="GET" action="{{ route('auditor.audit.index') }}"
                  class="bg-white rounded-xl border border-gray-100 px-5 py-4 flex flex-wrap items-end gap-3">

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Event</label>
                    <select name="event"
                            class="border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Events</option>
                        @foreach([
                            'login'          => 'Login',
                            'submitted'      => 'Submitted',
                            'status_changed' => 'Status Changed',
                            'signed'         => 'Signed',
                            'countersigned'  => 'Countersigned',
                            'authorised'     => 'Authorised',
                            'queried'        => 'Queried',
                            'generated'      => 'Generated',
                            'responded'      => 'Responded',
                            'created'        => 'Created',
                            'updated'        => 'Updated',
                            'deleted'        => 'Deleted',
                        ] as $val => $label)
                            <option value="{{ $val }}" {{ request('event') == $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">User</label>
                    <input type="text" name="user" value="{{ request('user') }}"
                           placeholder="Name..."
                           class="border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500 w-40"/>
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">Entity Type</label>
                    <select name="entity"
                            class="border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All</option>
                        @foreach(['Client','Submission','Result','Invoice','Complaint'] as $entity)
                            <option value="{{ $entity }}" {{ request('entity') == $entity ? 'selected' : '' }}>
                                {{ $entity }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">From</label>
                    <input type="date" name="from" value="{{ request('from') }}"
                           class="border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500"/>
                </div>

                <div>
                    <label class="block text-xs text-gray-500 mb-1">To</label>
                    <input type="date" name="to" value="{{ request('to') }}"
                           class="border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500"/>
                </div>

                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                    Filter
                </button>

                @if(request()->hasAny(['event','user','entity','from','to']))
                    <a href="{{ route('auditor.audit.index') }}"
                       class="px-4 py-2 bg-gray-100 text-gray-600 text-sm rounded-lg hover:bg-gray-200 transition">
                        Clear
                    </a>
                @endif
            </form>

            {{-- Log Table --}}
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-medium text-gray-800">
                        {{ number_format($logs->total()) }} entries
                        @if(request()->hasAny(['event','user','entity','from','to']))
                            <span class="text-xs text-gray-400 ml-2">(filtered)</span>
                        @endif
                    </h3>
                </div>

                @if($logs->isEmpty())
                    <div class="px-6 py-16 text-center">
                        <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-sm text-gray-400">No audit log entries found.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Timestamp</th>
                                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">User</th>
                                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Event</th>
                                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Description</th>
                                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Entity</th>
                                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">IP Address</th>
                                    <th class="px-5 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($logs as $log)
                                    @php
                                        $eventColors = [
                                            'login'          => 'bg-blue-50 text-blue-700',
                                            'submitted'      => 'bg-indigo-50 text-indigo-700',
                                            'status_changed' => 'bg-yellow-50 text-yellow-700',
                                            'signed'         => 'bg-purple-50 text-purple-700',
                                            'countersigned'  => 'bg-purple-100 text-purple-800',
                                            'authorised'     => 'bg-green-50 text-green-700',
                                            'queried'        => 'bg-orange-50 text-orange-700',
                                            'generated'      => 'bg-teal-50 text-teal-700',
                                            'responded'      => 'bg-sky-50 text-sky-700',
                                            'created'        => 'bg-gray-100 text-gray-600',
                                            'updated'        => 'bg-gray-100 text-gray-600',
                                            'deleted'        => 'bg-red-50 text-red-700',
                                        ];
                                        $color  = $eventColors[$log->event] ?? 'bg-gray-100 text-gray-500';
                                        $entity = class_basename($log->auditable_type ?? '');
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-5 py-3.5 text-xs text-gray-500 whitespace-nowrap font-mono">
                                            {{ $log->created_at->format('d M Y') }}<br>
                                            <span class="text-gray-400">{{ $log->created_at->format('H:i:s') }}</span>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <p class="text-gray-800 font-medium text-sm">{{ $log->user_name ?? 'System' }}</p>
                                        </td>
                                        <td class="px-5 py-3.5">
                                            <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full {{ $color }} capitalize whitespace-nowrap">
                                                {{ str_replace('_', ' ', $log->event) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3.5 text-gray-700 text-sm max-w-xs">
                                            {{ $log->description }}
                                        </td>
                                        <td class="px-5 py-3.5 text-xs text-gray-400 whitespace-nowrap">
                                            {{ $entity ?: '—' }}
                                        </td>
                                        <td class="px-5 py-3.5 text-xs font-mono text-gray-400 whitespace-nowrap">
                                            {{ $log->ip_address ?? '—' }}
                                        </td>
                                        <td class="px-5 py-3.5 text-right">
                                            <a href="{{ route('auditor.audit.show', $log->id) }}"
                                               class="text-xs text-indigo-600 hover:text-indigo-800 transition">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-5 py-4 border-t border-gray-100">
                        {{ $logs->withQueryString()->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>