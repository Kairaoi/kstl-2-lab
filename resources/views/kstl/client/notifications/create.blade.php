{{-- resources/views/kstl/client/notifications/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Administration</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Create Notification</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Compose and send a notification to a client</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <a href="{{ route('client.notifications.index') }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid rgba(255,255,255,.5);border-radius:3px;text-decoration:none;">
                            &larr; All Notifications
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .page-hdr { padding: 0 !important; }
        .page-hdr-inner { max-width: 100% !important; padding: 0 !important; }
        .app-main { padding-left:0 !important; padding-right:0 !important; padding-top:0 !important; max-width:100% !important; }
    </style>
    @endpush

    <div style="background:#f1f5f9;min-height:100vh;padding:52px 0 56px;">
        <div style="max-width:56rem;margin:0 auto;padding:0 2rem;">

            @if(session('success'))
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#166534;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#991b1b;">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;">
                    <ul style="margin:0;padding-left:16px;font-size:13px;color:#991b1b;">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('client.notifications.index') }}">
                @csrf

                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                    <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                        <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Notification Details</h3>
                    </div>
                    <div style="padding:20px 24px;">
                        <div style="display:grid;gap:20px;">

                            <div>
                                <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">
                                    Recipient <span style="color:#dc2626;">*</span>
                                </label>
                                @isset($clients)
                                    <select name="client_id" required
                                            style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;">
                                        <option value="">— Select a client —</option>
                                        @foreach($clients as $clientOption)
                                            <option value="{{ $clientOption->id }}" {{ old('client_id') == $clientOption->id ? 'selected' : '' }}>
                                                {{ $clientOption->company_name }}
                                                @if($clientOption->responsible_officer_name)
                                                    — {{ $clientOption->responsible_officer_name }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" name="client_id" value="{{ old('client_id') }}" required
                                           placeholder="Client ID"
                                           style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;">
                                @endisset
                            </div>

                            <div>
                                <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">
                                    Type
                                </label>
                                <select name="type"
                                        style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;">
                                    <option value="">— General —</option>
                                    <option value="info" {{ old('type') === 'info' ? 'selected' : '' }}>Information</option>
                                    <option value="warning" {{ old('type') === 'warning' ? 'selected' : '' }}>Warning</option>
                                    <option value="action_required" {{ old('type') === 'action_required' ? 'selected' : '' }}>Action Required</option>
                                </select>
                            </div>

                            <div>
                                <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">
                                    Subject / Title <span style="color:#dc2626;">*</span>
                                </label>
                                <input type="text" name="title" value="{{ old('title') }}" required maxlength="255"
                                       style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;box-sizing:border-box;">
                            </div>

                            <div>
                                <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">
                                    Message <span style="color:#dc2626;">*</span>
                                </label>
                                <textarea name="message" rows="6" required
                                          style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;resize:vertical;box-sizing:border-box;">{{ old('message') }}</textarea>
                                <p style="font-size:11px;color:#94a3b8;margin:4px 0 0;">This message will appear in the client's portal notification centre.</p>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                    <a href="{{ route('client.notifications.index') }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                        Cancel
                    </a>
                    <button type="submit" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#1a2f4e;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                        Send Notification
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>