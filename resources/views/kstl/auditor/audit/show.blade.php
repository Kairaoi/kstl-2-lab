{{-- resources/views/kstl/auditor/audit/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Audit</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Audit Entry Detail</h1>
                            <p style="font-size:12px;color:#94a3b8;font-family:monospace;margin:0;">{{ $log->id }}</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <a href="{{ route('auditor.audit.index') }}"
                           style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:rgba(255,255,255,.12);color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid rgba(255,255,255,.3);border-radius:3px;text-decoration:none;">
                            ← Back to audit log
                        </a>
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

            {{-- Core details --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:20px;">
                <div style="padding:20px 24px;border-bottom:1px solid #e2e8f0;">
                    <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Entry Details</h3>
                </div>
                <dl style="font-size:13px;">
                    <div style="padding:12px 24px;display:flex;justify-content:space-between;border-bottom:1px solid #f1f5f9;">
                        <dt style="color:#64748b;">Timestamp</dt>
                        <dd style="font-family:monospace;color:#1e293b;margin:0;">{{ $log->created_at->format('d M Y \a\t H:i:s') }} UTC</dd>
                    </div>
                    <div style="padding:12px 24px;display:flex;justify-content:space-between;border-bottom:1px solid #f1f5f9;background:#f8fafc;">
                        <dt style="color:#64748b;">User</dt>
                        <dd style="font-weight:600;color:#1e293b;margin:0;">{{ $log->user_name ?? 'System' }}</dd>
                    </div>
                    <div style="padding:12px 24px;display:flex;justify-content:space-between;border-bottom:1px solid #f1f5f9;">
                        <dt style="color:#64748b;">User ID</dt>
                        <dd style="font-family:monospace;font-size:12px;color:#64748b;margin:0;">{{ $log->user_id ?? '—' }}</dd>
                    </div>
                    <div style="padding:12px 24px;display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #f1f5f9;background:#f8fafc;">
                        <dt style="color:#64748b;">Event</dt>
                        <dd style="margin:0;">
                            <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;text-transform:capitalize;background:#eef2ff;color:#4338ca;">
                                {{ str_replace('_', ' ', $log->event) }}
                            </span>
                        </dd>
                    </div>
                    <div style="padding:12px 24px;display:flex;justify-content:space-between;border-bottom:1px solid #f1f5f9;">
                        <dt style="color:#64748b;">Description</dt>
                        <dd style="color:#1e293b;text-align:right;max-width:360px;margin:0;">{{ $log->description }}</dd>
                    </div>
                    <div style="padding:12px 24px;display:flex;justify-content:space-between;border-bottom:1px solid #f1f5f9;background:#f8fafc;">
                        <dt style="color:#64748b;">Entity Type</dt>
                        <dd style="font-family:monospace;font-size:12px;color:#64748b;margin:0;">{{ $log->auditable_type ?? '—' }}</dd>
                    </div>
                    <div style="padding:12px 24px;display:flex;justify-content:space-between;border-bottom:1px solid #f1f5f9;">
                        <dt style="color:#64748b;">Entity ID</dt>
                        <dd style="font-family:monospace;font-size:12px;color:#64748b;margin:0;">{{ $log->auditable_id ?? '—' }}</dd>
                    </div>
                    <div style="padding:12px 24px;display:flex;justify-content:space-between;border-bottom:1px solid #f1f5f9;background:#f8fafc;">
                        <dt style="color:#64748b;">IP Address</dt>
                        <dd style="font-family:monospace;font-size:13px;color:#374151;margin:0;">{{ $log->ip_address ?? '—' }}</dd>
                    </div>
                    <div style="padding:12px 24px;display:flex;justify-content:space-between;">
                        <dt style="color:#64748b;">User Agent</dt>
                        <dd style="font-size:12px;color:#64748b;text-align:right;max-width:360px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin:0;">{{ $log->user_agent ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Old values --}}
            @if($log->old_values)
                <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:4px;overflow:hidden;margin-bottom:20px;">
                    <div style="padding:14px 24px;border-bottom:1px solid #fecaca;">
                        <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#991b1b;margin:0;">Before (Old Values)</h3>
                    </div>
                    <pre style="padding:20px 24px;font-size:12px;color:#991b1b;overflow-x:auto;margin:0;">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endif

            {{-- New values --}}
            @if($log->new_values)
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:4px;overflow:hidden;margin-bottom:20px;">
                    <div style="padding:14px 24px;border-bottom:1px solid #bbf7d0;">
                        <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#166534;margin:0;">After (New Values)</h3>
                    </div>
                    <pre style="padding:20px 24px;font-size:12px;color:#166534;overflow-x:auto;margin:0;">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endif

            <div style="padding-bottom:32px;"></div>

        </div>
    </div>
</x-app-layout>
