{{-- resources/views/kstl/director/agreements/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Director</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Service Agreements</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Countersign and manage client service agreements</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <span style="font-size:12px;color:#94a3b8;">Fully executed:</span>
                        <span style="display:inline-flex;padding:5px 14px;font-size:12px;font-weight:700;background:#dcfce7;color:#166534;border-radius:3px;">{{ $totalSigned }}</span>
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

            {{-- ── Pending Countersign ──────────────────────────── --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                    <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0 0 4px;">
                        Awaiting Your Countersignature
                        @if($pending->isNotEmpty())
                            <span style="display:inline-flex;margin-left:8px;padding:2px 8px;font-size:11px;font-weight:600;background:#fef9c3;color:#854d0e;border-radius:9999px;">
                                {{ $pending->count() }} pending
                            </span>
                        @endif
                    </h3>
                    <p style="font-size:12px;color:#94a3b8;margin:0;">
                        These clients have signed the service agreement and are waiting for your countersignature.
                    </p>
                </div>

                @if($pending->isEmpty())
                    <div style="padding:48px 24px;text-align:center;">
                        <p style="font-size:13px;color:#94a3b8;">No agreements pending countersignature.</p>
                    </div>
                @else
                    <div style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;">
                            <thead>
                                <tr style="background:#1a2f4e;">
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Company</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Officer</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Email</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Client Signed</th>
                                    <th style="padding:10px 16px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pending as $client)
                                    <tr style="border-bottom:1px solid #f1f5f9;{{ $loop->even ? 'background:#f8fafc;' : '' }}">
                                        <td style="padding:12px 16px;">
                                            <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0;">{{ $client->company_name }}</p>
                                        </td>
                                        <td style="padding:12px 16px;font-size:13px;color:#374151;">
                                            {{ $client->responsible_officer_name ?? '—' }}
                                        </td>
                                        <td style="padding:12px 16px;font-size:12px;color:#64748b;">
                                            {{ $client->user?->email ?? '—' }}
                                        </td>
                                        <td style="padding:12px 16px;font-size:12px;color:#64748b;">
                                            {{ $client->service_agreement_signed_at?->format('d M Y \a\t H:i') ?? '—' }}
                                        </td>
                                        <td style="padding:12px 16px;text-align:right;">
                                            <a href="{{ route('director.agreements.show', $client->id) }}"
                                               style="display:inline-flex;align-items:center;gap:8px;padding:7px 16px;background:#0d9488;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;text-decoration:none;">
                                                Countersign
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- ── Fully Executed Agreements ───────────────────────── --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;">
                    <div>
                        <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0 0 4px;">Fully Executed Agreements</h3>
                        <p style="font-size:12px;color:#94a3b8;margin:0;">Both client and director have signed</p>
                    </div>
                    <span style="display:inline-flex;padding:4px 12px;font-size:12px;font-weight:600;background:#ccfbf1;color:#0f766e;border:1px solid #99f6e4;border-radius:9999px;">
                        {{ $executed->count() }} executed
                    </span>
                </div>

                @if($executed->isEmpty())
                    <div style="padding:48px 24px;text-align:center;">
                        <p style="font-size:13px;color:#94a3b8;">No fully executed agreements yet.</p>
                    </div>
                @else
                    <div style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;">
                            <thead>
                                <tr style="background:#1a2f4e;">
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Company</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Responsible Officer</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Email</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Client Signed</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Director Signed</th>
                                    <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Signature Type</th>
                                    <th style="padding:10px 16px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($executed as $client)
                                    <tr style="border-bottom:1px solid #f1f5f9;{{ $loop->even ? 'background:#f8fafc;' : '' }}">
                                        <td style="padding:12px 16px;">
                                            <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0;">{{ $client->company_name }}</p>
                                            <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">{{ $client->address }}</p>
                                        </td>
                                        <td style="padding:12px 16px;font-size:13px;color:#374151;">
                                            {{ $client->responsible_officer_name ?? '—' }}
                                        </td>
                                        <td style="padding:12px 16px;font-size:12px;color:#64748b;">
                                            {{ $client->user?->email ?? '—' }}
                                        </td>
                                        <td style="padding:12px 16px;font-size:12px;color:#64748b;">
                                            {{ $client->service_agreement_signed_at?->format('d M Y') ?? '—' }}
                                            <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">{{ $client->service_agreement_signed_at?->format('H:i') }}</p>
                                        </td>
                                        <td style="padding:12px 16px;font-size:12px;color:#64748b;">
                                            {{ $client->director_signed_at?->format('d M Y') ?? '—' }}
                                            <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">{{ $client->director_signed_at?->format('H:i') }}</p>
                                        </td>
                                        <td style="padding:12px 16px;">
                                            <span style="display:inline-flex;padding:2px 8px;font-size:11px;font-weight:600;border-radius:9999px;text-transform:capitalize;{{ $client->signature_type === 'drawn' ? 'background:#dbeafe;color:#1e40af;' : 'background:#f3e8ff;color:#6b21a8;' }}">
                                                {{ $client->signature_type === 'drawn' ? 'Drawn' : 'Uploaded' }}
                                            </span>
                                        </td>
                                        <td style="padding:12px 16px;text-align:right;">
                                            <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;">
                                                <a href="{{ route('director.agreements.show', $client->id) }}"
                                                   style="display:inline-flex;align-items:center;gap:8px;padding:6px 14px;background:#fff;color:#1a2f4e;font-size:11px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                                                    View
                                                </a>
                                                <a href="{{ route('director.agreements.download', $client->id) }}"
                                                   style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;background:#fff;color:#0d9488;font-size:11px;font-weight:700;letter-spacing:.06em;border:1px solid #0d9488;border-radius:3px;text-decoration:none;">
                                                    PDF
                                                </a>
                                            </div>
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
