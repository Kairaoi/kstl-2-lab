<x-app-layout>
    <x-slot name="header">
        <div style="position:relative;overflow:hidden;background:linear-gradient(135deg,#0f2240 0%,#1a2f4e 60%,#1e3a5f 100%);margin:-1px;">
            <div style="position:absolute;inset:0;opacity:.04;background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:12px 12px;pointer-events:none;"></div>
            <div style="position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,#1a2f4e,#b8922a 30%,#b8922a 70%,#1a2f4e);"></div>
            <div style="max-width:80rem;margin:0 auto;padding:28px 2rem;">
                <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;position:relative;">
                    <div style="display:flex;align-items:center;gap:20px;">
                        <img src="{{ asset('images/mfor-logo.png') }}" alt="MFOR" style="filter:brightness(0) invert(1);opacity:.92;width:56px;height:56px;flex-shrink:0;">
                        <div>
                            <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 4px;">Client</p>
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Seafood Laboratory Service Agreement</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Kiribati Seafood Testing Laboratory</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <a href="{{ route('client.dashboard') }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid rgba(255,255,255,.5);border-radius:3px;text-decoration:none;">
                            &larr; Dashboard
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
        [x-cloak] { display: none !important; }
    </style>
    @endpush

    <div style="background:#f1f5f9;min-height:100vh;padding:0 0 56px;">
        <div style="max-width:56rem;margin:0 auto;padding:0 2rem;">

            {{-- Intro Banner --}}
            <div style="background:#eff6ff;border:1px solid #bfdbfe;border-left:4px solid #3b82f6;border-radius:4px;padding:14px 18px;margin-bottom:24px;">
                <p style="font-size:13px;font-weight:700;color:#1e40af;margin:0 0 4px;">Action required before submitting samples</p>
                <p style="font-size:13px;color:#1d4ed8;margin:0;">
                    Please read the entire service agreement carefully. You must sign it before
                    you can submit samples to the laboratory.
                </p>
            </div>

            {{-- Agreement Document --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">

                {{-- Document Header --}}
                <div style="background:#1a2f4e;padding:28px 32px;text-align:center;">
                    <p style="font-size:9px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:#b8922a;margin:0 0 8px;">Kiribati Seafood Testing Laboratory</p>
                    <h2 style="font-family:'Georgia',serif;font-size:20px;font-weight:700;color:#fff;margin:0 0 8px;">Seafood Laboratory Service Agreement</h2>
                    <p style="font-size:12px;color:#94a3b8;margin:0;">Please read all sections before signing</p>
                </div>

                {{-- Agreement Body --}}
                <div style="padding:28px 32px;font-size:13px;color:#374151;line-height:1.7;">

                    {{-- 1. Parties --}}
                    <section style="margin-bottom:24px;">
                        <h2 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0 0 10px;padding-bottom:8px;border-bottom:2px solid #b8922a;">1. Parties</h2>
                        <p style="margin:0 0 8px;">This Service Agreement is entered into between:</p>
                        <ul style="margin:0 0 10px;padding-left:20px;">
                            <li style="margin-bottom:4px;"><strong>Kiribati Seafood Testing Laboratory (KSTL)</strong>, hereafter referred to as <em>"the Laboratory"</em></li>
                            <li><strong>{{ $client->company_name }}</strong>, hereafter referred to as <em>"the Client"</em></li>
                        </ul>
                        <p style="margin:0;">
                            <strong>Effective Date:</strong>
                            <span style="color:#1a2f4e;font-weight:600;">{{ now()->format('d F Y') }}</span>
                            (date of digital signature)
                        </p>
                    </section>

                    {{-- 2. Scope of Services --}}
                    <section style="margin-bottom:24px;">
                        <h2 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0 0 10px;padding-bottom:8px;border-bottom:2px solid #b8922a;">2. Scope of Services</h2>
                        <p style="margin:0 0 10px;">The Laboratory agrees to provide testing services for seafood and/or water samples, including but not limited to:</p>

                        <div style="margin-bottom:12px;">
                            <p style="font-weight:700;color:#1a2f4e;margin:0 0 8px;">Microbiology Analysis</p>
                            <div style="margin-left:16px;">
                                <p style="font-weight:600;margin:0 0 4px;">1. Water Samples (Colilert &amp; Enterolert):</p>
                                <ul style="margin:0 0 10px;padding-left:20px;">
                                    <li>Total Coliforms</li>
                                    <li><em>E. coli</em></li>
                                    <li><em>Enterococci</em> &amp; Faecal Coliforms</li>
                                </ul>
                                <p style="font-weight:600;margin:0 0 4px;">2. Fish and Fishery Samples (Petrifilm):</p>
                                <ul style="margin:0 0 8px;padding-left:20px;">
                                    <li>Yeast &amp; Mold</li>
                                    <li>APC (Aerobic Plate Count)</li>
                                    <li><em>E. coli</em> &amp; Coliform</li>
                                    <li><em>Staph. aureus</em></li>
                                </ul>
                            </div>
                        </div>

                        <div style="margin-bottom:10px;">
                            <p style="font-weight:700;color:#1a2f4e;margin:0 0 8px;">Chemical Analysis</p>
                            <ol style="margin:0;padding-left:20px;">
                                <li>Histamine — Rapid Kit</li>
                                <li>Moisture</li>
                                <li>pH</li>
                                <li>Conductivity</li>
                                <li>Water Activity</li>
                            </ol>
                        </div>

                        <p style="margin:0;font-size:12px;color:#64748b;">
                            All services will be conducted in accordance with laboratory guidelines, standard operating
                            procedures (SOPs), and compliance with international standard <strong>ISO 17025</strong>.
                        </p>
                    </section>

                    {{-- 3. Client Application --}}
                    <section style="margin-bottom:24px;">
                        <h2 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0 0 10px;padding-bottom:8px;border-bottom:2px solid #b8922a;">3. Client Application</h2>
                        <p style="margin:0;">
                            All test requests for laboratory services shall be submitted through the Laboratory's
                            designated database system. The Client is required to complete and submit the relevant
                            application form with accurate and complete information prior to sample delivery.
                        </p>
                    </section>

                    {{-- 4. Sample Submission and Handling --}}
                    <section style="margin-bottom:24px;">
                        <h2 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0 0 10px;padding-bottom:8px;border-bottom:2px solid #b8922a;">4. Sample Submission and Handling</h2>
                        <p style="margin:0 0 8px;">The Client shall ensure samples are:</p>
                        <ul style="margin:0 0 10px;padding-left:20px;">
                            <li>Properly labeled and documented</li>
                            <li>Collected, stored, and transported under chilled and appropriate conditions</li>
                        </ul>
                        <p style="margin:0;">
                            The Laboratory will notify the Client when samples do not comply with the assessment
                            criteria — to proceed for testing or to resubmit a new sample.
                        </p>
                    </section>

                    {{-- 5. Turnaround Time --}}
                    <section style="margin-bottom:24px;">
                        <h2 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0 0 10px;padding-bottom:8px;border-bottom:2px solid #b8922a;">5. Turnaround Time</h2>
                        <p style="margin:0;">
                            Standard turnaround times range from <strong>24 hours to 5 working days</strong>.
                            However, turnaround times may be extended due to unforeseen circumstances as stipulated
                            under <em>Force Majeure</em> (Section 10).
                        </p>
                    </section>

                    {{-- 6. Fees and Payment --}}
                    <section style="margin-bottom:24px;">
                        <h2 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0 0 10px;padding-bottom:8px;border-bottom:2px solid #b8922a;">6. Fees and Payment</h2>
                        <p style="margin:0 0 10px;">Testing fees are outlined in <strong>Schedule A (Pricing List)</strong>.</p>
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:3px;padding:14px 16px;font-size:12px;">
                            <p style="font-weight:700;color:#1a2f4e;margin:0 0 8px;">Payment Terms:</p>
                            <p style="margin:0 0 8px;">
                                The Client agrees to pay all fees for services provided by the Laboratory as outlined
                                in Schedule A. Invoices will be issued after sample assessment for analysis.
                            </p>
                            <p style="margin:0 0 8px;">
                                In the event that samples are rejected, the Client will be notified accordingly.
                                Should the Client agree to proceed under the stated conditions, an invoice will be issued.
                                <strong>Payment is due within 10 days</strong> from the invoice date.
                            </p>
                            <p style="margin:0;">
                                Payments shall be made via <strong>bank transfer, cash, or cheque</strong>
                                to the account details provided on the invoice. The Client must provide a
                                transaction reference number for all payments.
                            </p>
                        </div>
                    </section>

                    {{-- 7. Reporting of Results --}}
                    <section style="margin-bottom:24px;">
                        <h2 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0 0 10px;padding-bottom:8px;border-bottom:2px solid #b8922a;">7. Reporting of Results</h2>
                        <ul style="margin:0;padding-left:20px;">
                            <li style="margin-bottom:4px;">Results will be issued in an official laboratory test report.</li>
                            <li style="margin-bottom:4px;">Interpretation of results (if requested) will be provided within the Laboratory's scope.</li>
                            <li>Test results are intended solely for the Client and for the specific samples submitted. Results must not be altered or misrepresented in any form.</li>
                        </ul>
                    </section>

                    {{-- 8. Confidentiality --}}
                    <section style="margin-bottom:24px;">
                        <h2 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0 0 10px;padding-bottom:8px;border-bottom:2px solid #b8922a;">8. Confidentiality</h2>
                        <ul style="margin:0;padding-left:20px;">
                            <li style="margin-bottom:4px;">All client information and results shall remain confidential.</li>
                            <li>Disclosure will only occur with Client consent.</li>
                        </ul>
                    </section>

                    {{-- 9. Revocation --}}
                    <section style="margin-bottom:24px;">
                        <h2 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0 0 10px;padding-bottom:8px;border-bottom:2px solid #b8922a;">9. Revocation</h2>
                        <ul style="margin:0;padding-left:20px;">
                            <li style="margin-bottom:4px;">Either party may revoke the agreement with <strong>10 days prior written notice</strong>.</li>
                            <li>Immediate revocation may occur in cases of breach of this service agreement.</li>
                        </ul>
                    </section>

                    {{-- 10. Force Majeure --}}
                    <section style="margin-bottom:24px;">
                        <h2 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0 0 10px;padding-bottom:8px;border-bottom:2px solid #b8922a;">10. Force Majeure</h2>
                        <p style="margin:0;">
                            The Laboratory shall not be liable for delays or failure due to events beyond its control
                            (e.g., natural disasters, equipment malfunction, supply shortages, inadequate facility
                            conditions and utilities).
                        </p>
                    </section>

                    {{-- 11. Acceptance --}}
                    <section>
                        <h2 style="font-family:'Georgia',serif;font-size:15px;font-weight:700;color:#1a2f4e;margin:0 0 10px;padding-bottom:8px;border-bottom:2px solid #b8922a;">11. Acceptance</h2>
                        <p style="margin:0;">
                            By signing below, both parties agree to the terms of this Service Agreement.
                            <strong>The Service Agreement is valid for 1 year</strong> from the date of signing.
                        </p>
                    </section>

                </div>
            </div>

            {{-- Signature Form --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                    <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">
                        {{ $client->service_agreement_signed_at ? 'Agreement Signed' : 'Digital Signature — Client Representative' }}
                    </h3>
                    <p style="font-size:12px;color:#64748b;margin:6px 0 0;">
                        @if($client->service_agreement_signed_at)
                            This agreement was signed on
                            <strong>{{ $client->service_agreement_signed_at->format('d F Y \a\t H:i') }}</strong>.
                        @else
                            By completing this form you are digitally signing the Service Agreement on behalf of
                            <strong>{{ $client->company_name }}</strong>.
                        @endif
                    </p>
                </div>

                @if($client->service_agreement_signed_at)

                    {{-- ── Already Signed — read-only view ──────────────────────── --}}
                    <div style="padding:24px;">

                        {{-- Signed confirmation banner --}}
                        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:4px;padding:12px 16px;margin-bottom:20px;">
                            <p style="font-size:13px;font-weight:700;color:#166534;margin:0 0 2px;">Service Agreement Signed</p>
                            <p style="font-size:13px;color:#15803d;margin:0;">
                                Valid from {{ $client->service_agreement_signed_at->format('d M Y') }}
                                to {{ $client->service_agreement_signed_at->addYear()->format('d M Y') }}.
                            </p>
                        </div>

                        {{-- Signatory details --}}
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                            <div>
                                <p style="font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Signed By</p>
                                <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0;">
                                    {{ $client->responsible_officer_name ?? '—' }}
                                </p>
                            </div>
                            <div>
                                <p style="font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Company</p>
                                <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0;">{{ $client->company_name }}</p>
                            </div>
                            <div>
                                <p style="font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Date Signed</p>
                                <p style="font-size:13px;color:#374151;margin:0;">
                                    {{ $client->service_agreement_signed_at->format('d F Y \a\t H:i') }}
                                </p>
                            </div>
                            <div>
                                <p style="font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Signature Method</p>
                                <p style="font-size:13px;color:#374151;margin:0;text-transform:capitalize;">
                                    {{ $client->signature_type === 'drawn' ? 'Drawn on screen' : 'Uploaded image' }}
                                </p>
                            </div>
                        </div>

                        {{-- Signature image --}}
                        @if($client->signature_data)
                            <div style="margin-bottom:20px;">
                                <p style="font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#64748b;margin:0 0 8px;">Signature</p>
                                <div style="border:1px solid #e2e8f0;border-radius:3px;padding:16px;background:#f8fafc;display:inline-block;width:100%;box-sizing:border-box;">
                                    <img src="{{ $client->signature_data }}"
                                        alt="Signature of {{ $client->responsible_officer_name }}"
                                        style="max-height:128px;object-fit:contain;object-position:left;">
                                </div>
                            </div>
                        @endif

                        {{-- Audit trail note --}}
                        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:3px;padding:12px 16px;margin-bottom:20px;font-size:12px;color:#1d4ed8;">
                            This signed agreement is recorded with a full audit trail including IP address,
                            browser, and timestamp. It is legally binding for one year from the date of signing.
                        </div>

                        {{-- Back button --}}
                        <div style="display:flex;align-items:center;justify-content:space-between;padding-top:4px;">
                            <a href="{{ route('client.dashboard') }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                                &larr; Back to Dashboard
                            </a>

                            {{-- Download PDF --}}
                            <a href="{{ route('client.agreement.download') }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#1a2f4e;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;text-decoration:none;">
                                Download PDF
                            </a>
                        </div>

                    {-- Director countersign status --}
                    @if($client->director_signed_at)
                        <div style="margin-top:16px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:4px;padding:14px 16px;">
                            <p style="font-size:13px;font-weight:700;color:#166534;margin:0 0 2px;">Agreement Fully Executed</p>
                            <p style="font-size:12px;color:#15803d;margin:0;">
                                Countersigned by <strong>{{ $client->director_signed_by }}</strong>
                                on {{ $client->director_signed_at->format('d F Y') }}.
                                This agreement is now fully executed by both parties.
                            </p>
                        </div>
                    @else
                        <div style="margin-top:16px;background:#fffbeb;border:1px solid #fef08a;border-left:4px solid #b8922a;border-radius:4px;padding:14px 16px;">
                            <p style="font-size:13px;font-weight:600;color:#854d0e;margin:0 0 2px;">Awaiting Director Countersignature</p>
                            <p style="font-size:12px;color:#92400e;margin:0;">
                                Your signature has been recorded. The Laboratory Director will review and countersign the agreement.
                            </p>
                        </div>
                    @endif
                    </div>

                @else

                    {{-- ── Not yet signed — full signature form ──────────────────── --}}
                    <form method="POST" action="{{ route('client.agreement.sign') }}"
                          enctype="multipart/form-data"
                          style="padding:24px;"
                          x-data="signatureForm()"
                          @submit.prevent="handleSubmit($event)">
                        @csrf

                        @if($errors->any())
                            <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;">
                                <ul style="margin:0;padding-left:16px;font-size:13px;color:#991b1b;">
                                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Signatory Info (read-only — from account) --}}
                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:3px;padding:16px;margin-bottom:20px;">
                            <div>
                                <p style="font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Signing as</p>
                                <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0 0 2px;">{{ $user->name }}</p>
                                <p style="font-size:11px;color:#64748b;margin:0;">{{ $user->email }}</p>
                            </div>
                            <div>
                                <p style="font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Company</p>
                                <p style="font-size:13px;color:#374151;margin:0;">{{ $client->company_name }}</p>
                            </div>
                            <div>
                                <p style="font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Date</p>
                                <p style="font-size:13px;color:#374151;margin:0;">{{ now()->format('d F Y') }}</p>
                            </div>
                        </div>

                        {{-- ── Signature Section ──────────────────────────────────── --}}
                        <div style="margin-bottom:20px;">
                            <label style="display:block;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#475569;margin-bottom:6px;">Your Signature *</label>
                            <p style="font-size:12px;color:#64748b;margin:0 0 12px;">
                                Draw your signature in the box below, or upload an image of your handwritten signature.
                            </p>

                            {{-- Tab Buttons --}}
                            <div style="display:flex;gap:8px;margin-bottom:16px;">
                                <button type="button"
                                        @click="switchTab('draw')"
                                        :style="tab === 'draw' ? 'background:#1a2f4e;color:#fff;border-color:#1a2f4e;' : 'background:#f1f5f9;color:#475569;border-color:#e2e8f0;'"
                                        style="padding:8px 16px;font-size:12px;font-weight:700;border-radius:3px;border:1px solid;cursor:pointer;letter-spacing:.04em;">
                                    Draw Signature
                                </button>
                                <button type="button"
                                        @click="switchTab('upload')"
                                        :style="tab === 'upload' ? 'background:#1a2f4e;color:#fff;border-color:#1a2f4e;' : 'background:#f1f5f9;color:#475569;border-color:#e2e8f0;'"
                                        style="padding:8px 16px;font-size:12px;font-weight:700;border-radius:3px;border:1px solid;cursor:pointer;letter-spacing:.04em;">
                                    Upload Signature
                                </button>
                            </div>

                            {{-- Draw Tab --}}
                            <div x-show="tab === 'draw'" x-cloak>
                                <div style="border:2px solid #cbd5e1;border-radius:3px;overflow:hidden;background:#fff;">
                                    <canvas id="signatureCanvas"
                                            style="width:100%;height:200px;touch-action:none;cursor:crosshair;display:block;"
                                            @mousedown="startDrawing($event)"
                                            @mousemove="draw($event)"
                                            @mouseup="stopDrawing()"
                                            @mouseleave="stopDrawing()"
                                            @touchstart.prevent="startDrawing($event.touches[0])"
                                            @touchmove.prevent="draw($event.touches[0])"
                                            @touchend="stopDrawing()">
                                    </canvas>
                                </div>
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-top:8px;">
                                    <p style="font-size:11px;color:#94a3b8;margin:0;">Sign inside the box using mouse or finger</p>
                                    <button type="button"
                                            @click="clearSignature()"
                                            style="font-size:11px;color:#dc2626;background:none;border:none;cursor:pointer;font-weight:600;padding:0;">
                                        Clear Signature
                                    </button>
                                </div>
                            </div>

                            {{-- Upload Tab --}}
                            <div x-show="tab === 'upload'" x-cloak>
                                <div style="border:2px dashed #cbd5e1;border-radius:3px;padding:32px;text-align:center;background:#f8fafc;">
                                    <p style="font-size:13px;color:#64748b;margin:0 0 12px;">Upload image of your handwritten signature</p>
                                    <label style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;border:1px solid #1a2f4e;border-radius:3px;cursor:pointer;">
                                        Choose File
                                        <input type="file"
                                               name="signature_upload"
                                               accept="image/jpeg,image/png"
                                               style="display:none;"
                                               :disabled="tab !== 'upload'"
                                               @change="handleUpload($event)">
                                    </label>
                                    <p style="font-size:11px;color:#94a3b8;margin:12px 0 0;">JPG or PNG only &bull; Max 2MB</p>

                                    <div x-show="uploadPreview" style="margin-top:16px;">
                                        <img :src="uploadPreview"
                                             alt="Signature preview"
                                             style="max-height:120px;margin:0 auto;border:1px solid #e2e8f0;border-radius:3px;background:#fff;padding:8px;display:block;">
                                        <p style="font-size:11px;color:#16a34a;margin:8px 0 0;">Signature image ready</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Hidden inputs — set directly by JS in handleSubmit --}}
                            <input type="hidden" id="hiddenSignatureData" name="signature_data" value="">
                            <input type="hidden" id="hiddenSignatureType" name="signature_type" value="drawn">

                            <x-input-error for="signature_data" class="mt-1"/>
                            <x-input-error for="signature_upload" class="mt-1"/>
                            <x-input-error for="signature_type" class="mt-1"/>
                        </div>

                        {{-- Legal notice --}}
                        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:3px;padding:14px 16px;margin-bottom:20px;font-size:13px;color:#1d4ed8;">
                            This digital signature is legally binding. Your name, email address
                            (<strong>{{ $user->email }}</strong>), IP address, and the date and time
                            of signing will be securely recorded as part of the audit trail.
                        </div>

                        {{-- Declaration Checkbox --}}
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:3px;padding:16px;margin-bottom:20px;">
                            <label style="display:flex;align-items:flex-start;gap:12px;cursor:pointer;">
                                <input type="checkbox"
                                       id="declaration_accepted"
                                       name="declaration_accepted"
                                       value="1"
                                       {{ old('declaration_accepted') ? 'checked' : '' }}
                                       required
                                       style="margin-top:2px;width:16px;height:16px;flex-shrink:0;">
                                <span style="font-size:13px;color:#374151;line-height:1.6;">
                                    I confirm that I have read, understood, and agree to all terms and conditions
                                    of the Seafood Laboratory Service Agreement on behalf of
                                    <strong>{{ $client->company_name }}</strong>.
                                    I have the authority to enter into this agreement on behalf of my organisation.
                                </span>
                            </label>
                            <x-input-error for="declaration_accepted" class="mt-2"/>
                        </div>

                        {{-- Actions --}}
                        <div style="display:flex;align-items:center;justify-content:space-between;padding-top:4px;">
                            <a href="{{ route('client.dashboard') }}" style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                                Back to Dashboard
                            </a>
                            <button type="submit" style="display:inline-flex;align-items:center;gap:8px;padding:10px 24px;background:#0d9488;color:#fff;font-size:13px;font-weight:700;letter-spacing:.06em;border-radius:3px;border:none;cursor:pointer;">
                                Sign &amp; Submit Agreement
                            </button>
                        </div>

                    </form>

                @endif
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Signature Script --}}
<script>
function signatureForm() {
    return {
        tab: 'draw',
        signatureData: '',
        uploadPreview: null,
        canvas: null,
        ctx: null,
        isDrawing: false,

        init() {
            this.$nextTick(() => {
                if (this.tab === 'draw') this.setupCanvas();
            });
        },

        setupCanvas() {
            this.canvas = document.getElementById('signatureCanvas');
            if (!this.canvas) return;

            this.ctx = this.canvas.getContext('2d', { alpha: true });

            const dpr = window.devicePixelRatio || 1;
            const rect = this.canvas.getBoundingClientRect();

            this.canvas.width = rect.width * dpr;
            this.canvas.height = rect.height * dpr;
            this.ctx.scale(dpr, dpr);

            this.ctx.strokeStyle = '#1e293b';
            this.ctx.lineWidth = 3;
            this.ctx.lineCap = 'round';
            this.ctx.lineJoin = 'round';
        },

        switchTab(newTab) {
            this.tab = newTab;
            if (newTab === 'draw') {
                this.$nextTick(() => this.setupCanvas());
            }
        },

        getCoordinates(e) {
            const rect = this.canvas.getBoundingClientRect();
            return {
                x: e.clientX - rect.left,
                y: e.clientY - rect.top
            };
        },

        startDrawing(e) {
            this.isDrawing = true;
            const pos = this.getCoordinates(e);
            this.ctx.beginPath();
            this.ctx.moveTo(pos.x, pos.y);
        },

        draw(e) {
            if (!this.isDrawing) return;
            const pos = this.getCoordinates(e);
            this.ctx.lineTo(pos.x, pos.y);
            this.ctx.stroke();
        },

        stopDrawing() {
            this.isDrawing = false;
        },

        clearSignature() {
            if (!this.ctx || !this.canvas) return;
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
            this.signatureData = '';
        },

        handleSubmit(e) {
            const typeInput = document.getElementById('hiddenSignatureType');
            const dataInput = document.getElementById('hiddenSignatureData');

            if (this.tab === 'draw') {
                // Capture canvas
                if (!this.canvas) {
                    alert('Signature canvas not ready. Please refresh and try again.');
                    return;
                }
                const dataUrl = this.canvas.toDataURL('image/png');
                if (dataUrl.length < 1000) {
                    alert('Please draw your signature before submitting.');
                    return;
                }
                typeInput.value = 'drawn';
                dataInput.value = dataUrl;
            } else {
                // Upload tab
                typeInput.value = 'uploaded';
                dataInput.value = '';
            }

            // Submit the form natively (bypasses Alpine reactivity timing issues)
            e.target.submit();
        },

        handleUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                alert("File is too large. Maximum 2MB allowed.");
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                this.uploadPreview = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
}
</script>