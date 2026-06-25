{{-- resources/views/kstl/analyst/results/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Analyst Portal</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">{{ $submission->reference_number }}</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">{{ $submission->client->company_name ?? '' }}</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <button onclick="window.print()"
                                class="no-print"
                                style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;cursor:pointer;">
                            <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Print
                        </button>
                        <a href="{{ route('analyst.results.index') }}"
                           class="no-print"
                           style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:rgba(255,255,255,.12);color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid rgba(255,255,255,.3);border-radius:3px;text-decoration:none;">
                            ← Back to results
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

        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .page-hdr, .gov-stripe, .gov-top, nav, .app-footer { display: none !important; }
        }
    </style>
    @endpush

    <div style="background:#f1f5f9;min-height:100vh;padding:52px 0 56px;">
        <div style="max-width:80rem;margin:0 auto;padding:0 2rem;">

            {{-- Read-only notice --}}
            <div class="no-print" style="background:#eff6ff;border:1px solid #bfdbfe;border-left:4px solid #3b82f6;border-radius:4px;padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:8px;">
                <svg style="width:16px;height:16px;color:#3b82f6;flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p style="font-size:12px;color:#1e40af;margin:0;">This is the authorised report as signed off by the Director. It is read-only.</p>
            </div>

            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">

                {{-- Letterhead --}}
                <div style="padding:28px 32px;border-bottom:3px double #1a2f4e;background:linear-gradient(180deg,#fbfaf8 0%,#ffffff 100%);">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:24px;flex-wrap:wrap;">
                        <div style="display:flex;align-items:flex-start;gap:16px;">
                            <div style="width:48px;height:48px;border-radius:50%;border:2px solid #b8922a;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <svg style="width:24px;height:24px;stroke:#1a2f4e;" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                                </svg>
                            </div>
                            <div>
                                <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Government of Kiribati &middot; Ministry of Fisheries &amp; Ocean Resources</p>
                                <h1 style="font-family:'Georgia',serif;font-size:18px;font-weight:700;color:#1a2f4e;margin:0 0 6px;">Kiribati Seafood Toxicology Laboratory</h1>
                                <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="height:24px;object-fit:contain;object-position:left;">
                            </div>
                        </div>
                        <div style="text-align:right;flex-shrink:0;">
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Results</p>
                            <p style="font-family:monospace;font-size:14px;font-weight:700;color:#1e293b;margin:0 0 4px;">{{ $submission->reference_number }}</p>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">{{ $submission->client->company_name ?? '' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Authorisation strip --}}
                <div style="padding:20px 32px;display:flex;align-items:center;justify-content:space-between;gap:24px;border-bottom:1px solid #e2e8f0;background:#f8fafc;flex-wrap:wrap;">
                    <div>
                        <p style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Authorisation Status</p>
                        @if($result?->authorised_at)
                            <p style="font-size:13px;font-weight:700;color:#166534;margin:0;">Authorised</p>
                        @else
                            <p style="font-size:13px;color:#94a3b8;font-style:italic;margin:0;">Awaiting Director authorisation</p>
                        @endif
                    </div>

                    <div style="text-align:center;">
                        <p style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Prepared For</p>
                        <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0 0 2px;">{{ $submission->client->company_name }}</p>
                        <p style="font-size:12px;color:#64748b;margin:0;">{{ $submission->client->user->email ?? '' }}</p>
                    </div>

                    @if($result?->authorised_at)
                        <div style="text-align:right;">
                            <p style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Authorised By</p>
                            <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0 0 2px;">{{ $result->authorisedBy?->name ?? 'Laboratory Director' }}</p>
                            <p style="font-size:12px;color:#64748b;margin:0 0 2px;">Laboratory Director</p>
                            <p style="font-size:11px;color:#94a3b8;margin:0;">{{ $result->authorised_at->format('d M Y \a\t H:i') }}</p>
                        </div>
                    @endif
                </div>

                {{-- Submission particulars --}}
                <div style="padding:24px 32px;border-bottom:1px solid #e2e8f0;">
                    <h2 style="font-family:'Georgia',serif;font-size:16px;font-weight:700;color:#1a2f4e;margin:0 0 16px;padding-bottom:8px;border-bottom:2px solid #b8922a;">Submission Particulars</h2>
                    <dl style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px 24px;margin-bottom:20px;">
                        <div>
                            <dt style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Reference</dt>
                            <dd style="font-family:monospace;font-size:13px;color:#1e293b;margin:0;">{{ $submission->reference_number }}</dd>
                        </div>
                        <div>
                            <dt style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Collected</dt>
                            <dd style="font-size:13px;color:#374151;margin:0;">{{ $submission->collected_at?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        @if($submission->delivered_at)
                        <div>
                            <dt style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Delivered</dt>
                            <dd style="font-size:13px;color:#374151;margin:0;">{{ $submission->delivered_at->format('d M Y') }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Submitted</dt>
                            <dd style="font-size:13px;color:#374151;margin:0;">{{ $submission->submitted_at?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Date of Issue</dt>
                            <dd style="font-size:13px;color:#374151;margin:0;">{{ $result?->authorised_at?->format('d M Y') ?? '—' }}</dd>
                        </div>
                    </dl>

                    <p style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 8px;">Samples Submitted ({{ $samples->count() }})</p>
                    <table style="width:100%;border-collapse:collapse;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">
                        <thead>
                            <tr style="background:#1a2f4e;">
                                <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">#</th>
                                <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Common Name</th>
                                <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Scientific Name</th>
                                <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Sample Code</th>
                                <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($samples as $i => $sample)
                                <tr style="border-bottom:1px solid #f1f5f9;{{ $loop->even ? 'background:#f8fafc;' : '' }}">
                                    <td style="padding:10px 12px;font-family:monospace;font-size:11px;color:#94a3b8;">{{ $i + 1 }}</td>
                                    <td style="padding:10px 12px;font-size:13px;font-weight:600;color:#1e293b;">{{ $sample->common_name ?? '—' }}</td>
                                    <td style="padding:10px 12px;font-size:12px;font-style:italic;color:#64748b;">{{ $sample->scientific_name ?? '—' }}</td>
                                    <td style="padding:10px 12px;font-family:monospace;font-size:11px;color:#64748b;">{{ $sample->sample_code }}</td>
                                    <td style="padding:10px 12px;font-size:12px;color:#374151;">{{ $sample->quantity ?? '—' }} {{ $sample->quantity_unit ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Director's remarks --}}
                @if($result && $result->director_comments)
                    <div style="padding:24px 32px;border-bottom:1px solid #e2e8f0;">
                        <h2 style="font-family:'Georgia',serif;font-size:16px;font-weight:700;color:#1a2f4e;margin:0 0 12px;padding-bottom:8px;border-bottom:2px solid #b8922a;">Director's Remarks</h2>
                        <p style="font-size:13px;color:#374151;line-height:1.6;white-space:pre-line;margin:0;">{{ $result->director_comments }}</p>
                    </div>
                @endif

                {{-- Analytical results --}}
                <div style="padding:24px 32px;">
                    <h2 style="font-family:'Georgia',serif;font-size:16px;font-weight:700;color:#1a2f4e;margin:0 0 16px;padding-bottom:8px;border-bottom:2px solid #b8922a;">Analytical Results</h2>

                    @php
                        $sopDocuments = \App\Models\Kstl\Document::where('category', 'sop')
                            ->whereIn('reference_code', array_values(\App\Models\Kstl\SampleTest::TEST_SOPS))
                            ->with('currentVersion')
                            ->get()
                            ->keyBy('reference_code');
                    @endphp

                    @foreach($samples as $sample)
                        <div style="margin-bottom:24px;">
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                                <div>
                                    <h4 style="font-size:13px;font-weight:700;color:#1e293b;margin:0 0 2px;">
                                        {{ $sample->common_name ?? $sample->sample_code }}
                                    </h4>
                                    @if($sample->scientific_name)
                                        <p style="font-size:12px;color:#94a3b8;font-style:italic;margin:0 0 2px;">{{ $sample->scientific_name }}</p>
                                    @endif
                                    <p style="font-family:monospace;font-size:11px;color:#94a3b8;margin:0;">{{ $sample->sample_code }}</p>
                                </div>
                            </div>

                            @php $tests = $testsBySample[$sample->id] ?? collect(); @endphp
                            @if($tests->isEmpty())
                                <p style="font-size:13px;color:#94a3b8;font-style:italic;padding:12px 0;margin:0;">No tests recorded for this sample.</p>
                            @else
                                <table style="width:100%;border-collapse:collapse;">
                                    <thead>
                                        <tr style="background:#1a2f4e;">
                                            <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Test</th>
                                            <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Result</th>
                                            <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Methods</th>
                                            <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Outcome</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tests as $test)
                                            @php
                                                $sopCode = \App\Models\Kstl\SampleTest::TEST_SOPS[$test->test_key] ?? null;
                                                $sopDoc  = $sopCode ? ($sopDocuments[$sopCode] ?? null) : null;
                                                $unit    = $test->result_unit ? ' ' . $test->result_unit : '';
                                                $resultDisplay = match($test->result_qualifier) {
                                                    'detected'     => $test->result_value ? 'Detected · ' . $test->result_value . $unit : 'Detected',
                                                    'not_detected' => $test->result_value ? 'Not Detected · ' . $test->result_value . $unit : 'Not Detected',
                                                    'pass'         => $test->result_value ? 'Pass · ' . $test->result_value . $unit : 'Pass',
                                                    'fail'         => $test->result_value ? 'Fail · ' . $test->result_value . $unit : 'Fail',
                                                    'less_than'    => '< ' . $test->result_value . $unit,
                                                    'greater_than' => '> ' . $test->result_value . $unit,
                                                    'equal_to'     => $test->result_value . $unit,
                                                    default        => ($test->result_value ? $test->result_value . $unit : null),
                                                };
                                            @endphp
                                            <tr style="border-bottom:1px solid #f1f5f9;{{ $loop->even ? 'background:#f8fafc;' : '' }}">
                                                <td style="padding:10px 12px;font-size:13px;font-weight:600;color:#1e293b;">
                                                    {{ method_exists($test, 'getDisplayLabel') ? $test->getDisplayLabel() : $test->test_key }}
                                                </td>
                                                <td style="padding:10px 12px;font-size:13px;color:#374151;">
                                                    @if($resultDisplay)
                                                        {{ $resultDisplay }}
                                                    @else
                                                        <span style="color:#94a3b8;">—</span>
                                                    @endif
                                                </td>
                                                <td style="padding:10px 12px;">
                                                    @if($sopCode && $sopDoc)
                                                        @if($sopDoc->currentVersion)
                                                            <a href="{{ route('staff.documents.preview', $sopDoc->id) }}"
                                                               target="_blank"
                                                               style="display:inline-flex;align-items:center;gap:4px;font-family:monospace;font-size:11px;color:#0d9488;text-decoration:none;">
                                                                {{ $sopCode }}
                                                                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('staff.documents.show', $sopDoc->id) }}"
                                                               target="_blank"
                                                               style="display:inline-flex;align-items:center;gap:4px;font-family:monospace;font-size:11px;color:#0d9488;text-decoration:none;">
                                                                {{ $sopCode }}
                                                                <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                            </a>
                                                        @endif
                                                    @elseif($sopCode)
                                                        <span style="font-family:monospace;font-size:11px;color:#64748b;">{{ $sopCode }}</span>
                                                    @else
                                                        <span style="color:#94a3b8;">—</span>
                                                    @endif
                                                </td>
                                                <td style="padding:10px 12px;">
                                                    @if($test->director_outcome === 'pass')
                                                        <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;text-transform:uppercase;background:#f0fdf4;color:#166534;">Pass</span>
                                                    @elseif($test->director_outcome === 'fail')
                                                        <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;text-transform:uppercase;background:#fef2f2;color:#991b1b;">Fail</span>
                                                    @else
                                                        <span style="color:#94a3b8;">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @if($test->result_notes)
                                                <tr>
                                                    <td colspan="4" style="padding:8px 12px;font-size:12px;color:#64748b;background:#f8fafc;">
                                                        <span style="font-weight:600;color:#374151;">Notes:</span> {{ $test->result_notes }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Footer --}}
                <div style="padding:20px 32px;background:#f8fafc;border-top:1px solid #e2e8f0;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:24px;flex-wrap:wrap;">
                        <p style="font-size:12px;color:#64748b;line-height:1.6;max-width:480px;margin:0;">
                            Internal laboratory record. Results pertain solely to the sample(s) identified above.
                            This view reflects the report as authorised by the Laboratory Director.
                        </p>
                        <div style="text-align:right;flex-shrink:0;">
                            <p style="font-size:9px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:#94a3b8;margin:0 0 4px;">Document Ref.</p>
                            <p style="font-family:monospace;font-size:13px;color:#374151;margin:0 0 4px;">{{ $submission->reference_number }}</p>
                            <p style="font-size:11px;color:#94a3b8;margin:0;">STLD &middot; Official Portal</p>
                        </div>
                    </div>
                </div>

            </div>

            <div style="padding-bottom:32px;"></div>

        </div>
    </div>
</x-app-layout>
