<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Seafood Laboratory Service Agreement
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Intro Banner --}}
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l-.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-800">Action required before submitting samples</p>
                        <p class="text-sm text-blue-700 mt-1">
                            Please read the entire service agreement carefully. You must sign it before
                            you can submit samples to the laboratory.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Agreement Document --}}
            <div class="bg-white shadow rounded-xl overflow-hidden">

                {{-- Document Header --}}
                <div class="bg-gray-800 px-8 py-6 text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Kiribati Seafood Testing Laboratory</p>
                    <h1 class="text-xl font-bold text-white">Seafood Laboratory Service Agreement</h1>
                    <p class="text-gray-400 text-sm mt-2">Please read all sections before signing</p>
                </div>

                {{-- Agreement Body --}}
                <div class="px-8 py-8 prose prose-sm max-w-none text-gray-700 space-y-6 leading-relaxed">

                    {{-- 1. Parties --}}
                    <section>
                        <h2 class="text-base font-bold text-gray-900 border-b border-gray-200 pb-2 mb-3">
                            1. Parties
                        </h2>
                        <p>This Service Agreement is entered into between:</p>
                        <ul class="list-disc ml-6 space-y-1 mt-2">
                            <li><strong>Kiribati Seafood Testing Laboratory (KSTL)</strong>, hereafter referred to as <em>"the Laboratory"</em></li>
                            <li><strong>{{ $client->company_name }}</strong>, hereafter referred to as <em>"the Client"</em></li>
                        </ul>
                        <p class="mt-3">
                            <strong>Effective Date:</strong>
                            <span class="text-blue-600 font-medium">{{ now()->format('d F Y') }}</span>
                            (date of digital signature)
                        </p>
                    </section>

                    {{-- 2. Scope of Services --}}
                    <section>
                        <h2 class="text-base font-bold text-gray-900 border-b border-gray-200 pb-2 mb-3">
                            2. Scope of Services
                        </h2>
                        <p>The Laboratory agrees to provide testing services for seafood and/or water samples, including but not limited to:</p>

                        <div class="mt-3 space-y-4">
                            <div>
                                <p class="font-semibold text-gray-800">Microbiological Analysis</p>
                                <div class="ml-4 mt-2 space-y-3">
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">1. Water Samples (Colilert &amp; Enterolert):</p>
                                        <ul class="list-disc ml-6 text-sm space-y-1 mt-1">
                                            <li>Total Coliforms</li>
                                            <li><em>E. coli</em></li>
                                            <li><em>Enterococci</em> &amp; Faecal Coliforms</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-700">2. Fish and Fishery Samples (Petrifilm):</p>
                                        <ul class="list-disc ml-6 text-sm space-y-1 mt-1">
                                            <li>Yeast &amp; Mold</li>
                                            <li>APC (Aerobic Plate Count)</li>
                                            <li><em>E. coli</em> &amp; Coliform</li>
                                            <li><em>Staph. aureus</em></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <p class="font-semibold text-gray-800">Chemical Analysis</p>
                                <ul class="list-decimal ml-6 text-sm space-y-1 mt-2">
                                    <li>Histamine — Rapid Kit</li>
                                    <li>Moisture</li>
                                    <li>pH</li>
                                    <li>Conductivity</li>
                                    <li>Water Activity</li>
                                </ul>
                            </div>
                        </div>

                        <p class="mt-3 text-sm text-gray-600">
                            All services will be conducted in accordance with laboratory guidelines, standard operating
                            procedures (SOPs), and compliance with international standard <strong>ISO 17025</strong>.
                        </p>
                    </section>

                    {{-- 3. Client Application --}}
                    <section>
                        <h2 class="text-base font-bold text-gray-900 border-b border-gray-200 pb-2 mb-3">
                            3. Client Application
                        </h2>
                        <p>
                            All test requests for laboratory services shall be submitted through the Laboratory's
                            designated database system. The Client is required to complete and submit the relevant
                            application form with accurate and complete information prior to sample delivery.
                        </p>
                    </section>

                    {{-- 4. Sample Submission and Handling --}}
                    <section>
                        <h2 class="text-base font-bold text-gray-900 border-b border-gray-200 pb-2 mb-3">
                            4. Sample Submission and Handling
                        </h2>
                        <p>The Client shall ensure samples are:</p>
                        <ul class="list-disc ml-6 mt-2 space-y-1">
                            <li>Properly labeled and documented</li>
                            <li>Collected, stored, and transported under chilled and appropriate conditions</li>
                        </ul>
                        <p class="mt-3">
                            The Laboratory will notify the Client when samples do not comply with the assessment
                            criteria — to proceed for testing or to resubmit a new sample.
                        </p>
                    </section>

                    {{-- 5. Turnaround Time --}}
                    <section>
                        <h2 class="text-base font-bold text-gray-900 border-b border-gray-200 pb-2 mb-3">
                            5. Turnaround Time
                        </h2>
                        <p>
                            Standard turnaround times range from <strong>24 hours to 5 working days</strong>.
                            However, turnaround times may be extended due to unforeseen circumstances as stipulated
                            under <em>Force Majeure</em> (Section 10).
                        </p>
                    </section>

                    {{-- 6. Fees and Payment --}}
                    <section>
                        <h2 class="text-base font-bold text-gray-900 border-b border-gray-200 pb-2 mb-3">
                            6. Fees and Payment
                        </h2>
                        <p>Testing fees are outlined in <strong>Schedule A (Pricing List)</strong>.</p>
                        <div class="mt-3 bg-gray-50 border border-gray-200 rounded-lg p-4 space-y-2 text-sm">
                            <p class="font-medium text-gray-800">Payment Terms:</p>
                            <p>
                                The Client agrees to pay all fees for services provided by the Laboratory as outlined
                                in Schedule A. Invoices will be issued after sample assessment for analysis.
                            </p>
                            <p>
                                In the event that samples are rejected, the Client will be notified accordingly.
                                Should the Client agree to proceed under the stated conditions, an invoice will be issued.
                                <strong>Payment is due within 30 days</strong> from the invoice date.
                            </p>
                            <p>
                                Payments shall be made via <strong>bank transfer, cash, or cheque</strong>
                                to the account details provided on the invoice. The Client must provide a
                                transaction reference number for all payments.
                            </p>
                        </div>
                    </section>

                    {{-- 7. Reporting of Results --}}
                    <section>
                        <h2 class="text-base font-bold text-gray-900 border-b border-gray-200 pb-2 mb-3">
                            7. Reporting of Results
                        </h2>
                        <ul class="list-disc ml-6 space-y-1">
                            <li>Results will be issued in an official laboratory test report.</li>
                            <li>Interpretation of results (if requested) will be provided within the Laboratory's scope.</li>
                            <li>Test results are intended solely for the Client and for the specific samples submitted. Results must not be altered or misrepresented in any form.</li>
                        </ul>
                    </section>

                    {{-- 8. Confidentiality --}}
                    <section>
                        <h2 class="text-base font-bold text-gray-900 border-b border-gray-200 pb-2 mb-3">
                            8. Confidentiality
                        </h2>
                        <ul class="list-disc ml-6 space-y-1">
                            <li>All client information and results shall remain confidential.</li>
                            <li>Disclosure will only occur with Client consent.</li>
                        </ul>
                    </section>

                    {{-- 9. Revocation --}}
                    <section>
                        <h2 class="text-base font-bold text-gray-900 border-b border-gray-200 pb-2 mb-3">
                            9. Revocation
                        </h2>
                        <ul class="list-disc ml-6 space-y-1">
                            <li>Either party may revoke the agreement with <strong>10 days prior written notice</strong>.</li>
                            <li>Immediate revocation may occur in cases of breach of this service agreement.</li>
                        </ul>
                    </section>

                    {{-- 10. Force Majeure --}}
                    <section>
                        <h2 class="text-base font-bold text-gray-900 border-b border-gray-200 pb-2 mb-3">
                            10. Force Majeure
                        </h2>
                        <p>
                            The Laboratory shall not be liable for delays or failure due to events beyond its control
                            (e.g., natural disasters, equipment malfunction, supply shortages, inadequate facility
                            conditions and utilities).
                        </p>
                    </section>

                    {{-- 11. Acceptance --}}
                    <section>
                        <h2 class="text-base font-bold text-gray-900 border-b border-gray-200 pb-2 mb-3">
                            11. Acceptance
                        </h2>
                        <p>
                            By signing below, both parties agree to the terms of this Service Agreement.
                            <strong>The Service Agreement is valid for 1 year</strong> from the date of signing.
                        </p>
                    </section>

                </div>
            </div>

            {{-- Signature Form --}}
            <div class="bg-white shadow rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">
                        {{ $client->service_agreement_signed_at ? 'Agreement Signed' : 'Digital Signature — Client Representative' }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
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
                    <div class="px-6 py-6 space-y-5">

                        {{-- Signed confirmation banner --}}
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-green-800">Service Agreement Signed</p>
                                <p class="text-sm text-green-700 mt-0.5">
                                    Valid from {{ $client->service_agreement_signed_at->format('d M Y') }}
                                    to {{ $client->service_agreement_signed_at->addYear()->format('d M Y') }}.
                                </p>
                            </div>
                        </div>

                        {{-- Signatory details --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Signed By</p>
                                <p class="text-sm font-medium text-gray-800 mt-1">
                                    {{ $client->responsible_officer_name ?? '—' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Company</p>
                                <p class="text-sm font-medium text-gray-800 mt-1">{{ $client->company_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Date Signed</p>
                                <p class="text-sm text-gray-800 mt-1">
                                    {{ $client->service_agreement_signed_at->format('d F Y \a\t H:i') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Signature Method</p>
                                <p class="text-sm text-gray-800 mt-1 capitalize">
                                    {{ $client->signature_type === 'drawn' ? '✏️ Drawn on screen' : '📁 Uploaded image' }}
                                </p>
                            </div>
                        </div>

                        {{-- Signature image --}}
                        @if($client->signature_data)
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Signature</p>
                                <div class="border border-gray-200 rounded-xl p-4 bg-gray-50 inline-block w-full">
                                    <img src="{{ $client->signature_data }}"
                                        alt="Signature of {{ $client->responsible_officer_name }}"
                                        class="max-h-32 object-contain object-left"/>
                                </div>
                            </div>
                        @endif

                        {{-- Audit trail note --}}
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 text-xs text-blue-700">
                            This signed agreement is recorded with a full audit trail including IP address,
                            browser, and timestamp. It is legally binding for one year from the date of signing.
                        </div>

                        {{-- Back button --}}
                        <div class="flex items-center justify-between pt-2">
                            <a href="{{ route('client.dashboard') }}">
                                <x-secondary-button>← Back to Dashboard</x-secondary-button>
                            </a>

                            {{-- Download PDF --}}
                            <a href="{{ route('client.agreement.download') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Download PDF
                            </a>
                        </div>

                    {-- Director countersign status --}
                    @if($client->director_signed_at)
                        <div class="mt-4 flex items-center gap-2.5 bg-green-50 border border-green-200 rounded-xl p-4">
                            <svg class="w-5 h-5 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-green-800">Agreement Fully Executed</p>
                                <p class="text-xs text-green-600 mt-0.5">
                                    Countersigned by <strong>{{ $client->director_signed_by }}</strong>
                                    on {{ $client->director_signed_at->format('d F Y') }}.
                                    This agreement is now fully executed by both parties.
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="mt-4 flex items-center gap-2.5 bg-amber-50 border border-amber-200 rounded-xl p-4">
                            <svg class="w-5 h-5 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000-2z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-amber-800">Awaiting Director Countersignature</p>
                                <p class="text-xs text-amber-600 mt-0.5">
                                    Your signature has been recorded. The Laboratory Director will review and countersign the agreement.
                                </p>
                            </div>
                        </div>
                    @endif
                    </div>

                @else

                    {{-- ── Not yet signed — full signature form ──────────────────── --}}
                    <form method="POST" action="{{ route('client.agreement.sign') }}"
                          enctype="multipart/form-data"
                          class="px-6 py-6 space-y-5"
                          x-data="signatureForm()"
                          @submit.prevent="handleSubmit($event)">
                        @csrf

                        <x-validation-errors class="bg-red-50 border border-red-200 rounded-lg p-4"/>

                        {{-- Signatory Info (read-only — from account) --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-gray-50 rounded-lg p-4 border border-gray-100">
                            <div>
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-0.5">Signing as</p>
                                <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $user->email }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-0.5">Company</p>
                                <p class="text-sm text-gray-800">{{ $client->company_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-0.5">Date</p>
                                <p class="text-sm text-gray-800">{{ now()->format('d F Y') }}</p>
                            </div>
                        </div>

                        {{-- ── Signature Section ──────────────────────────────────── --}}
                        <div>
                            <x-label value="Your Signature *"/>
                            <p class="text-xs text-gray-500 mb-3">
                                Draw your signature in the box below, or upload an image of your handwritten signature.
                            </p>

                            {{-- Tab Buttons --}}
                            <div class="flex gap-2 mb-4">
                                <button type="button"
                                        @click="switchTab('draw')"
                                        :class="tab === 'draw' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                        class="px-4 py-2 text-sm font-medium rounded-lg transition">
                                    ✏️ Draw Signature
                                </button>
                                <button type="button"
                                        @click="switchTab('upload')"
                                        :class="tab === 'upload' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                        class="px-4 py-2 text-sm font-medium rounded-lg transition">
                                    📁 Upload Signature
                                </button>
                            </div>

                            {{-- Draw Tab --}}
                            <div x-show="tab === 'draw'" x-cloak>
                                <div class="border-2 border-gray-200 rounded-xl overflow-hidden bg-white shadow-inner">
                                    <canvas id="signatureCanvas"
                                            class="w-full touch-none cursor-crosshair block"
                                            style="height: 200px;"
                                            @mousedown="startDrawing($event)"
                                            @mousemove="draw($event)"
                                            @mouseup="stopDrawing()"
                                            @mouseleave="stopDrawing()"
                                            @touchstart.prevent="startDrawing($event.touches[0])"
                                            @touchmove.prevent="draw($event.touches[0])"
                                            @touchend="stopDrawing()">
                                    </canvas>
                                </div>
                                <div class="flex items-center justify-between mt-3">
                                    <p class="text-xs text-gray-400">Sign inside the box using mouse or finger</p>
                                    <button type="button"
                                            @click="clearSignature()"
                                            class="text-xs text-red-600 hover:text-red-700 font-medium">
                                        Clear Signature
                                    </button>
                                </div>
                            </div>

                            {{-- Upload Tab --}}
                            <div x-show="tab === 'upload'" x-cloak>
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center bg-gray-50 hover:bg-gray-100 transition">
                                    <svg class="w-10 h-10 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm text-gray-600 mb-2">Upload image of your handwritten signature</p>
                                    <label class="cursor-pointer inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-sm hover:bg-gray-50">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                        </svg>
                                        Choose File
                                        <input type="file"
                                               name="signature_upload"
                                               accept="image/jpeg,image/png"
                                               class="hidden"
                                               :disabled="tab !== 'upload'"
                                               @change="handleUpload($event)">
                                    </label>
                                    <p class="text-xs text-gray-400 mt-3">JPG or PNG only • Max 2MB</p>

                                    <div x-show="uploadPreview" class="mt-6">
                                        <img :src="uploadPreview"
                                             alt="Signature preview"
                                             class="max-h-40 mx-auto border border-gray-200 rounded-lg shadow-sm object-contain bg-white p-2">
                                        <p class="text-xs text-green-600 mt-2">✓ Signature image ready</p>
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
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 text-sm text-blue-800">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/>
                                </svg>
                                <p>
                                    This digital signature is legally binding. Your name, email address
                                    (<strong>{{ $user->email }}</strong>), IP address, and the date and time
                                    of signing will be securely recorded as part of the audit trail.
                                </p>
                            </div>
                        </div>

                        {{-- Declaration Checkbox --}}
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox"
                                       id="declaration_accepted"
                                       name="declaration_accepted"
                                       value="1"
                                       {{ old('declaration_accepted') ? 'checked' : '' }}
                                       required
                                       class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"/>
                                <span class="text-sm text-gray-700 leading-relaxed">
                                    I confirm that I have read, understood, and agree to all terms and conditions
                                    of the Seafood Laboratory Service Agreement on behalf of
                                    <strong>{{ $client->company_name }}</strong>.
                                    I have the authority to enter into this agreement on behalf of my organisation.
                                </span>
                            </label>
                            <x-input-error for="declaration_accepted" class="mt-2"/>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-between pt-2">
                            <a href="{{ route('client.dashboard') }}">
                                <x-secondary-button type="button">Back to Dashboard</x-secondary-button>
                            </a>
                            <button type="submit"
                                    class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition">
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