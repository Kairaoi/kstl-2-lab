{{-- resources/views/kstl/director/results/show.blade.php --}}
{{-- Internal director-only result report. NOT shared with client. --}}

<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem 32px;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Director &middot; Internal Report</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">{{ $submission->reference_number }}</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Director use only &middot; {{ $submission->sample_name }}</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;" class="no-print">
                        <span style="display:inline-flex;align-items:center;padding:5px 14px;background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;border-radius:3px;font-size:11px;font-weight:700;letter-spacing:.06em;">
                            INTERNAL &mdash; NOT FOR CLIENT
                        </span>
                        <button onclick="window.print()"
                                style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;cursor:pointer;">
                            Print / Save PDF
                        </button>
                        <a href="{{ route('director.submissions.show', $submission->id) }}"
                           style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #fff;border-radius:3px;text-decoration:none;">
                            &larr; Back
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
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .page-hdr, .gov-stripe, .gov-top, nav, .app-footer { display: none !important; }
            a[href]:after { content: ''; }
        }
    </style>
    @endpush

    <div style="background:#f1f5f9;min-height:100vh;padding:0 0 56px;">
        <div style="max-width:64rem;margin:0 auto;padding:0 2rem;">

            {{-- Internal use banner --}}
            <div style="background:#fffbeb;border:1px solid #fde68a;border-left:4px solid #f59e0b;border-radius:4px;padding:12px 16px;margin-bottom:20px;display:flex;align-items:flex-start;gap:12px;" class="no-print">
                <p style="font-size:13px;color:#92400e;font-weight:600;margin:0;">
                    This is the internal Director's report. It contains outcome determination and analyst details not included in the client-facing Certificate of Analysis.
                </p>
            </div>

            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">

                {{-- ── Letterhead ─────────────────────────────────────────── --}}
                <div style="padding:24px 32px;border-bottom:3px double #1a2f4e;background:linear-gradient(180deg,#fbfaf8 0%,#ffffff 100%);">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:24px;">
                        <div style="display:flex;align-items:flex-start;gap:16px;">
                            <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="width:64px;height:64px;object-fit:contain;flex-shrink:0;">
                            <div>
                                <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Government of Kiribati &middot; Ministry of Fisheries &amp; Ocean Resources</p>
                                <h1 style="font-family:'Georgia',serif;font-size:20px;font-weight:700;color:#1a2f4e;margin:0;">Kiribati Seafood Toxicology Laboratory</h1>
                            </div>
                        </div>
                        <div style="text-align:right;flex-shrink:0;">
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Results</p>
                            <p style="font-family:monospace;font-size:13px;font-weight:700;color:#1a2f4e;margin:0 0 4px;">{{ $submission->reference_number }}</p>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">
                                Issued {{ $result?->authorised_at?->format('d M Y') ?? now()->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ── Authorisation strip ─────────────────────────────────── --}}
                <div style="padding:20px 32px;display:flex;align-items:center;justify-content:space-between;gap:24px;border-bottom:1px solid #e2e8f0;background:#f8fafc;">
                    <div>
                        <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Authorisation Status</p>
                        @if($result?->authorised_at)
                            <p style="font-size:13px;font-weight:700;color:#16a34a;margin:0;">Authorised</p>
                        @else
                            <p style="font-size:13px;color:#94a3b8;font-style:italic;margin:0;">Awaiting Director authorisation</p>
                        @endif
                    </div>

                    <div style="text-align:center;">
                        <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Prepared For</p>
                        <p style="font-size:13px;font-weight:700;color:#1e293b;margin:0 0 2px;">{{ $submission->client->company_name }}</p>
                        <p style="font-size:12px;color:#64748b;margin:0;">{{ $submission->client->user->email ?? '' }}</p>
                    </div>

                    @if($result?->authorised_at)
                        <div style="text-align:right;">
                            <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Authorised By</p>
                            <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0 0 2px;">{{ $result->authorisedBy?->name ?? 'Laboratory Director' }}</p>
                            <p style="font-size:12px;color:#64748b;margin:0 0 2px;">Laboratory Director</p>
                            <p style="font-size:11px;color:#94a3b8;margin:0;">{{ $result->authorised_at->format('d M Y \a\t H:i') }}</p>
                        </div>
                    @endif
                </div>

                {{-- ── Submission particulars ─────────────────────────────── --}}
                <div style="padding:24px 32px;border-bottom:1px solid #e2e8f0;">
                    <h2 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0 0 16px;padding-bottom:8px;border-bottom:2px solid #b8922a;">Submission Particulars</h2>

                    <dl style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px 24px;margin-bottom:20px;">
                        <div>
                            <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Client</dt>
                            <dd style="font-size:13px;font-weight:600;color:#1e293b;margin:0;">{{ $submission->client->company_name }}</dd>
                        </div>
                        <div>
                            <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Reference</dt>
                            <dd style="font-family:monospace;font-size:13px;color:#1e293b;margin:0;">{{ $submission->reference_number }}</dd>
                        </div>
                        <div>
                            <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Priority</dt>
                            <dd style="font-size:13px;color:#374151;text-transform:capitalize;margin:0;">{{ $submission->priority ?? 'Routine' }}</dd>
                        </div>
                        <div>
                            <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Collected</dt>
                            <dd style="font-size:13px;color:#374151;margin:0;">{{ $submission->collected_at?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        @if($submission->delivered_at)
                        <div>
                            <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Delivered</dt>
                            <dd style="font-size:13px;color:#374151;margin:0;">{{ $submission->delivered_at->format('d M Y') }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Submitted</dt>
                            <dd style="font-size:13px;color:#374151;margin:0;">{{ $submission->submitted_at?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Results Required By</dt>
                            <dd style="font-size:13px;color:#374151;margin:0;">{{ $submission->results_required_by?->format('d M Y') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Date of Issue</dt>
                            <dd style="font-size:13px;color:#374151;margin:0;">{{ $result?->authorised_at?->format('d M Y') ?? '—' }}</dd>
                        </div>
                    </dl>

                    {{-- Samples table --}}
                    <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 8px;">Samples Submitted ({{ $submission->samples->count() }})</p>
                    <table style="width:100%;border-collapse:collapse;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;">
                        <thead>
                            <tr style="background:#f8fafc;">
                                <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;">#</th>
                                <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;">Common Name</th>
                                <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;">Scientific Name</th>
                                <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;">Sample Code</th>
                                <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($submission->samples as $i => $sample)
                                <tr style="border-bottom:1px solid #f1f5f9;{{ $loop->even ? 'background:#f8fafc;' : '' }}">
                                    <td style="padding:8px 12px;font-size:12px;color:#94a3b8;font-family:monospace;">{{ $i + 1 }}</td>
                                    <td style="padding:8px 12px;font-size:13px;font-weight:600;color:#1e293b;">{{ $sample->common_name ?? '—' }}</td>
                                    <td style="padding:8px 12px;font-size:13px;font-style:italic;color:#64748b;">{{ $sample->scientific_name ?? '—' }}</td>
                                    <td style="padding:8px 12px;font-family:monospace;font-size:12px;color:#64748b;">{{ $sample->sample_code }}</td>
                                    <td style="padding:8px 12px;font-size:12px;color:#64748b;">{{ $sample->quantity ?? '—' }} {{ $sample->quantity_unit ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @php
                    // Partition tests: only director-queried (has [Director query] note) go to the
                    // returned section. Analyst self-flagged tests appear in authorised results.
                    $allTests        = $submission->samples->flatMap(fn($s) => $s->sampleTests->map(fn($t) => ['sample' => $s, 'test' => $t]));
                    $returnedTests   = $allTests->filter(fn($row) =>
                        $row['test']->status === 'flagged' &&
                        str_contains($row['test']->result_notes ?? '', '[Director query]')
                    );
                    $returnedTestIds = $returnedTests->pluck('test.id')->all();
                    $authorisedTests = $allTests->filter(fn($row) => ! in_array($row['test']->id, $returnedTestIds));

                    $sopDocuments = \App\Models\Kstl\Document::where('category', 'sop')
                        ->whereIn('reference_code', array_values(\App\Models\Kstl\SampleTest::TEST_SOPS))
                        ->with(['currentVersion'])
                        ->get()
                        ->keyBy('reference_code');
                @endphp

                {{-- ── Authorised Results ─────────────────────────────────────── --}}
                <div style="padding:24px 32px;border-bottom:1px solid #e2e8f0;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                        <h2 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;padding-bottom:8px;border-bottom:2px solid #b8922a;">Authorised Results</h2>
                        <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;background:#dcfce7;color:#166534;border:1px solid #86efac;border-radius:3px;font-size:11px;font-weight:700;">
                            Authorised
                        </span>
                    </div>

                    @foreach($submission->samples as $sample)
                        @php
                            $sampleAuthorisedTests = $authorisedTests->filter(fn($r) => $r['sample']->id === $sample->id);
                        @endphp
                        <div style="margin-bottom:24px;">
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                                <div>
                                    <h4 style="font-size:14px;font-weight:700;color:#1e293b;margin:0;">{{ $sample->common_name ?? $sample->sample_code }}</h4>
                                    @if($sample->scientific_name)
                                        <p style="font-size:12px;color:#94a3b8;font-style:italic;margin:2px 0 0;">{{ $sample->scientific_name }}</p>
                                    @endif
                                    <p style="font-family:monospace;font-size:11px;color:#94a3b8;margin:2px 0 0;">{{ $sample->sample_code }}</p>
                                </div>
                            </div>

                            @if($sampleAuthorisedTests->isEmpty())
                                <p style="font-size:13px;color:#94a3b8;font-style:italic;padding:12px 0;">No test results available.</p>
                            @else
                                <table style="width:100%;border-collapse:collapse;border:1px solid #e2e8f0;">
                                    <thead>
                                        <tr style="background:#1a2f4e;">
                                            <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Test</th>
                                            <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Result</th>
                                            <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Methods</th>
                                            <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Analyst</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sampleAuthorisedTests as $row)
                                            @php
                                                $test    = $row['test'];
                                                $sopCode = \App\Models\Kstl\SampleTest::TEST_SOPS[$test->test_key] ?? null;
                                                $sopDoc  = $sopCode ? ($sopDocuments[$sopCode] ?? null) : null;
                                                $unit = $test->result_unit ? ' ' . $test->result_unit : '';
                                                $resultText = match($test->result_qualifier) {
                                                    'detected'     => $test->result_value ? 'Detected · ' . $test->result_value . $unit : 'Detected',
                                                    'not_detected' => $test->result_value ? 'Not Detected · ' . $test->result_value . $unit : 'Not Detected',
                                                    'pass'         => $test->result_value ? 'Pass · ' . $test->result_value . $unit : 'Pass',
                                                    'fail'         => $test->result_value ? 'Fail · ' . $test->result_value . $unit : 'Fail',
                                                    'less_than'    => '< ' . $test->result_value . $unit,
                                                    'greater_than' => '> ' . $test->result_value . $unit,
                                                    'equal_to'     => $test->result_value . $unit,
                                                    default        => ($test->result_value ? $test->result_value . $unit : '—'),
                                                };
                                                $isDetected    = $test->result_qualifier === 'detected' || $test->result_qualifier === 'fail';
                                                $isNotDetected = $test->result_qualifier === 'not_detected' || $test->result_qualifier === 'pass';
                                            @endphp
                                            <tr style="border-bottom:1px solid #f1f5f9;{{ $loop->even ? 'background:#f8fafc;' : '' }}">
                                                <td style="padding:10px 12px;font-size:13px;font-weight:600;color:#1e293b;">{{ $test->getDisplayLabel() }}</td>
                                                <td style="padding:10px 12px;font-size:13px;font-weight:600;{{ $isDetected ? 'color:#dc2626;' : ($isNotDetected ? 'color:#16a34a;' : 'color:#374151;') }}">
                                                    {{ $resultText }}
                                                </td>
                                                <td style="padding:10px 12px;">
                                                    @if($sopCode && $sopDoc)
                                                        @if($sopDoc->currentVersion)
                                                            <a href="{{ route('staff.documents.preview', $sopDoc->id) }}"
                                                               target="_blank"
                                                               style="font-family:monospace;font-size:12px;color:#1a2f4e;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
                                                                {{ $sopCode }}
                                                            </a>
                                                        @else
                                                            <a href="{{ route('staff.documents.show', $sopDoc->id) }}"
                                                               target="_blank"
                                                               style="font-family:monospace;font-size:12px;color:#1a2f4e;text-decoration:none;">
                                                                {{ $sopCode }}
                                                            </a>
                                                        @endif
                                                    @elseif($sopCode)
                                                        <span style="font-family:monospace;font-size:12px;color:#64748b;">{{ $sopCode }}</span>
                                                    @else
                                                        <span style="color:#94a3b8;">—</span>
                                                    @endif
                                                </td>
                                                <td style="padding:10px 12px;font-size:12px;color:#64748b;">{{ $test->assignedTo?->name ?? '—' }}</td>
                                            </tr>
                                            @if($test->result_notes)
                                                <tr style="background:#f8fafc;">
                                                    <td colspan="4" style="padding:6px 12px 10px;font-size:12px;color:#64748b;font-style:italic;">Notes: {{ $test->result_notes }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- ── Analyst Section — Returned for Review ──────────────────── --}}
                @if($returnedTests->isNotEmpty())
                <div style="padding:24px 32px;border-bottom:1px solid #e2e8f0;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                        <h2 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;padding-bottom:8px;border-bottom:2px solid #b8922a;">Analyst Section</h2>
                        <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;background:#fef9c3;color:#854d0e;border:1px solid #fde047;border-radius:3px;font-size:11px;font-weight:700;">
                            Return &mdash; Pending Analyst Review
                        </span>
                    </div>

                    <div style="margin-bottom:16px;padding:12px 16px;background:#fffbeb;border:1px solid #fde68a;border-radius:3px;font-size:12px;color:#92400e;line-height:1.6;">
                        The following tests have been queried. The analyst has been notified and will re-confirm results before resubmitting for authorisation. Results shown are from the analyst's last submission.
                    </div>

                    @foreach($submission->samples as $sample)
                        @php
                            $sampleReturnedTests = $returnedTests->filter(fn($r) => $r['sample']->id === $sample->id);
                        @endphp
                        @if($sampleReturnedTests->isNotEmpty())
                            <div style="margin-bottom:24px;">
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                                    <div>
                                        <h4 style="font-size:14px;font-weight:700;color:#1e293b;margin:0;">{{ $sample->common_name ?? $sample->sample_code }}</h4>
                                        @if($sample->scientific_name)
                                            <p style="font-size:12px;color:#94a3b8;font-style:italic;margin:2px 0 0;">{{ $sample->scientific_name }}</p>
                                        @endif
                                        <p style="font-family:monospace;font-size:11px;color:#94a3b8;margin:2px 0 0;">{{ $sample->sample_code }}</p>
                                    </div>
                                    <x-kstl.status-badge :status="$sample->status" />
                                </div>
                                <table style="width:100%;border-collapse:collapse;border:1px solid #e2e8f0;">
                                    <thead>
                                        <tr style="background:#1a2f4e;">
                                            <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Test</th>
                                            <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Result</th>
                                            <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Methods</th>
                                            <th style="padding:8px 12px;text-align:left;font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#e2e8f0;">Analyst</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sampleReturnedTests as $row)
                                            @php
                                                $test   = $row['test'];
                                                $queryNote = null;
                                                if ($test->result_notes && str_contains($test->result_notes, '[Director query]')) {
                                                    preg_match('/\[Director query\]\s*(.+?)(?:\n\n|$)/s', $test->result_notes, $m);
                                                    $queryNote = trim($m[1] ?? '');
                                                }
                                                $unit = $test->result_unit ? ' ' . $test->result_unit : '';
                                                $resultText = match($test->result_qualifier) {
                                                    'detected'     => $test->result_value ? 'Detected · ' . $test->result_value . $unit : 'Detected',
                                                    'not_detected' => $test->result_value ? 'Not Detected · ' . $test->result_value . $unit : 'Not Detected',
                                                    'pass'         => $test->result_value ? 'Pass · ' . $test->result_value . $unit : 'Pass',
                                                    'fail'         => $test->result_value ? 'Fail · ' . $test->result_value . $unit : 'Fail',
                                                    'less_than'    => '< ' . $test->result_value . $unit,
                                                    'greater_than' => '> ' . $test->result_value . $unit,
                                                    'equal_to'     => $test->result_value . $unit,
                                                    default        => ($test->result_value ? $test->result_value . $unit : '—'),
                                                };
                                                $isDetected    = $test->result_qualifier === 'detected' || $test->result_qualifier === 'fail';
                                                $isNotDetected = $test->result_qualifier === 'not_detected' || $test->result_qualifier === 'pass';
                                                $sopCode = \App\Models\Kstl\SampleTest::TEST_SOPS[$test->test_key] ?? null;
                                                $sopDoc  = $sopCode ? ($sopDocuments[$sopCode] ?? null) : null;
                                            @endphp
                                            <tr style="background:#fffbeb;border-bottom:1px solid #fde68a;">
                                                <td style="padding:10px 12px;font-size:13px;font-weight:600;color:#1e293b;">{{ $test->getDisplayLabel() }}</td>
                                                <td style="padding:10px 12px;font-size:13px;font-weight:600;{{ $isDetected ? 'color:#dc2626;' : ($isNotDetected ? 'color:#16a34a;' : 'color:#374151;') }}">
                                                    {{ $resultText }}
                                                </td>
                                                <td style="padding:10px 12px;">
                                                    @if($sopCode && $sopDoc)
                                                        @if($sopDoc->currentVersion)
                                                            <a href="{{ route('staff.documents.preview', $sopDoc->id) }}"
                                                               target="_blank"
                                                               style="font-family:monospace;font-size:12px;color:#1a2f4e;text-decoration:none;">
                                                                {{ $sopCode }}
                                                            </a>
                                                        @else
                                                            <a href="{{ route('staff.documents.show', $sopDoc->id) }}"
                                                               target="_blank"
                                                               style="font-family:monospace;font-size:12px;color:#1a2f4e;text-decoration:none;">
                                                                {{ $sopCode }}
                                                            </a>
                                                        @endif
                                                    @elseif($sopCode)
                                                        <span style="font-family:monospace;font-size:12px;color:#64748b;">{{ $sopCode }}</span>
                                                    @else
                                                        <span style="color:#94a3b8;">—</span>
                                                    @endif
                                                </td>
                                                <td style="padding:10px 12px;font-size:12px;color:#64748b;">{{ $test->assignedTo?->name ?? '—' }}</td>
                                            </tr>
                                            @if($queryNote)
                                                <tr style="background:#fefce8;">
                                                    <td colspan="4" style="padding:6px 12px 10px;font-size:12px;color:#92400e;font-style:italic;">
                                                        <span style="font-weight:700;font-style:normal;">Director query:</span> {{ $queryNote }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endforeach
                </div>
                @endif

                {{-- ── Assessment Record ────────────────────────────────────── --}}
                @php $assessedSamples = $submission->samples->filter(fn($s) => $s->assessment !== null); @endphp
                @if($assessedSamples->isNotEmpty())
                <div style="padding:24px 32px;border-bottom:1px solid #e2e8f0;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                        <h2 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;padding-bottom:8px;border-bottom:2px solid #b8922a;">Sample Assessment Record</h2>
                        @php
                            $allAccepted = $assessedSamples->every(fn($s) => $s->assessment->outcome === 'accepted');
                            $anyRejected = $assessedSamples->some(fn($s)  => $s->assessment->outcome === 'rejected');
                        @endphp
                        @if($allAccepted)
                            <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;background:#dcfce7;color:#166534;border:1px solid #86efac;border-radius:3px;font-size:11px;font-weight:700;">All Accepted</span>
                        @elseif($anyRejected)
                            <span style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;border-radius:3px;font-size:11px;font-weight:700;">Rejected</span>
                        @endif
                    </div>

                    @foreach($assessedSamples as $sample)
                        @php $a = $sample->assessment; @endphp
                        <div style="{{ !$loop->last ? 'margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid #e2e8f0;' : '' }}">
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                                <div>
                                    <p style="font-size:14px;font-weight:700;color:#1e293b;margin:0;">{{ $sample->common_name }}</p>
                                    <p style="font-family:monospace;font-size:11px;color:#94a3b8;margin:2px 0 0;">{{ $sample->sample_code }}</p>
                                </div>
                                <div style="display:flex;align-items:center;gap:12px;">
                                    <span style="display:inline-flex;padding:3px 10px;font-size:12px;font-weight:700;border-radius:9999px;{{ $a->outcome === 'accepted' ? 'background:#dcfce7;color:#166534;' : 'background:#fee2e2;color:#991b1b;' }}">
                                        {{ ucfirst($a->outcome) }}
                                    </span>
                                    @if($a->assessedBy)
                                        <div style="text-align:right;">
                                            <p style="font-size:12px;color:#64748b;margin:0;">{{ $a->assessedBy->name }}</p>
                                            <p style="font-size:11px;color:#94a3b8;margin:2px 0 0;">{{ ($a->assessed_at ?? $a->created_at)->format('d M Y H:i') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @php
                                $criteria = [
                                    'Temperature'         => [$a->temperature_ok, $a->temperature_notes],
                                    'Storage Condition'   => [$a->storage_ok,     $a->storage_notes],
                                    'Transport Condition' => [$a->transport_ok,   $a->transport_notes],
                                    'Packaging Integrity' => [$a->packaging_ok,   $a->packaging_notes],
                                    'Colour / Appearance' => [$a->colour_ok,      $a->colour_notes],
                                    'Weight / Quantity'   => [$a->weight_ok,      $a->weight_notes],
                                ];
                            @endphp
                            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;">
                                @foreach($criteria as $label => [$pass, $notes])
                                    <div style="border-radius:3px;border:1px solid;padding:10px 12px;{{ $pass ? 'border-color:#bbf7d0;background:#f0fdf4;' : 'border-color:#fecaca;background:#fff5f5;' }}">
                                        <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;">
                                            <span style="font-size:12px;font-weight:600;color:#374151;">{{ $label }}</span>
                                            <span style="font-size:12px;font-weight:700;{{ $pass ? 'color:#16a34a;' : 'color:#dc2626;' }}">
                                                {{ $pass ? 'Pass' : 'Fail' }}
                                            </span>
                                        </div>
                                        @if($notes)
                                            <p style="font-size:11px;color:#64748b;margin:4px 0 0;line-height:1.4;">{{ $notes }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @if($a->additional_observations)
                                <div style="margin-top:8px;background:#f8fafc;border-radius:3px;padding:10px 12px;font-size:12px;color:#475569;">
                                    <span style="font-weight:700;">Observations:</span> {{ $a->additional_observations }}
                                </div>
                            @endif
                            @if($a->rejection_reason)
                                <div style="margin-top:8px;background:#fff5f5;border:1px solid #fecaca;border-radius:3px;padding:10px 12px;font-size:12px;color:#991b1b;">
                                    <span style="font-weight:700;">Rejection reason:</span> {{ $a->rejection_reason }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                @endif

                {{-- ── Director's Comments ─────────────────────────────────── --}}
                @if($result?->director_comments)
                    <div style="padding:24px 32px;border-bottom:1px solid #e2e8f0;">
                        <h2 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0 0 12px;padding-bottom:8px;border-bottom:2px solid #b8922a;">Director's Comments</h2>
                        <p style="font-size:13px;color:#374151;line-height:1.7;white-space:pre-line;margin:0;">{{ $result->director_comments }}</p>
                    </div>
                @endif

                {{-- ── Footer ──────────────────────────────────────────────── --}}
                <div style="padding:20px 32px;background:#f8fafc;border-top:1px solid #e2e8f0;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:24px;">
                        <div style="max-width:28rem;">
                            <p style="font-size:11px;font-weight:700;color:#dc2626;margin:0 0 4px;text-transform:uppercase;letter-spacing:.06em;">INTERNAL DOCUMENT &mdash; CONFIDENTIAL</p>
                            <p style="font-size:12px;color:#64748b;line-height:1.6;margin:0;">
                                This document is for Director use only and must not be shared with the client or external parties.
                                The client-facing Certificate of Analysis does not include determination outcomes or analyst details.
                            </p>
                        </div>
                        <div style="text-align:right;flex-shrink:0;">
                            <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Document Ref.</p>
                            <p style="font-family:monospace;font-size:13px;color:#1a2f4e;font-weight:600;margin:0 0 4px;">{{ $submission->reference_number }}</p>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">KSTL &middot; Director</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
