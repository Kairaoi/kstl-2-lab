{{-- resources/views/kstl/client/notifications/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Notifications</h2>
            @if(\DB::table('notifications')->where('notifiable_type', 'App\\Models\\User')
                    ->where('notifiable_id', auth()->id())->whereNull('read_at')->exists())
                <form method="POST" action="{{ route('client.notifications.read-all') }}">
                    @csrf
                    <button type="submit"
                            class="text-xs text-blue-600 hover:underline">
                        Mark all as read
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-3">

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $notifications = \DB::table('notifications')
                    ->where('notifiable_type', 'App\\Models\\User')
                    ->where('notifiable_id', auth()->id())
                    ->orderByDesc('created_at')
                    ->paginate(20);
            @endphp

            @if($notifications->isEmpty())
                <div class="bg-white rounded-xl border border-gray-100 px-6 py-16 text-center">
                    <svg class="w-10 h-10 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <p class="text-sm text-gray-400">No notifications yet</p>
                </div>
            @else
                @foreach($notifications as $notification)
                    @php
                        $isUnread = is_null($notification->read_at);
                        $icon = match(json_decode($notification->data)->notification_type ?? 'info') {
                            'results_ready'          => ['color' => 'text-green-500',  'bg' => 'bg-green-50',  'path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                            'invoice_issued'         => ['color' => 'text-blue-500',   'bg' => 'bg-blue-50',   'path' => 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z'],
                            'awaiting_authorisation' => ['color' => 'text-amber-500',  'bg' => 'bg-amber-50',  'path' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                            'sample_rejected'        => ['color' => 'text-red-500',    'bg' => 'bg-red-50',    'path' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                            default                  => ['color' => 'text-gray-400',   'bg' => 'bg-gray-50',   'path' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        };
                    @endphp

                    <div class="bg-white rounded-xl border {{ $isUnread ? 'border-blue-200 shadow-sm' : 'border-gray-100' }} px-5 py-4 flex items-start gap-4">

                        {{-- Icon --}}
                        <div class="w-9 h-9 rounded-full {{ $icon['bg'] }} flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4.5 h-4.5 {{ $icon['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon['path'] }}"/>
                            </svg>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <p class="text-sm font-{{ $isUnread ? 'semibold' : 'medium' }} text-gray-800">
                                    {{ json_decode($notification->data)->subject ?? $notification->type }}
                                    @if($isUnread)
                                        <span class="ml-2 inline-flex w-2 h-2 bg-blue-500 rounded-full"></span>
                                    @endif
                                </p>
                                <span class="text-xs text-gray-400 shrink-0 mt-0.5">
                                    {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                </span>
                            </div>
                            @if(json_decode($notification->data)->message ?? '')
                                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ json_decode($notification->data)->message ?? '' }}</p>
                            @endif
                        </div>

                        {{-- Mark read --}}
                        @if($isUnread)
                            <form method="POST"
                                  action="{{ route('client.notifications.read', $notification->id) }}"
                                  class="shrink-0">
                                @csrf
                                <button type="submit"
                                        class="text-xs text-gray-400 hover:text-gray-600 transition"
                                        title="Mark as read">
                                    ✓
                                </button>
                            </form>
                        @endif

                    </div>
                @endforeach

                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>