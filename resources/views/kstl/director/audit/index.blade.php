{{-- resources/views/kstl/director/audit/index.blade.php --}}
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
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Audit Log</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Immutable record of all significant actions — ISO 17025</p>
                </div>
            </div>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 ring-1 ring-red-600/20">
                CONFIDENTIAL
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- ── Filters ──────────────────────────────────────────── --}}
            <form method="GET" action="{{ route('director.audit.index') }}"
                  class="bg-white rounded-xl border border-gray-100 px-5 py-4 flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-36">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Event type</label>
                    <select name="event"
                            class="w-full border-gray-200 rounded-lg text-sm focus:border-indigo-400 focus:ring-indigo-400">
                        <option value="">All events</option>
                        @foreach([
                            'created','updated','deleted','login','logout',
                            'signed','countersigned','authorised','queried',
                            'submitted','status_changed','generated','responded'
                        ] as $evt)
                            <option value="{{ $evt }}" {{ request('event') === $evt ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $evt)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1 min-w-36">
                    <label class="block text-xs font-medium text-gray-500 mb-1">User</label>
                    <input type="text" name="user" value="{{ request('user') }}"
                           placeholder="Name or ID…"
                           class="w-full border-gray-200 rounded-lg text-sm focus:border-indigo-400 focus:ring-indigo-400">
                </div>
                <div class="min-w-36">
                    <label class="block text-xs font-medium text-gray-500 mb-1">From</label>
                    <input type="date" name="from" value="{{ request('from') }}"
                           class="w-full border-gray-200 rounded-lg text-sm focus:border-indigo-400 focus:ring-indigo-400">
                </div>
                <div class="min-w-36">
                    <label class="block text-xs font-medium text-gray-500 mb-1">To</label>
                    <input type="date" name="to" value="{{ request('to') }}"
                           class="w-full border-gray-200 rounded-lg text-sm focus:border-indigo-400 focus:ring-indigo-400">
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['event','user','from','to']))
                        <a href="{{ route('director.audit.index') }}"
                           class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 transition">
                            Clear
                        </a>
                    @endif
                </div>
            </form>

            {{-- ── Summary strip ────────────────────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-100 px-5 py-3 flex items-center justify-between text-sm">
                <p class="text-gray-500">
                    Showing <span class="font-semibold text-gray-800">{{ $logs->firstItem() }}–{{ $logs->lastItem() }}</span>
                    of <span class="font-semibold text-gray-800">{{ number_format($logs->total()) }}</span> records
                </p>
                <p class="text-xs text-gray-400">Append-only — records cannot be edited or deleted</p>
            </div>

            {{-- ── Log table ────────────────────────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                @if($logs->isEmpty())
                    <div class="px-6 py-16 text-center text-gray-400 text-sm">No audit records found.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Timestamp</th>
                                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Event</th>
                                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">User</th>
                                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Description</th>
                                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Entity</th>
                                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">IP</th>
                                    <th class="px-4 py-3 w-8"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($logs as $log)
                                    @php
                                        $eventColours = [
                                            'created'       => 'bg-green-50 text-green-700',
                                            'updated'       => 'bg-blue-50 text-blue-700',
                                            'deleted'       => 'bg-red-50 text-red-700',
                                            'login'         => 'bg-teal-50 text-teal-700',
                                            'logout'        => 'bg-gray-100 text-gray-600',
                                            'signed'        => 'bg-indigo-50 text-indigo-700',
                                            'countersigned' => 'bg-indigo-50 text-indigo-700',
                                            'authorised'    => 'bg-emerald-50 text-emerald-700',
                                            'queried'       => 'bg-amber-50 text-amber-700',
                                            'submitted'     => 'bg-purple-50 text-purple-700',
                                            'status_changed'=> 'bg-sky-50 text-sky-700',
                                            'generated'     => 'bg-orange-50 text-orange-700',
                                            'responded'     => 'bg-pink-50 text-pink-700',
                                        ];
                                        $ec = $eventColours[$log->event] ?? 'bg-gray-100 text-gray-600';
                                        $entityLabel = $log->auditable_type
                                            ? class_basename($log->auditable_type)
                                            : '—';
                                        $hasDetail = !empty($log->old_values) || !empty($log->new_values);
                                    @endphp
                                    <tr x-data="{ open: false }" class="hover:bg-gray-50/60 transition">
                                        <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap font-mono">
                                            {{ $log->created_at->format('d M Y') }}<br>
                                            <span class="text-gray-400">{{ $log->created_at->format('H:i:s') }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full capitalize {{ $ec }}">
                                                {{ str_replace('_', ' ', $log->event) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <p class="text-xs font-medium text-gray-800">{{ $log->user_name ?? '—' }}</p>
                                            @if($log->country_name ?? $log->country_code)
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $log->country_name ?? $log->country_code }}</p>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-700 max-w-xs">
                                            {{ $log->description ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="inline-flex px-2 py-0.5 text-xs rounded bg-gray-100 text-gray-600 font-mono">
                                                {{ $entityLabel }}
                                            </span>
                                            @if($log->auditable_id)
                                                <p class="text-xs text-gray-400 font-mono mt-0.5 truncate max-w-24" title="{{ $log->auditable_id }}">
                                                    {{ substr($log->auditable_id, 0, 8) }}…
                                                </p>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-xs text-gray-400 font-mono whitespace-nowrap">
                                            {{ $log->ip_address ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            @if($hasDetail)
                                                <button @click="open = !open"
                                                        class="text-gray-300 hover:text-indigo-500 transition"
                                                        :title="open ? 'Hide detail' : 'Show detail'">
                                                    <svg class="w-4 h-4 transition" :class="open ? 'rotate-180' : ''"
                                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>

                                    {{-- Expandable diff row --}}
                                    @if($hasDetail)
                                        <tr x-show="open" x-cloak class="bg-gray-50/60">
                                            <td colspan="7" class="px-4 pb-4 pt-0">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-1">
                                                    @if(!empty($log->old_values))
                                                        <div class="rounded-lg border border-red-100 bg-red-50/40 p-3">
                                                            <p class="text-xs font-semibold text-red-600 mb-2 uppercase tracking-wide">Before</p>
                                                            <pre class="text-xs text-red-800 whitespace-pre-wrap break-all font-mono leading-relaxed">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </div>
                                                    @endif
                                                    @if(!empty($log->new_values))
                                                        <div class="rounded-lg border border-green-100 bg-green-50/40 p-3">
                                                            <p class="text-xs font-semibold text-green-600 mb-2 uppercase tracking-wide">After</p>
                                                            <pre class="text-xs text-green-800 whitespace-pre-wrap break-all font-mono leading-relaxed">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($logs->hasPages())
                        <div class="px-5 py-4 border-t border-gray-100">
                            {{ $logs->withQueryString()->links() }}
                        </div>
                    @endif
                @endif
            </div>

            <div class="pb-4"></div>
        </div>
    </div>
</x-app-layout>
