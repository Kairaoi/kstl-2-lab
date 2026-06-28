{{-- resources/views/kstl/client/index.blade.php --}}
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
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Client Accounts</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Manage registered client organisations</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <a href="{{ route('client.create') }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#b8922a;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;text-decoration:none;">
                            + New Client
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

            {{-- Search / Filter --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;padding:16px 20px;margin-bottom:20px;">
                <form method="GET" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
                    <div style="flex:1;min-width:200px;">
                        <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Company name, officer..."
                               style="width:100%;padding:8px 12px;border:1px solid #cbd5e1;border-radius:3px;font-size:13px;color:#1e293b;background:#fff;">
                    </div>
                    <div>
                        <button type="submit" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#1a2f4e;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                            Search
                        </button>
                    </div>
                    @if(request('search'))
                        <div>
                            <a href="{{ route('client.index') }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                                Clear
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            {{-- Clients Table --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;">
                    <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Registered Clients</h3>
                    @isset($clients)
                        <span style="font-size:12px;color:#64748b;">{{ $clients->total() ?? count($clients) }} total</span>
                    @endisset
                </div>
                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;">
                        <thead>
                            <tr style="background:#1a2f4e;">
                                <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">#</th>
                                <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Company</th>
                                <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Responsible Officer</th>
                                <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Phone</th>
                                <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Agreement</th>
                                <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Registered</th>
                                <th style="padding:10px 16px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients as $client)
                                <tr style="border-bottom:1px solid #f1f5f9;{{ $loop->even ? 'background:#f8fafc;' : '' }}">
                                    <td style="padding:12px 16px;font-size:12px;color:#64748b;">{{ $loop->iteration }}</td>
                                    <td style="padding:12px 16px;font-size:13px;color:#1a2f4e;font-weight:600;">
                                        {{ $client->company_name }}
                                        @if($client->trashed())
                                            <span style="display:inline-block;margin-left:6px;padding:1px 6px;background:#fee2e2;color:#dc2626;font-size:9px;font-weight:700;border-radius:2px;text-transform:uppercase;">Deleted</span>
                                        @endif
                                    </td>
                                    <td style="padding:12px 16px;font-size:13px;color:#374151;">{{ $client->responsible_officer_name ?? '—' }}</td>
                                    <td style="padding:12px 16px;font-size:13px;color:#374151;">{{ $client->company_phone ?? '—' }}</td>
                                    <td style="padding:12px 16px;">
                                        @if($client->service_agreement_signed_at)
                                            <span style="display:inline-flex;align-items:center;gap:4px;padding:2px 8px;background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;font-size:10px;font-weight:700;border-radius:2px;text-transform:uppercase;">
                                                Signed
                                            </span>
                                        @else
                                            <span style="display:inline-flex;align-items:center;gap:4px;padding:2px 8px;background:#fef9c3;border:1px solid #fef08a;color:#854d0e;font-size:10px;font-weight:700;border-radius:2px;text-transform:uppercase;">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding:12px 16px;font-size:12px;color:#64748b;">{{ $client->created_at->format('d M Y') }}</td>
                                    <td style="padding:12px 16px;">
                                        <div style="display:flex;gap:6px;">
                                            <a href="{{ route('client.show', $client->id) }}" style="display:inline-flex;align-items:center;padding:4px 10px;background:#1a2f4e;color:#fff;font-size:11px;font-weight:700;border-radius:2px;text-decoration:none;letter-spacing:.04em;">View</a>
                                            <a href="{{ route('client.edit', $client->id) }}" style="display:inline-flex;align-items:center;padding:4px 10px;background:#fff;color:#1a2f4e;font-size:11px;font-weight:700;border:1px solid #1a2f4e;border-radius:2px;text-decoration:none;letter-spacing:.04em;">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="padding:40px 16px;text-align:center;font-size:13px;color:#64748b;">
                                        No clients found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            @isset($clients)
                @if(method_exists($clients, 'links'))
                    <div style="padding:8px 0;">
                        {{ $clients->links() }}
                    </div>
                @endif
            @endisset

        </div>
    </div>
</x-app-layout>
