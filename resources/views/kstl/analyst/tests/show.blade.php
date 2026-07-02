{{-- resources/views/kstl/analyst/tests/show.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div style="display:flex; align-items:center; gap:12px;">
            <a href="{{ route('analyst.tests.index') }}"
               style="color:#94a3b8; text-decoration:none; flex-shrink:0;">
                <svg style="width:20px; height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <p style="font-size:10px; font-weight:700; letter-spacing:.16em; text-transform:uppercase; color:#b8922a; margin:0 0 3px;">Analyst &middot; Enter Result</p>
                <h2 style="font-family:'Georgia',serif; font-size:20px; font-weight:700; color:#1a2f4e; margin:0; letter-spacing:.01em;">
                    {{ $test->getDisplayLabel() }}
                </h2>
                <p style="font-size:11px; color:#94a3b8; margin:3px 0 0;">
                    {{ $submission->reference_number }} &middot; {{ $sample->sample_code }}
                </p>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
    .page-hdr { padding: 0 !important; position: static !important; }
    .page-hdr-inner { max-width: 100% !important; padding: 0 !important; }
    .app-main { padding-left:0 !important; padding-right:0 !important; padding-top:0 !important; max-width:100% !important; }
    .at-meta-label { letter-spacing:.1em; text-transform:uppercase; font-size:9px; color:#94a3b8; font-weight:700; }
    .at-section-heading {
        font-family:'Georgia',serif; font-size:15px; font-weight:700; color:#1a2f4e;
        border-bottom:2px solid #b8922a; padding-bottom:8px; margin:0 0 4px; display:inline-block;
    }
    </style>
    @endpush

    <div style="background:#f1f5f9; min-height:100vh; padding:0 0 56px;">
        <div style="max-width:80rem; margin:0 auto; padding:0 2rem;">

            {{-- ── Director query / flagged banner ── --}}
            @php
                // Extract the most recent [Director query] note from result_notes.
                // Defined here (outside the flagged conditional) so it is always
                // available to the save panel further down the page.
                $directorQueryNote = null;
                if ($test->result_notes) {
                    preg_match_all('/\[Director query\]\s*(.*?)(?=\n\n\[Director query\]|$)/s', $test->result_notes, $allMatches);
                    if (!empty($allMatches[1])) {
                        $directorQueryNote = trim(end($allMatches[1]));
                    }
                }
                $hasDirectorQuery = !empty($directorQueryNote);
            @endphp
            @if($test->status === 'flagged')

                @if($hasDirectorQuery)
                    {{-- Director sent an explicit query back to the analyst --}}
                    <div style="background:#fef2f2; border:1px solid #fca5a5; border-radius:4px; overflow:hidden; margin-bottom:24px;">
                        <div style="background:#dc2626; padding:12px 20px; display:flex; align-items:center; gap:10px;">
                            <svg style="width:18px; height:18px; flex-shrink:0;" fill="none" stroke="#fff" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                            </svg>
                            <p style="font-size:13px; font-weight:700; color:#fff; margin:0; letter-spacing:.02em;">
                                Director Query — Action Required
                            </p>
                        </div>
                        <div style="padding:16px 20px;">
                            <p style="font-size:12px; color:#991b1b; margin:0 0 14px;">
                                The Laboratory Director has returned this test for clarification.
                                Read the query carefully, amend your result if needed, then click <strong>Save Result</strong> to resubmit for authorisation.
                            </p>
                            <div style="background:#fff; border:2px solid #dc2626; border-radius:4px; padding:14px 18px;">
                                <p style="font-size:9px; font-weight:700; letter-spacing:.14em; text-transform:uppercase; color:#dc2626; margin:0 0 8px;">
                                    Director's Query
                                </p>
                                <p style="font-size:14px; color:#1a2f4e; line-height:1.7; margin:0; white-space:pre-line;">{{ $directorQueryNote }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Analyst-flagged (awaiting Director review) --}}
                    <div style="background:#fffbeb; border:1px solid #fcd34d; border-left:4px solid #d97706; border-radius:4px; padding:14px 20px; margin-bottom:24px; display:flex; align-items:flex-start; gap:12px;">
                        <svg style="width:18px; height:18px; flex-shrink:0; margin-top:1px;" fill="none" stroke="#d97706" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2z"/>
                        </svg>
                        <div>
                            <p style="font-size:12.5px; font-weight:700; color:#92400e; margin:0 0 4px;">
                                Flagged for Director Review
                            </p>
                            <p style="font-size:12px; color:#b45309; margin:0;">
                                You marked this test for Director attention. The Director has not yet sent a query back.
                                Once the Director responds, their query will appear here and you will be notified.
                            </p>
                        </div>
                    </div>
                @endif
            @endif

            <div style="display:grid; grid-template-columns:1fr 2fr; gap:20px; align-items:start;">

                {{-- ── Left: Context ──────────────────────────────── --}}
                <div style="display:flex; flex-direction:column; gap:16px;">

                    {{-- Test Info --}}
                    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:4px; overflow:hidden;">
                        <div style="padding:14px 18px; border-bottom:1px solid #e2e8f0;">
                            <h3 class="at-section-heading">Test Details</h3>
                        </div>
                        <dl style="padding:16px 18px; display:flex; flex-direction:column; gap:12px;">
                            <div>
                                <dt class="at-meta-label">Test</dt>
                                <dd style="font-size:13px; font-weight:600; color:#1a2f4e; margin:3px 0 0;">{{ $test->getDisplayLabel() }}</dd>
                            </div>
                            <div>
                                <dt class="at-meta-label">Category</dt>
                                <dd style="margin:3px 0 0;">
                                    @php $cat = $test->getDisplayCategory(); @endphp
                                    <span style="display:inline-flex; padding:2px 8px; font-size:10px; font-weight:600; border-radius:20px; text-transform:capitalize; background:{{ $cat === 'microbiology' ? '#f5f3ff' : '#eff6ff' }}; color:{{ $cat === 'microbiology' ? '#7c3aed' : '#1d4ed8' }};">
                                        {{ $cat }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="at-meta-label">Status</dt>
                                <dd style="margin:3px 0 0;">
                                    <x-kstl.status-badge :status="$test->status" />
                                </dd>
                            </div>
                            @if($test->started_at)
                            <div>
                                <dt class="at-meta-label">Started</dt>
                                <dd style="font-size:12.5px; color:#475569; margin:3px 0 0;">{{ $test->started_at->format('d M Y H:i') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                    {{-- Sample Info --}}
                    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:4px; overflow:hidden;">
                        <div style="padding:14px 18px; border-bottom:1px solid #e2e8f0;">
                            <h3 class="at-section-heading">Sample</h3>
                        </div>
                        <dl style="padding:16px 18px; display:flex; flex-direction:column; gap:12px;">
                            <div>
                                <dt class="at-meta-label">Code</dt>
                                <dd style="font-family:monospace; font-size:12px; font-weight:700; color:#1a2f4e; margin:3px 0 0;">{{ $sample->sample_code }}</dd>
                            </div>
                            <div>
                                <dt class="at-meta-label">Common Name</dt>
                                <dd style="font-size:13px; font-weight:600; color:#1a2f4e; margin:3px 0 0;">{{ $sample->common_name }}</dd>
                            </div>
                            @if($sample->scientific_name)
                            <div>
                                <dt class="at-meta-label">Scientific Name</dt>
                                <dd style="font-size:12.5px; font-style:italic; color:#64748b; margin:3px 0 0;">{{ $sample->scientific_name }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="at-meta-label">Quantity</dt>
                                <dd style="font-size:12.5px; color:#475569; margin:3px 0 0;">{{ $sample->quantity }} {{ $sample->quantity_unit }}</dd>
                            </div>
                            <div>
                                <dt class="at-meta-label">Sampling Date</dt>
                                <dd style="font-size:12.5px; color:#475569; margin:3px 0 0;">{{ $sample->sampling_date->format('d M Y') }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Submission Info --}}
                    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:4px; overflow:hidden;">
                        <div style="padding:14px 18px; border-bottom:1px solid #e2e8f0;">
                            <h3 class="at-section-heading">Submission</h3>
                        </div>
                        <dl style="padding:16px 18px; display:flex; flex-direction:column; gap:12px;">
                            <div>
                                <dt class="at-meta-label">Reference</dt>
                                <dd style="font-family:monospace; font-size:11px; font-weight:700; color:#1a2f4e; margin:3px 0 0;">{{ $submission->reference_number }}</dd>
                            </div>
                            <div>
                                <dt class="at-meta-label">Client</dt>
                                <dd style="font-size:12.5px; color:#475569; margin:3px 0 0;">{{ $submission->client->company_name }}</dd>
                            </div>
                            <div>
                                <dt class="at-meta-label">Priority</dt>
                                <dd style="margin:3px 0 0;">
                                    <x-kstl.priority-badge :priority="$submission->priority" />
                                </dd>
                            </div>
                            @if($submission->results_required_by)
                            <div>
                                <dt class="at-meta-label">Required By</dt>
                                <dd style="font-size:12.5px; color:#475569; margin:3px 0 0;">{{ $submission->results_required_by->format('d M Y') }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>

                </div>

                {{-- ── Right: Result Form ──────────────────────────── --}}
                <div style="display:flex; flex-direction:column; gap:16px;">
                    @php
                        // Always compute before the locked/unlocked branch so @push('scripts') can use it.
                        $analystOwnNotes = $test->result_notes ?? '';
                        if ($test->status === 'flagged' && $analystOwnNotes !== '') {
                            $analystOwnNotes = preg_replace('/\n*\[Director query\].*/s', '', $analystOwnNotes);
                            $analystOwnNotes = trim($analystOwnNotes);
                        }
                    @endphp
                    @if(($locked ?? false) || $test->status === 'completed')
                        {{-- ── LOCKED: read-only finalised result (audit view) ── --}}
                        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:4px; overflow:hidden;">
                            <div style="padding:16px 20px; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between;">
                                <div>
                                    <h3 class="at-section-heading">Finalised Result</h3>
                                    <p style="font-size:11px; color:#94a3b8; margin:4px 0 0;">This result is locked for audit and can only be viewed.</p>
                                </div>
                                <span style="display:inline-flex; align-items:center; gap:6px; padding:5px 12px; background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0; border-radius:3px; font-size:11px; font-weight:600;">
                                    <svg style="width:13px; height:13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    Locked
                                </span>
                            </div>
                            <dl style="padding:20px; display:grid; grid-template-columns:1fr 1fr; gap:16px 24px;">
                                @php
                                    $qlabels = ['pass'=>'Pass','fail'=>'Fail','detected'=>'Detected','not_detected'=>'Not Detected','less_than'=>'Less Than','greater_than'=>'Greater Than','equal_to'=>'Equal To','pending'=>'Pending'];
                                @endphp
                                <div>
                                    <dt class="at-meta-label">Result Qualifier</dt>
                                    <dd style="margin-top:4px; font-size:13px; font-weight:700; color:#1a2f4e;">{{ $qlabels[$test->result_qualifier] ?? ucfirst(str_replace('_',' ',$test->result_qualifier)) }}</dd>
                                </div>
                                <div>
                                    <dt class="at-meta-label">Result Value</dt>
                                    <dd style="margin-top:4px; font-size:13px; color:#1a2f4e; font-family:monospace;">{{ $test->result_value ?: '—' }} <span style="color:#94a3b8; font-family:inherit; font-size:12px;">{{ $test->result_unit }}</span></dd>
                                </div>
                                <div style="grid-column:1 / -1;">
                                    <dt class="at-meta-label">Result Notes</dt>
                                    <dd style="margin-top:4px; font-size:13px; color:#475569; white-space:pre-line; line-height:1.6;">{{ $test->result_notes ?: '—' }}</dd>
                                </div>
                                <div>
                                    <dt class="at-meta-label">Finalised By</dt>
                                    <dd style="margin-top:4px; font-size:13px; color:#1a2f4e;">{{ $test->assignedTo?->name ?? trim(($test->assignedTo->first_name ?? '') . ' ' . ($test->assignedTo->last_name ?? '')) ?: '—' }}</dd>
                                </div>
                                <div>
                                    <dt class="at-meta-label">Completed At</dt>
                                    <dd style="margin-top:4px; font-size:13px; color:#1a2f4e;">{{ $test->completed_at?->format('d M Y \a\t H:i') ?? '—' }}</dd>
                                </div>
                            </dl>
                        </div>
                    @else
                    <div style="background:#fff; border:1px solid #e2e8f0; border-radius:4px; overflow:hidden;">
                        <div style="padding:16px 20px; border-bottom:1px solid #e2e8f0;">
                            <h3 class="at-section-heading">Enter Test Result</h3>
                            <p style="font-size:11px; color:#94a3b8; margin:4px 0 0;">Record the result for this test. All fields except qualifier are optional.</p>
                        </div>

                        <form method="POST"
                              id="result-form"
                              action="{{ route('analyst.tests.result', $test->id) }}"
                              x-data="resultForm()"
                              x-init="init()"
                              @submit="clearDraft()">
                            @csrf

                            <div style="padding:20px; display:flex; flex-direction:column; gap:20px;">

                                {{-- Result Qualifier --}}
                                <div>
                                    <label style="display:block; font-size:13px; font-weight:600; color:#1a2f4e; margin-bottom:10px;">
                                        Result Qualifier <span style="color:#dc2626;">*</span>
                                    </label>
                                    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:8px;">
                                        @php
                                            $qualifiers = [
                                                'detected'     => ['label' => 'Detected',     'color' => 'orange'],
                                                'not_detected' => ['label' => 'Not Detected', 'color' => 'green'],
                                                'less_than'    => ['label' => '&lt; (Less Than)', 'color' => 'blue'],
                                                'greater_than' => ['label' => '&gt; (Greater Than)', 'color' => 'blue'],
                                                'equal_to'     => ['label' => '= (Equal To)', 'color' => 'blue'],
                                            ];
                                        @endphp
                                        @foreach($qualifiers as $value => $cfg)
                                            <label style="display:flex; align-items:center; gap:8px; padding:10px 12px; border-radius:4px; border:2px solid; cursor:pointer; font-size:12.5px; transition:all .15s;"
                                                   :style="qualifier === '{{ $value }}'
                                                       ? 'border-color:#1a2f4e; background:#f0f4ff; color:#1a2f4e; font-weight:700;'
                                                       : 'border-color:#e2e8f0; color:#64748b; background:#fff;'">
                                                <input type="radio"
                                                       name="result_qualifier"
                                                       value="{{ $value }}"
                                                       x-model="qualifier"
                                                       style="display:none;">
                                                {!! $cfg['label'] !!}
                                            </label>
                                        @endforeach
                                    </div>
                                    <x-input-error for="result_qualifier" class="mt-1"/>
                                </div>

                                {{-- Result Value + Unit --}}
                                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                                    <div>
                                        <label style="display:block; font-size:13px; font-weight:600; color:#1a2f4e; margin-bottom:6px;">
                                            Result Value
                                        </label>
                                        <x-input type="text"
                                                 name="result_value"
                                                 value="{{ old('result_value', $test->result_value) }}"
                                                 class="w-full"
                                                 placeholder="e.g. 12.4, <10, Detected"/>
                                        <p style="font-size:11px; color:#94a3b8; margin:4px 0 0;">Numeric or qualitative value</p>
                                        <x-input-error for="result_value" class="mt-1"/>
                                    </div>
                                    <div>
                                        @php
                                            $defaultUnit = \App\Models\Kstl\SampleTest::TEST_UNITS[$test->test_key] ?? '';
                                            $savedUnit   = old('result_unit', $test->result_unit ?? $defaultUnit);
                                            $unitOptions = ['CFU/g','CFU/mL','MPN/100mL','mg/kg','%','µS/cm','pH units','aw'];
                                        @endphp
                                        <label style="display:block; font-size:13px; font-weight:600; color:#1a2f4e; margin-bottom:6px;">
                                            Unit
                                        </label>
                                        <select name="result_unit"
                                                style="width:100%; border:1px solid #e2e8f0; border-radius:3px; padding:8px 12px; font-size:13px; color:#1a2f4e; background:#fff; outline:none; box-sizing:border-box; height:38px;">
                                            <option value="">— none —</option>
                                            @foreach($unitOptions as $u)
                                                <option value="{{ $u }}" @selected($savedUnit === $u)>{{ $u }}</option>
                                            @endforeach
                                        </select>
                                        <p style="font-size:11px; color:#94a3b8; margin:4px 0 0;">Select the measurement unit</p>
                                        <x-input-error for="result_unit" class="mt-1"/>
                                    </div>
                                </div>

                                {{-- Notes --}}
                                <div>
                                    <label style="display:block; font-size:13px; font-weight:600; color:#1a2f4e; margin-bottom:6px;">
                                        Result Notes
                                    </label>
                                    <textarea name="result_notes"
                                              rows="3"
                                              style="width:100%; border:1px solid #e2e8f0; border-radius:3px; padding:8px 12px; font-size:13px; color:#1a2f4e; outline:none; resize:vertical; box-sizing:border-box;"
                                              placeholder="Additional observations, instrument readings, methodology notes...">{{ old('result_notes', $analystOwnNotes) }}</textarea>
                                    @if($test->status === 'flagged')
                                        <p style="font-size:11px; color:#64748b; margin:4px 0 0;">
                                            Enter your response or amended observations. The Director's query above is preserved automatically.
                                        </p>
                                    @endif
                                    <x-input-error for="result_notes" class="mt-1"/>
                                </div>

                                {{-- Flag for Director and Save are rendered below, after Supporting Files --}}

                            </div>
                        </form>
                    </div>
                    @endif

                    {{-- ── Supporting Files (shown for both active and locked) ── --}}
                    @include('kstl.analyst.tests._attachments', ['test' => $test, 'locked' => ($locked ?? false)])

                    {{-- ── Actions panel (placed after Supporting Files) ── --}}
                    @unless(($locked ?? false) || $test->status === 'completed')
                        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:4px; overflow:hidden;">
                            <div style="padding:20px;">
                                @if($directorQueryNote)
                                    {{-- Responding to director query — show submit response info, hide re-flag --}}
                                    <div style="background:#f0fdf4; border:1px solid #86efac; border-left:4px solid #16a34a; border-radius:4px; padding:14px 16px;">
                                        <p style="font-size:13px; font-weight:600; color:#15803d; margin:0 0 4px;">Submit Response to Director</p>
                                        <p style="font-size:11px; color:#166534; margin:0;">
                                            Saving will mark this test complete and send your response back for Director review.
                                            If you still need to re-flag, tick the option below.
                                        </p>
                                    </div>
                                    <label style="display:flex; align-items:flex-start; gap:10px; cursor:pointer; margin-top:12px;">
                                        <input type="checkbox"
                                               name="flag"
                                               value="1"
                                               form="result-form"
                                               x-model="flagged"
                                               style="margin-top:2px; accent-color:#d97706;"/>
                                        <div>
                                            <p style="font-size:12px; font-weight:600; color:#92400e; margin:0;">Re-flag for Director (keep open)</p>
                                            <p style="font-size:11px; color:#b45309; margin:2px 0 0;">
                                                Tick only if the issue requires further back-and-forth. Leave unticked to close this query.
                                            </p>
                                        </div>
                                    </label>
                                @else
                                    {{-- Normal save — show standard flag checkbox --}}
                                    <div style="background:#fffbeb; border:1px solid #fcd34d; border-radius:4px; padding:14px 16px;">
                                        <label style="display:flex; align-items:flex-start; gap:10px; cursor:pointer;">
                                            <input type="checkbox"
                                                   name="flag"
                                                   value="1"
                                                   form="result-form"
                                                   x-model="flagged"
                                                   style="margin-top:2px; accent-color:#d97706;"/>
                                            <div>
                                                <p style="font-size:13px; font-weight:600; color:#92400e; margin:0;">Flag for Director Review</p>
                                                <p style="font-size:11px; color:#b45309; margin:3px 0 0;">
                                                    Check this if the result is anomalous, exceeds limits, or requires Director attention before authorisation.
                                                </p>
                                            </div>
                                        </label>
                                    </div>
                                @endif
                            </div>
                            {{-- Actions --}}
                            <div style="padding:14px 20px; border-top:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; background:#f8fafc;">
                                <a href="{{ route('analyst.tests.index') }}"
                                   style="padding:8px 18px; font-size:13px; font-weight:600; color:#475569; background:#fff; border:1px solid #e2e8f0; border-radius:3px; text-decoration:none;">
                                    Cancel
                                </a>
                                <button type="submit"
                                        form="result-form"
                                        style="display:inline-flex; align-items:center; gap:8px; padding:9px 24px; font-size:13px; font-weight:600; border:none; border-radius:3px; cursor:pointer; background:#1a2f4e; color:#fff;"
                                        :style="flagged ? 'background:#d97706; color:#fff;' : 'background:#1a2f4e; color:#fff;'">
                                    <svg style="width:16px; height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span x-text="flagged ? 'Save &amp; Keep Flagged' : '{{ $directorQueryNote ? 'Submit Response' : 'Save Result' }}'">{{ $directorQueryNote ? 'Submit Response' : 'Save Result' }}</span>
                                </button>
                            </div>
                        </div>
                    @endunless
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function resultForm() {
        const DRAFT_KEY = 'result_draft_{{ $test->id }}';
        const DB = {
            qualifier : '{{ old("result_qualifier", $test->result_qualifier ?? "") }}',
            value     : @json(old('result_value', $test->result_value ?? '')),
            unit      : @json(old('result_unit',  $test->result_unit  ?? '')),
            notes     : @json(old('result_notes', $analystOwnNotes)),
            // Pre-check only when analyst originally flagged (no director query present).
            // When responding to a director query, default to unchecked so saving submits response.
            flagged   : {{ (old('flag') !== null ? (bool)old('flag') : ($test->status === 'flagged' && !$directorQueryNote)) ? 'true' : 'false' }},
        };

        return {
            qualifier : DB.qualifier || '',
            flagged   : DB.flagged,

            init() {
                // If Laravel redirected back with validation errors, old() values
                // are already baked into DB — discard any stale localStorage draft.
                const hasOld = {{ session()->has('_old_input') ? 'true' : 'false' }};
                if (hasOld) {
                    localStorage.removeItem(DRAFT_KEY);
                }

                const saved = hasOld ? null : this._load();
                if (saved) {
                    this.qualifier = saved.qualifier ?? DB.qualifier ?? '';
                    this.flagged   = saved.flagged   ?? DB.flagged;

                    if (saved.value !== undefined) {
                        const vEl = document.querySelector('[name="result_value"]');
                        if (vEl && saved.value !== DB.value) vEl.value = saved.value;
                    }
                    if (saved.unit !== undefined) {
                        const uEl = document.querySelector('[name="result_unit"]');
                        if (uEl && saved.unit !== DB.unit) uEl.value = saved.unit;
                    }
                    if (saved.notes !== undefined) {
                        const nEl = document.querySelector('[name="result_notes"]');
                        if (nEl && saved.notes !== DB.notes) nEl.value = saved.notes;
                    }
                }

                // Auto-save on any input change
                document.getElementById('result-form')
                    ?.addEventListener('input',  () => this._save());
                document.getElementById('result-form')
                    ?.addEventListener('change', () => this._save());
            },

            _save() {
                const vEl = document.querySelector('[name="result_value"]');
                const uEl = document.querySelector('[name="result_unit"]');
                const nEl = document.querySelector('[name="result_notes"]');
                localStorage.setItem(DRAFT_KEY, JSON.stringify({
                    qualifier : this.qualifier,
                    flagged   : this.flagged,
                    value     : vEl?.value ?? '',
                    unit      : uEl?.value ?? '',
                    notes     : nEl?.value ?? '',
                }));
            },

            _load() {
                try { return JSON.parse(localStorage.getItem(DRAFT_KEY)); }
                catch { return null; }
            },

            clearDraft() {
                localStorage.removeItem(DRAFT_KEY);
            },
        };
    }
    </script>
    @endpush
</x-app-layout>
