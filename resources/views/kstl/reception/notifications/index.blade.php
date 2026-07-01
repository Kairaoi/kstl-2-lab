{{-- resources/views/kstl/reception/notifications/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Reception</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Notifications</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">New submissions and lab updates</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        @if(\DB::table('notifications')->where('notifiable_type', 'App\\Models\\User')
                                ->where('notifiable_id', auth()->id())->whereNull('read_at')->exists())
                            <form method="POST" action="{{ route('reception.notifications.read-all') }}">
                                @csrf
                                <button type="submit"
                                        style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;cursor:pointer;">
                                    Mark all as read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .page-hdr { padding: 0 !important; position: static !important; }
        .page-hdr-inner { max-width: 100% !important; padding: 0 !important; }
        .app-main { padding-left:0 !important; padding-right:0 !important; padding-top:0 !important; max-width:100% !important; }
    </style>
    @endpush

    <div style="background:#f1f5f9;min-height:100vh;padding:0 0 56px;">
        <div style="max-width:80rem;margin:0 auto;padding:0 2rem;">

            @if(session('success'))
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#166534;">{{ session('success') }}</div>
            @endif

            @if($notifications->isEmpty())
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;padding:64px 24px;text-align:center;margin-top:24px;">
                    <svg style="width:40px;height:40px;color:#cbd5e1;margin:0 auto 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <p style="font-size:14px;color:#94a3b8;margin:0;">No notifications yet</p>
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:8px;margin-top:24px;">
                    @foreach($notifications as $notification)
                        @php
                            $isUnread   = is_null($notification->read_at);
                            $notifData  = json_decode($notification->data);
                            $notifType  = $notifData->notification_type ?? 'info';
                            $iconColors = match($notifType) {
                                'new_submission'  => ['border' => '#1a2f4e', 'bg' => '#eff6ff', 'color' => '#1e40af'],
                                'samples_received'=> ['border' => '#16a34a', 'bg' => '#f0fdf4', 'color' => '#166534'],
                                default           => ['border' => '#64748b', 'bg' => '#f8fafc', 'color' => '#475569'],
                            };
                        @endphp

                        <div style="background:#fff;border:1px solid {{ $isUnread ? '#bfdbfe' : '#e2e8f0' }};border-left:4px solid {{ $iconColors['border'] }};border-radius:4px;padding:16px 20px;display:flex;align-items:flex-start;gap:16px;">
                            <div style="flex:1;min-width:0;">
                                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;margin-bottom:4px;">
                                    <p style="font-size:13px;font-weight:{{ $isUnread ? '700' : '600' }};color:#1a2f4e;margin:0;">
                                        {{ $notifData->subject ?? $notification->type }}
                                        @if($isUnread)
                                            <span style="display:inline-block;width:8px;height:8px;background:#3b82f6;border-radius:50%;margin-left:6px;vertical-align:middle;"></span>
                                        @endif
                                    </p>
                                    <span style="font-size:11px;color:#94a3b8;white-space:nowrap;flex-shrink:0;">
                                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                    </span>
                                </div>
                                @if($notifData->message ?? '')
                                    <p style="font-size:12px;color:#64748b;margin:0;line-height:1.5;">{{ $notifData->message }}</p>
                                @endif
                            </div>

                            @if($isUnread)
                                <form method="POST"
                                      action="{{ route('reception.notifications.read', $notification->id) }}"
                                      style="flex-shrink:0;">
                                    @csrf
                                    <button type="submit"
                                            style="background:none;border:none;cursor:pointer;font-size:12px;color:#94a3b8;padding:0;"
                                            title="Mark as read">✓</button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div style="margin-top:20px;">
                    {{ $notifications->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
