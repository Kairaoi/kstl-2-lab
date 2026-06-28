{{-- resources/views/kstl/client/show.blade.php --}}
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
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">{{ $client->company_name }}</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Client profile &amp; account details</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <a href="{{ route('client.edit', $client->id) }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#b8922a;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;text-decoration:none;">
                            Edit Client
                        </a>
                        <a href="{{ route('client.index') }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid rgba(255,255,255,.5);border-radius:3px;text-decoration:none;">
                            &larr; All Clients
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

            @if(session('success'))
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#166534;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#991b1b;">{{ session('error') }}</div>
            @endif

            <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;align-items:start;">

                {{-- Left column --}}
                <div>
                    {{-- Company Details --}}
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                        <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                            <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Company Details</h3>
                        </div>
                        <div style="padding:20px 24px;">
                            <dl style="display:grid;grid-template-columns:1fr 2fr;gap:12px 16px;">
                                <dt style="font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#64748b;padding-top:2px;">Company</dt>
                                <dd style="font-size:13px;color:#1e293b;font-weight:600;margin:0;">{{ $client->company_name }}</dd>

                                <dt style="font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#64748b;padding-top:2px;">Address</dt>
                                <dd style="font-size:13px;color:#374151;margin:0;white-space:pre-line;">{{ $client->address }}</dd>

                                <dt style="font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#64748b;padding-top:2px;">Phone</dt>
                                <dd style="font-size:13px;color:#374151;margin:0;">{{ $client->company_phone ?? '—' }}</dd>

                                <dt style="font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#64748b;padding-top:2px;">Registered</dt>
                                <dd style="font-size:13px;color:#374151;margin:0;">{{ $client->created_at->format('d F Y') }}</dd>
                            </dl>
                        </div>
                    </div>

                    {{-- Responsible Officer --}}
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                        <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                            <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Responsible Officer</h3>
                        </div>
                        <div style="padding:20px 24px;">
                            <dl style="display:grid;grid-template-columns:1fr 2fr;gap:12px 16px;">
                                <dt style="font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#64748b;padding-top:2px;">Name</dt>
                                <dd style="font-size:13px;color:#374151;margin:0;">{{ $client->responsible_officer_name ?? '—' }}</dd>

                                <dt style="font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#64748b;padding-top:2px;">Email</dt>
                                <dd style="font-size:13px;color:#374151;margin:0;">{{ $client->responsible_officer_email ?? '—' }}</dd>

                                <dt style="font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#64748b;padding-top:2px;">Phone</dt>
                                <dd style="font-size:13px;color:#374151;margin:0;">{{ $client->responsible_officer_phone ?? '—' }}</dd>
                            </dl>
                        </div>
                    </div>

                    {{-- Service Agreement --}}
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                        <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                            <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Service Agreement</h3>
                        </div>
                        <div style="padding:20px 24px;">
                            @if($client->service_agreement_signed_at)
                                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:4px;padding:12px 16px;margin-bottom:16px;font-size:13px;color:#166534;">
                                    Agreement signed on {{ $client->service_agreement_signed_at->format('d F Y \a\t H:i') }}.
                                    Valid until {{ $client->service_agreement_signed_at->addYear()->format('d F Y') }}.
                                </div>
                                <dl style="display:grid;grid-template-columns:1fr 2fr;gap:10px 16px;">
                                    <dt style="font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#64748b;">Signed By</dt>
                                    <dd style="font-size:13px;color:#374151;margin:0;">{{ $client->responsible_officer_name ?? '—' }}</dd>

                                    <dt style="font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#64748b;">Method</dt>
                                    <dd style="font-size:13px;color:#374151;margin:0;text-transform:capitalize;">{{ $client->signature_type ?? '—' }}</dd>

                                    @if($client->director_signed_at)
                                        <dt style="font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#64748b;">Countersigned</dt>
                                        <dd style="font-size:13px;color:#374151;margin:0;">
                                            {{ $client->director_signed_by }} — {{ $client->director_signed_at->format('d F Y') }}
                                        </dd>
                                    @else
                                        <dt style="font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#64748b;">Countersign</dt>
                                        <dd style="font-size:13px;color:#b8922a;margin:0;font-weight:600;">Awaiting director</dd>
                                    @endif
                                </dl>

                                @if($client->signature_data)
                                    <div style="margin-top:16px;">
                                        <p style="font-size:11px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#64748b;margin-bottom:8px;">Signature</p>
                                        <div style="border:1px solid #e2e8f0;border-radius:3px;padding:12px;background:#f8fafc;display:inline-block;">
                                            <img src="{{ $client->signature_data }}"
                                                 alt="Client signature"
                                                 style="max-height:80px;max-width:240px;object-fit:contain;">
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div style="background:#fef9c3;border:1px solid #fef08a;border-left:4px solid #b8922a;border-radius:4px;padding:12px 16px;font-size:13px;color:#854d0e;">
                                    Service agreement has not been signed yet.
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Internal Notes --}}
                    @if($client->internal_notes)
                        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                            <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                                <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Internal Notes</h3>
                            </div>
                            <div style="padding:20px 24px;">
                                <p style="font-size:13px;color:#374151;margin:0;white-space:pre-line;">{{ $client->internal_notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Right column --}}
                <div>
                    {{-- Account --}}
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                        <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                            <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Portal Account</h3>
                        </div>
                        <div style="padding:20px 24px;">
                            @if($client->user)
                                <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0 0 4px;">{{ $client->user->name }}</p>
                                <p style="font-size:12px;color:#64748b;margin:0 0 12px;">{{ $client->user->email }}</p>
                                <p style="font-size:11px;color:#64748b;margin:0;">
                                    Last login: {{ $client->user->last_login_at ? $client->user->last_login_at->format('d M Y H:i') : 'Never' }}
                                </p>
                            @else
                                <p style="font-size:13px;color:#64748b;margin:0;">No user account linked.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Quick Stats --}}
                    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                        <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                            <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Activity</h3>
                        </div>
                        <div style="padding:20px 24px;">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                                <div style="text-align:center;padding:12px;background:#f8fafc;border-radius:3px;border:1px solid #e2e8f0;">
                                    <p style="font-size:20px;font-weight:700;color:#1a2f4e;margin:0;">{{ $client->submissions->count() }}</p>
                                    <p style="font-size:10px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#64748b;margin:4px 0 0;">Submissions</p>
                                </div>
                                <div style="text-align:center;padding:12px;background:#f8fafc;border-radius:3px;border:1px solid #e2e8f0;">
                                    <p style="font-size:20px;font-weight:700;color:#b8922a;margin:0;">
                                        {{ $client->service_agreement_signed_at ? 'Yes' : 'No' }}
                                    </p>
                                    <p style="font-size:10px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#64748b;margin:4px 0 0;">Signed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Danger Zone --}}
                    <div style="background:#fff;border:1px solid #fecaca;border-radius:4px;overflow:hidden;">
                        <div style="padding:16px 24px;border-bottom:1px solid #fecaca;background:#fef2f2;">
                            <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#991b1b;margin:0;">Danger Zone</h3>
                        </div>
                        <div style="padding:20px 24px;">
                            <p style="font-size:12px;color:#64748b;margin:0 0 12px;">Permanently delete this client record and associated data.</p>
                            <form method="POST" action="{{ route('client.destroy', $client->id) }}" onsubmit="return confirm('Are you sure? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#dc2626;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                                    Delete Client
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
