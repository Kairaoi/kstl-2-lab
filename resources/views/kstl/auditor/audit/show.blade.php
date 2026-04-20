{{-- resources/views/kstl/auditor/audit/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('auditor.audit.index') }}"
               class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Audit Entry Detail</h2>
                <p class="text-xs text-gray-400 mt-0.5 font-mono">{{ $log->id }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- Core details --}}
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-medium text-gray-800">Entry Details</h3>
                </div>
                <dl class="divide-y divide-gray-50 text-sm">
                    <div class="px-6 py-3 flex justify-between">
                        <dt class="text-gray-500">Timestamp</dt>
                        <dd class="font-mono text-gray-800">{{ $log->created_at->format('d M Y \a\t H:i:s') }} UTC</dd>
                    </div>
                    <div class="px-6 py-3 flex justify-between">
                        <dt class="text-gray-500">User</dt>
                        <dd class="text-gray-800 font-medium">{{ $log->user_name ?? 'System' }}</dd>
                    </div>
                    <div class="px-6 py-3 flex justify-between">
                        <dt class="text-gray-500">User ID</dt>
                        <dd class="font-mono text-gray-500 text-xs">{{ $log->user_id ?? '—' }}</dd>
                    </div>
                    <div class="px-6 py-3 flex justify-between">
                        <dt class="text-gray-500">Event</dt>
                        <dd>
                            <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-indigo-50 text-indigo-700 capitalize">
                                {{ str_replace('_', ' ', $log->event) }}
                            </span>
                        </dd>
                    </div>
                    <div class="px-6 py-3 flex justify-between">
                        <dt class="text-gray-500">Description</dt>
                        <dd class="text-gray-800 text-right max-w-xs">{{ $log->description }}</dd>
                    </div>
                    <div class="px-6 py-3 flex justify-between">
                        <dt class="text-gray-500">Entity Type</dt>
                        <dd class="font-mono text-gray-500 text-xs">{{ $log->auditable_type ?? '—' }}</dd>
                    </div>
                    <div class="px-6 py-3 flex justify-between">
                        <dt class="text-gray-500">Entity ID</dt>
                        <dd class="font-mono text-gray-500 text-xs">{{ $log->auditable_id ?? '—' }}</dd>
                    </div>
                    <div class="px-6 py-3 flex justify-between">
                        <dt class="text-gray-500">IP Address</dt>
                        <dd class="font-mono text-gray-600">{{ $log->ip_address ?? '—' }}</dd>
                    </div>
                    <div class="px-6 py-3 flex justify-between">
                        <dt class="text-gray-500">User Agent</dt>
                        <dd class="text-gray-500 text-xs text-right max-w-xs truncate">{{ $log->user_agent ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Old values --}}
            @if($log->old_values)
                <div class="bg-red-50 rounded-xl border border-red-100 overflow-hidden">
                    <div class="px-6 py-3.5 border-b border-red-100">
                        <h3 class="text-sm font-medium text-red-800">Before (Old Values)</h3>
                    </div>
                    <pre class="px-6 py-4 text-xs text-red-700 overflow-x-auto">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endif

            {{-- New values --}}
            @if($log->new_values)
                <div class="bg-green-50 rounded-xl border border-green-100 overflow-hidden">
                    <div class="px-6 py-3.5 border-b border-green-100">
                        <h3 class="text-sm font-medium text-green-800">After (New Values)</h3>
                    </div>
                    <pre class="px-6 py-4 text-xs text-green-700 overflow-x-auto">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endif

            <div class="pb-8"></div>

        </div>
    </div>
</x-app-layout>