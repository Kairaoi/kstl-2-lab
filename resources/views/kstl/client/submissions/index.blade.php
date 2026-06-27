{{-- resources/views/kstl/client/submissions/index.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="position:absolute;inset:0;opacity:.04;background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:12px 12px;pointer-events:none;"></div>
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:20px;position:relative;">
                    <div style="display:flex;align-items:center;gap:18px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="Ministry of Fisheries &amp; Ocean Resources" style="width:56px;height:56px;object-fit:contain;filter:brightness(0) invert(1);opacity:.92;">
                        <div>
                            <p style="font-size:8.5px;font-weight:700;letter-spacing:.22em;text-transform:uppercase;color:#b8922a;margin-bottom:5px;">
                                Client Portal &nbsp;·&nbsp; Seafood Toxicology Laboratory
                            </p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#ffffff;line-height:1.2;margin:0;">
                                My Submissions
                            </h1>
                            <p style="font-size:11px;color:#94a3b8;margin-top:4px;">
                                Track all your laboratory sample submissions
                            </p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                        @if($client && $client->service_agreement_signed_at)
                            <a href="{{ route('client.submissions.create') }}"
                               style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:rgba(255,255,255,.12);color:#fff;font-size:12px;font-weight:600;letter-spacing:.04em;border-radius:3px;text-decoration:none;border:1px solid rgba(255,255,255,.25);">
                                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                New Submission
                            </a>
                        @endif
                        <a href="{{ route('client.dashboard') }}" style="display:inline-flex;align-items:center;gap:5px;font-size:11px;font-weight:600;color:#94a3b8;text-decoration:none;">
                            <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Dashboard
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
        .app-main { padding-left: 0 !important; padding-right: 0 !important;
                    padding-top: 0 !important; max-width: 100% !important; }
    </style>
    @endpush

    <div style="background:#f1f5f9;min-height:100vh;padding:52px 0 56px;">
        <div style="max-width:80rem;margin:0 auto;padding:0 2rem;display:flex;flex-direction:column;gap:24px;">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div style="border-left:4px solid #16a34a;padding:12px 18px;border-radius:0 4px 4px 0;background:#f0fdf4;margin-bottom:16px;font-size:13px;color:#166534;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="border-left:4px solid #dc2626;padding:12px 18px;border-radius:0 4px 4px 0;background:#fef2f2;margin-bottom:16px;font-size:13px;color:#991b1b;">
                    {{ session('error') }}
                </div>
            @endif

            {{-- ── Pending Consent Banner ───────────────────────────────── --}}
            @if($pendingConsents->isNotEmpty())
                <div style="background:#fff;border:1px solid #fca5a5;border-radius:4px;overflow:hidden;margin-bottom:20px;">
                    <div style="padding:14px 18px;border-bottom:1px solid #fecaca;display:flex;align-items:flex-start;gap:10px;background:#fef2f2;">
                        <svg style="width:18px;height:18px;flex-shrink:0;margin-top:1px;color:#dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        <div>
                            <p style="font-size:13px;font-weight:700;color:#991b1b;margin:0;">Action Required — Sample Assessment</p>
                            <p style="font-size:11px;color:#b91c1c;margin:4px 0 0;">
                                One or more of your samples did not pass the laboratory assessment. Please review and indicate your decision below.
                            </p>
                        </div>
                    </div>
                    @foreach($pendingConsents as $consent)
                        @php
                            $sample     = $consent->sample;
                            $submission = $sample->submission;
                            $consentUrl = route('client.consent.show', $consent->consent_token);
                        @endphp
                        <div style="padding:14px 18px;border-bottom:1px solid #fee2e2;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                            <div>
                                <p style="font-size:13px;font-weight:600;color:#7f1d1d;margin:0;">{{ $sample->common_name }}</p>
                                <p style="font-size:11px;color:#b91c1c;margin:3px 0 0;">
                                    Ref: <span style="font-family:monospace;">{{ $submission->reference_number }}</span>
                                    @if($consent->assessed_at ?? $consent->created_at)
                                        &middot; Assessed {{ ($consent->assessed_at ?? $consent->created_at)->format('d M Y') }}
                                    @endif
                                </p>
                                @if($consent->rejection_reason)
                                    <p style="font-size:11px;color:#b91c1c;margin:4px 0 0;font-style:italic;">{{ $consent->rejection_reason }}</p>
                                @endif
                            </div>
                            <a href="{{ $consentUrl }}"
                               style="background:#dc2626;color:#fff;padding:7px 16px;border-radius:3px;font-size:12px;font-weight:600;text-decoration:none;flex-shrink:0;display:inline-flex;align-items:center;gap:6px;">
                                Review &amp; Decide
                                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Service Agreement Warning --}}
            @if($client && !$client->service_agreement_signed_at)
                <div style="border-left:4px solid #d97706;padding:12px 18px;border-radius:0 4px 4px 0;background:#fffbeb;margin-bottom:20px;font-size:13px;color:#92400e;">
                    <strong>Service agreement required.</strong>
                    You must sign the service agreement before submitting samples. Please contact the lab.
                </div>
            @endif

            {{-- Filters & Search --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;padding:16px 18px;margin-bottom:20px;">
                <form method="GET" action="{{ route('client.submissions.index') }}"
                      style="display:flex;flex-wrap:wrap;gap:10px;align-items:center;">

                    <div style="flex:1;min-width:180px;position:relative;">
                        <svg style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:14px;height:14px;" fill="none" stroke="#9ca3af" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by reference or sample name..."
                               style="width:100%;padding:8px 12px 8px 32px;font-size:12px;border:1px solid #e2e8f0;border-radius:3px;box-sizing:border-box;color:#374151;"/>
                    </div>

                    <select name="status"
                            style="font-size:12px;border:1px solid #e2e8f0;border-radius:3px;padding:8px 12px;color:#374151;background:#fff;">
                        <option value="">All Statuses</option>
                        <option value="submitted"              {{ request('status') === 'submitted'              ? 'selected' : '' }}>Submitted</option>
                        <option value="received"               {{ request('status') === 'received'               ? 'selected' : '' }}>Received</option>
                        <option value="assessing"              {{ request('status') === 'assessing'              ? 'selected' : '' }}>Assessing</option>
                        <option value="accepted"               {{ request('status') === 'accepted'               ? 'selected' : '' }}>Accepted</option>
                        <option value="rejected"               {{ request('status') === 'rejected'               ? 'selected' : '' }}>Rejected</option>
                        <option value="testing"                {{ request('status') === 'testing'                ? 'selected' : '' }}>Testing</option>
                        <option value="awaiting_authorisation" {{ request('status') === 'awaiting_authorisation' ? 'selected' : '' }}>Awaiting Authorisation</option>
                        <option value="authorised"             {{ request('status') === 'authorised'             ? 'selected' : '' }}>Authorised</option>
                        <option value="completed"              {{ request('status') === 'completed'              ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled"              {{ request('status') === 'cancelled'              ? 'selected' : '' }}>Cancelled</option>
                    </select>

                    <input type="date"
                           name="from"
                           value="{{ request('from') }}"
                           style="font-size:12px;border:1px solid #e2e8f0;border-radius:3px;padding:8px 12px;color:#374151;"/>

                    <button type="submit"
                            style="background:#1a2f4e;color:#fff;padding:8px 18px;border-radius:3px;font-size:12px;font-weight:600;border:none;cursor:pointer;">
                        Filter
                    </button>

                    @if(request()->hasAny(['search', 'status', 'from']))
                        <a href="{{ route('client.submissions.index') }}"
                           style="border:1px solid #e2e8f0;color:#374151;padding:8px 16px;border-radius:3px;font-size:12px;background:#fff;text-decoration:none;">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            {{-- Submissions Table --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">

                <div style="padding:14px 18px;border-bottom:2px solid #b8922a;display:flex;align-items:center;justify-content:space-between;">
                    <h3 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0;padding-bottom:0;">
                        All Submissions
                        @if(isset($submissions) && method_exists($submissions, 'total'))
                            <span style="font-size:12px;color:#9ca3af;font-weight:400;margin-left:8px;">({{ $submissions->total() }} total)</span>
                        @endif
                    </h3>
                </div>

                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;">
                        <thead>
                            <tr style="background:#1a2f4e;">
                                <th style="text-align:left;padding:9px 16px;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;white-space:nowrap;">Reference</th>
                                <th style="text-align:left;padding:9px 16px;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;white-space:nowrap;">Sample Name</th>
                                <th style="text-align:left;padding:9px 16px;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;white-space:nowrap;">Tests Requested</th>
                                <th style="text-align:left;padding:9px 16px;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;white-space:nowrap;">Submitted</th>
                                <th style="text-align:left;padding:9px 16px;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;white-space:nowrap;">Status</th>
                                <th style="text-align:left;padding:9px 16px;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;white-space:nowrap;">Result</th>
                                <th style="padding:9px 16px;"></th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($submissions as $i => $submission)
                                <tr style="background:{{ $i % 2 === 0 ? '#fff' : '#f8fafc' }};border-bottom:1px solid #f1f5f9;">

                                    {{-- Reference --}}
                                    <td style="padding:11px 16px;">
                                        <span style="font-family:monospace;font-size:12px;font-weight:600;color:#1a2f4e;">
                                            {{ $submission->reference_number }}
                                        </span>
                                    </td>

                                    {{-- Sample Name + Type --}}
                                    <td style="padding:11px 16px;">
                                        <p style="font-size:12.5px;font-weight:600;color:#1e293b;margin:0;">{{ $submission->sample_name }}</p>
                                        <p style="font-size:11px;color:#9ca3af;margin:2px 0 0;text-transform:capitalize;">{{ $submission->sample_type }}</p>
                                    </td>

                                    {{-- Tests Requested --}}
                                    <td style="padding:11px 16px;">
                                        @php
                                            $tests = is_array($submission->tests_requested)
                                                ? $submission->tests_requested
                                                : json_decode($submission->tests_requested ?? '[]', true) ?? [];
                                        @endphp
                                        @if(count($tests))
                                            @php
                                                $indexTestLabels = [
                                                    'total_coliforms'        => 'Total Coliforms',
                                                    'e_coli'                 => 'E. coli',
                                                    'enterococci'            => 'Enterococci',
                                                    'faecal_coliforms'       => 'Faecal Coliforms',
                                                    'yeast_mold'             => 'Yeast & Mould',
                                                    'apc'                    => 'APC',
                                                    'e_coli_coliform'        => 'E. coli & Coliform',
                                                    'staph_aureus'           => 'S. aureus',
                                                    'salmonella_spp'         => 'Salmonella spp.',
                                                    'listeria_mono'          => 'L. monocytogenes',
                                                    'listeria_spp'           => 'Listeria spp.',
                                                    'e_coli_colilert'        => 'E. coli (Colilert)',
                                                    'enterococci_enterolert' => 'Enterococci (Enterolert)',
                                                    'moisture'               => 'Moisture',
                                                    'histamine'              => 'Histamine',
                                                    'ph'                     => 'pH',
                                                    'conductivity'           => 'Conductivity',
                                                    'water_activity'         => 'Water Activity',
                                                ];
                                            @endphp
                                            <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                                @foreach(array_slice($tests, 0, 3) as $test)
                                                    <span style="display:inline-flex;padding:2px 8px;font-size:10px;background:#eff6ff;color:#1d4ed8;border-radius:999px;font-weight:600;">
                                                        {{ $indexTestLabels[$test] ?? ucwords(str_replace('_', ' ', $test)) }}
                                                    </span>
                                                @endforeach
                                                @if(count($tests) > 3)
                                                    <span style="font-size:10px;color:#9ca3af;">+{{ count($tests) - 3 }} more</span>
                                                @endif
                                            </div>
                                        @else
                                            <span style="color:#9ca3af;font-size:12px;">—</span>
                                        @endif
                                    </td>

                                    {{-- Submitted date --}}
                                    <td style="padding:11px 16px;font-size:12.5px;color:#6b7280;white-space:nowrap;">
                                        {{ $submission->submitted_at?->format('d M Y') ?? $submission->created_at->format('d M Y') }}
                                    </td>

                                    {{-- Status badge --}}
                                    <td style="padding:11px 16px;">
                                        @php
                                            $statusConfig = [
                                                'submitted'              => ['background:#fefce8;color:#854d0e;',  'Submitted'],
                                                'received'               => ['background:#eff6ff;color:#1e40af;',  'Received'],
                                                'assessing'              => ['background:#faf5ff;color:#6b21a8;',  'Assessing'],
                                                'accepted'               => ['background:#f0fdf4;color:#166534;',  'Accepted'],
                                                'rejected'               => ['background:#fef2f2;color:#991b1b;',  'Rejected'],
                                                'consent_to_proceed'     => ['background:#fff7ed;color:#9a3412;',  'Consent to Proceed'],
                                                'testing'                => ['background:#eef2ff;color:#3730a3;',  'Testing'],
                                                'awaiting_authorisation' => ['background:#fffbeb;color:#92400e;',  'Awaiting Auth.'],
                                                'authorised'             => ['background:#f0fdfa;color:#065f46;',  'Authorised'],
                                                'completed'              => ['background:#f0fdf4;color:#166534;',  'Completed'],
                                                'cancelled'              => ['background:#f8fafc;color:#6b7280;',  'Cancelled'],
                                            ];
                                            $sc = $statusConfig[$submission->status] ?? ['background:#f8fafc;color:#6b7280;', ucfirst($submission->status)];
                                        @endphp
                                        <span style="display:inline-flex;align-items:center;border-radius:999px;padding:2px 12px;font-size:10px;font-weight:700;white-space:nowrap;{{ $sc[0] }}">
                                            {{ $sc[1] }}
                                        </span>
                                    </td>

                                    {{-- Result --}}
                                    <td style="padding:11px 16px;">
                                        @if($submission->hasResult())
                                            <span style="display:inline-flex;align-items:center;gap:4px;font-size:10px;font-weight:700;border-radius:999px;padding:2px 12px;background:#f0fdf4;color:#166534;">
                                                <svg style="width:11px;height:11px;" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                                                </svg>
                                                Ready
                                            </span>
                                        @else
                                            <span style="font-size:10px;font-weight:700;border-radius:999px;padding:2px 12px;background:#f8fafc;color:#9ca3af;">Pending</span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td style="padding:11px 16px;text-align:right;white-space:nowrap;">
                                        <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px;">
                                            <a href="{{ route('client.submissions.show', $submission->id) }}"
                                               style="border:1px solid #e2e8f0;color:#374151;padding:5px 12px;border-radius:3px;font-size:11px;background:#fff;text-decoration:none;font-weight:600;">
                                                View
                                            </a>
                                            @if($submission->isEditable())
                                                <a href="{{ route('client.submissions.edit', $submission->id) }}"
                                                   style="background:#1a2f4e;color:#fff;padding:5px 12px;border-radius:3px;font-size:11px;font-weight:600;text-decoration:none;">
                                                    Edit
                                                </a>
                                            @endif
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="padding:56px 24px;text-align:center;">
                                        <svg style="width:40px;height:40px;color:#e2e8f0;margin:0 auto 16px;display:block;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p style="color:#9ca3af;font-size:13px;font-weight:600;margin:0 0 4px;">No submissions yet</p>
                                        <p style="color:#cbd5e1;font-size:12px;margin:0 0 16px;">Submit your first sample to get started.</p>
                                        @if($client && $client->service_agreement_signed_at)
                                            <a href="{{ route('client.submissions.create') }}"
                                               style="background:#1a2f4e;color:#fff;padding:8px 18px;border-radius:3px;font-size:12px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                                                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                                New Submission
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if(isset($submissions) && method_exists($submissions, 'hasPages') && $submissions->hasPages())
                    <div style="padding:14px 18px;border-top:1px solid #f1f5f9;">
                        {{ $submissions->links() }}
                    </div>
                @endif

            </div>

        </div>
    </div>
</x-app-layout>
