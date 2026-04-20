<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>KSTL Service Agreement — {{ $client->company_name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            line-height: 1.6;
        }

        .page { padding: 40px 50px; }

        /* Header */
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #1e3a5f; padding-bottom: 20px; }
        .header .ministry { font-size: 10px; color: #555; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
        .header h1 { font-size: 16px; font-weight: bold; color: #1e3a5f; margin-bottom: 4px; }
        .header .subtitle { font-size: 10px; color: #777; }

        /* Reference box */
        .ref-box {
            background: #f0f4f8;
            border: 1px solid #c5d5e8;
            border-radius: 4px;
            padding: 10px 14px;
            margin-bottom: 24px;
            font-size: 10px;
        }
        .ref-box table { width: 100%; border-collapse: collapse; }
        .ref-box td { padding: 2px 8px 2px 0; }
        .ref-box .label { color: #666; width: 130px; }
        .ref-box .value { font-weight: bold; color: #1a1a1a; }

        /* Section headings */
        h2 {
            font-size: 12px;
            font-weight: bold;
            color: #1e3a5f;
            border-bottom: 1px solid #dde5ef;
            padding-bottom: 4px;
            margin: 20px 0 8px 0;
        }

        p { margin-bottom: 6px; }

        ul { margin: 6px 0 6px 18px; }
        ul li { margin-bottom: 3px; }

        ol { margin: 6px 0 6px 18px; }
        ol li { margin-bottom: 3px; }

        .sub-label {
            font-size: 10px;
            font-weight: bold;
            color: #444;
            margin: 8px 0 4px 0;
        }

        .highlight-box {
            background: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 3px;
            padding: 8px 12px;
            margin: 6px 0;
            font-size: 10.5px;
        }

        /* Signature section */
        .signature-section {
            margin-top: 30px;
            border-top: 2px solid #1e3a5f;
            padding-top: 20px;
        }

        .sig-grid { width: 100%; border-collapse: collapse; margin-top: 16px; }
        .sig-grid td { vertical-align: top; padding: 0 20px 16px 0; width: 50%; }

        .sig-field { margin-bottom: 12px; }
        .sig-field .sig-label { font-size: 9px; text-transform: uppercase; color: #888; letter-spacing: 0.5px; margin-bottom: 3px; }
        .sig-field .sig-value { font-size: 11px; font-weight: bold; color: #1a1a1a; }
        .sig-field .sig-value.italic { font-style: italic; }

        .sig-image-box {
            border: 1px solid #ccc;
            border-radius: 3px;
            padding: 8px;
            background: #fff;
            min-height: 70px;
            margin-top: 8px;
        }

        .sig-image-box img {
            max-height: 70px;
            max-width: 100%;
        }

        .validity-badge {
            display: inline-block;
            background: #e6f4ea;
            border: 1px solid #a8d5b5;
            color: #2d7a4a;
            padding: 4px 10px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            margin-top: 6px;
        }

        .audit-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 3px;
            padding: 8px 12px;
            margin-top: 16px;
            font-size: 9.5px;
            color: #1e40af;
        }

        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 8.5px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="header">
        <p class="ministry">Government of Kiribati — Ministry of Fisheries and Ocean Resources</p>
        <h1>Seafood Laboratory Service Agreement</h1>
        <p class="subtitle">Kiribati Seafood Toxicology Laboratory (KSTL) &bull; stld@mfor.gov.ki</p>
    </div>

    {{-- Reference Box --}}
    <div class="ref-box">
        <table>
            <tr>
                <td class="label">Client:</td>
                <td class="value">{{ $client->company_name }}</td>
                <td class="label">Signed By:</td>
                <td class="value">{{ $client->responsible_officer_name }}</td>
            </tr>
            <tr>
                <td class="label">Date Signed:</td>
                <td class="value">{{ $client->service_agreement_signed_at->format('d F Y') }}</td>
                <td class="label">Valid Until:</td>
                <td class="value">{{ $client->service_agreement_signed_at->addYear()->format('d F Y') }}</td>
            </tr>
        </table>
    </div>

    {{-- 1. Parties --}}
    <h2>1. Parties</h2>
    <p>This Service Agreement is entered into between:</p>
    <ul>
        <li><strong>Kiribati Seafood Toxicology Laboratory (KSTL)</strong>, hereafter referred to as <em>"the Laboratory"</em></li>
        <li><strong>{{ $client->company_name }}</strong>, hereafter referred to as <em>"the Client"</em></li>
    </ul>
    <p><strong>Effective Date:</strong> {{ $client->service_agreement_signed_at->format('d F Y') }}</p>

    {{-- 2. Scope of Services --}}
    <h2>2. Scope of Services</h2>
    <p>The Laboratory agrees to provide testing services for seafood and/or water samples, including but not limited to:</p>

    <p class="sub-label">Microbiological Analysis</p>
    <p style="margin-left:10px; margin-bottom:3px;"><strong>1. Water Samples (Colilert &amp; Enterolert):</strong></p>
    <ul style="margin-left:24px;">
        <li>Total Coliforms</li>
        <li><em>E. coli</em></li>
        <li><em>Enterococci</em> &amp; Faecal Coliforms</li>
    </ul>
    <p style="margin-left:10px; margin-top:6px; margin-bottom:3px;"><strong>2. Fish and Fishery Samples (Petrifilm):</strong></p>
    <ul style="margin-left:24px;">
        <li>Yeast &amp; Mold</li>
        <li>APC (Aerobic Plate Count)</li>
        <li><em>E. coli</em> &amp; Coliform</li>
        <li><em>Staph. aureus</em></li>
    </ul>

    <p class="sub-label">Chemical Analysis</p>
    <ol style="margin-left:18px;">
        <li>Histamine — Rapid Kit</li>
        <li>Moisture</li>
        <li>pH</li>
        <li>Conductivity</li>
        <li>Water Activity</li>
    </ol>
    <p style="margin-top:6px;">All services will be conducted in accordance with laboratory guidelines, standard operating procedures (SOPs), and compliance with international standard <strong>ISO 17025</strong>.</p>

    {{-- 3. Client Application --}}
    <h2>3. Client Application</h2>
    <p>All test requests for laboratory services shall be submitted through the Laboratory's designated database system. The Client is required to complete and submit the relevant application form with accurate and complete information prior to sample delivery.</p>

    {{-- 4. Sample Submission and Handling --}}
    <h2>4. Sample Submission and Handling</h2>
    <p>The Client shall ensure samples are:</p>
    <ul>
        <li>Properly labeled and documented</li>
        <li>Collected, stored, and transported under chilled and appropriate conditions</li>
    </ul>
    <p>The Laboratory will notify the Client when samples do not comply with the assessment criteria — to proceed for testing or to resubmit a new sample.</p>

    {{-- 5. Turnaround Time --}}
    <h2>5. Turnaround Time</h2>
    <p>Standard turnaround times range from <strong>24 hours to 5 working days</strong>. However, turnaround times may be extended due to unforeseen circumstances as stipulated under <em>Force Majeure</em> (Section 10).</p>

    {{-- 6. Fees and Payment --}}
    <h2>6. Fees and Payment</h2>
    <p>Testing fees are outlined in <strong>Schedule A (Pricing List)</strong>.</p>
    <div class="highlight-box">
        <strong>Payment Terms:</strong> The Client agrees to pay all fees as outlined in Schedule A. Invoices will be issued after sample assessment. Payment is due within <strong>30 days</strong> from the invoice date. Payments shall be made via <strong>bank transfer, cash, or cheque</strong>. The Client must provide a transaction reference number for all payments.
    </div>

    {{-- 7. Reporting of Results --}}
    <h2>7. Reporting of Results</h2>
    <ul>
        <li>Results will be issued in an official laboratory test report.</li>
        <li>Interpretation of results (if requested) will be provided within the Laboratory's scope.</li>
        <li>Test results are intended solely for the Client and for the specific samples submitted. Results must not be altered or misrepresented in any form.</li>
    </ul>

    {{-- 8. Confidentiality --}}
    <h2>8. Confidentiality</h2>
    <ul>
        <li>All client information and results shall remain confidential.</li>
        <li>Disclosure will only occur with Client consent.</li>
    </ul>

    {{-- 9. Revocation --}}
    <h2>9. Revocation</h2>
    <ul>
        <li>Either party may revoke the agreement with <strong>10 days prior written notice</strong>.</li>
        <li>Immediate revocation may occur in cases of breach of this service agreement.</li>
    </ul>

    {{-- 10. Force Majeure --}}
    <h2>10. Force Majeure</h2>
    <p>The Laboratory shall not be liable for delays or failure due to events beyond its control (e.g., natural disasters, equipment malfunction, supply shortages, inadequate facility conditions and utilities).</p>

    {{-- 11. Acceptance --}}
    <h2>11. Acceptance</h2>
    <p>By signing below, both parties agree to the terms of this Service Agreement. <strong>The Service Agreement is valid for 1 year</strong> from the date of signing.</p>

    {{-- Signature Section --}}
    <div class="signature-section">
        <table class="sig-grid">
            <tr>
                {{-- Laboratory Representative --}}
                <td>
                    <p class="sub-label" style="margin-top:0;">Laboratory Representative</p>
                    <div class="sig-field">
                        <p class="sig-label">Name</p>
                        <p class="sig-value">{{ $client->director_signed_by ?? 'Kiribati Seafood Toxicology Laboratory' }}</p>
                    <div class="sig-field">
                        <p class="sig-label">Title</p>
                        <p class="sig-value">Laboratory Director, KSTL</p>
                    </div>
                    </div>
                    <div class="sig-field">
                        <p class="sig-label">Signature</p>
                        <div class="sig-image-box" style="min-height:55px; color:#aaa; font-size:10px; padding-top:18px;">
                        @if($client->director_signature_data)
                            <div class="sig-image-box">
                                <img src="{{ $client->director_signature_data }}" alt="Director signature" style="max-height:70px;"/>
                            </div>
                        @else
                            <div class="sig-image-box" style="min-height:55px; color:#aaa; font-size:10px; padding-top:18px;">
                                Awaiting Director countersignature
                            </div>
                        @endif
                    </div>
                    <div class="sig-field">
                        <p class="sig-label">Date</p>
                        <p class="sig-value">{{ $client->director_signed_at ? $client->director_signed_at->format('d F Y') : '—' }}</p>
                    </div>
                    @if($client->director_signed_at)
                        <span class="validity-badge">✓ Countersigned &bull; {{ $client->director_signed_at->format('d M Y') }}</span>
                    @endif
                </td>

                {{-- Client Representative --}}
                <td>
                    <p class="sub-label" style="margin-top:0;">Client Representative</p>
                    <div class="sig-field">
                        <p class="sig-label">Name</p>
                        <p class="sig-value">{{ $client->responsible_officer_name }}</p>
                    </div>
                    <div class="sig-field">
                        <p class="sig-label">Company</p>
                        <p class="sig-value">{{ $client->company_name }}</p>
                    </div>
                    <div class="sig-field">
                        <p class="sig-label">Signature</p>
                        @if($client->signature_data)
                            <div class="sig-image-box">
                                <img src="{{ $client->signature_data }}"
                                     alt="Signature of {{ $client->responsible_officer_name }}"/>
                            </div>
                        @else
                            <div class="sig-image-box" style="color:#aaa; font-size:10px; padding-top:18px;">
                                Digital signature on file
                            </div>
                        @endif
                    </div>
                    <div class="sig-field">
                        <p class="sig-label">Date</p>
                        <p class="sig-value">{{ $client->director_signed_at ? $client->director_signed_at->format('d F Y') : '—' }}</p>
                    </div>
                    @if($client->director_signed_at)
                        <span class="validity-badge">✓ Countersigned &bull; {{ $client->director_signed_at->format('d M Y') }}</span>
                    @endif
                    <span class="validity-badge">
                        ✓ Digitally Signed &bull; Valid until {{ $client->service_agreement_signed_at->addYear()->format('d M Y') }}
                    </span>
                </td>
            </tr>
        </table>

        <div class="audit-box">
            <strong>Audit Trail:</strong>
            Client signed on {{ $client->service_agreement_signed_at->format('d F Y \a\t H:i:s') }} UTC
            by {{ $client->responsible_officer_name }} ({{ $user->email }})
            on behalf of {{ $client->company_name }}.
            Client signature: {{ $client->signature_type === 'drawn' ? 'Drawn on screen' : 'Uploaded image' }}.
            @if($client->director_signed_at)
            Director countersigned on {{ $client->director_signed_at->format('d F Y \\a\\t H:i:s') }} UTC by {{ $client->director_signed_by }}.
            Director signature: {{ $client->director_signature_type === 'drawn' ? 'Drawn on screen' : 'Uploaded image' }}.
            @else
            Director countersignature: Pending.
            @endif
            This record is stored securely and cannot be altered.
        </div>
    </div>

    <div class="footer">
        Generated by KSTL Lab Management System &bull; {{ now()->format('d M Y H:i') }} &bull; {{ $client->company_name }}
    </div>

</div>
</body>
</html>