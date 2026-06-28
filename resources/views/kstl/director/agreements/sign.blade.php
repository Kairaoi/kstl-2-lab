{{-- resources/views/kstl/director/agreements/sign.blade.php --}}

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
                            <h1 style="font-family:'Georgia',serif;font-size:22px;font-weight:700;color:#fff;margin:0 0 6px;line-height:1.2;">Countersign Agreement &mdash; {{ $client->company_name }}</h1>
                            <p style="font-size:12px;color:#94a3b8;margin:0;">Client signed {{ $client->service_agreement_signed_at?->format('d M Y \a\t H:i') }}</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <a href="{{ route('director.agreements.index') }}"
                           style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                            &larr; All Agreements
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
        <div style="max-width:64rem;margin:0 auto;padding:0 2rem;">

            @if(session('error'))
                <div style="background:#fef2f2;border:1px solid #fecaca;border-left:4px solid #dc2626;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#991b1b;">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-left:4px solid #16a34a;border-radius:4px;padding:12px 16px;margin-bottom:20px;font-size:13px;color:#166534;">{{ session('success') }}</div>
            @endif

            {{-- ── Agreement Details ────────────────────────────── --}}
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                    <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0;">Agreement Details</h3>
                </div>
                <div style="padding:20px 24px;display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                    <div>
                        <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Company</p>
                        <p style="font-size:14px;font-weight:700;color:#1e293b;margin:0;">{{ $client->company_name }}</p>
                    </div>
                    <div>
                        <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Responsible Officer</p>
                        <p style="font-size:13px;color:#374151;margin:0;">{{ $client->responsible_officer_name ?? '—' }}</p>
                    </div>
                    <div>
                        <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Address</p>
                        <p style="font-size:13px;color:#374151;white-space:pre-line;margin:0;">{{ $client->address ?? '—' }}</p>
                    </div>
                    <div>
                        <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Phone</p>
                        <p style="font-size:13px;color:#374151;margin:0;">{{ $client->company_phone ?? '—' }}</p>
                    </div>
                    <div>
                        <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Client Signed</p>
                        <p style="font-size:13px;color:#374151;margin:0;">{{ $client->service_agreement_signed_at?->format('d M Y \a\t H:i') }}</p>
                    </div>
                    <div>
                        <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Email</p>
                        <p style="font-size:13px;color:#374151;margin:0;">{{ $client->user?->email ?? '—' }}</p>
                    </div>
                </div>

                {{-- Client signature preview --}}
                @if($client->signature_data)
                    <div style="padding:0 24px 20px;">
                        <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 8px;">Client Signature</p>
                        <div style="display:inline-block;border:1px solid #e2e8f0;border-radius:3px;padding:8px;background:#f8fafc;">
                            <img src="{{ $client->signature_data }}"
                                 alt="Client signature"
                                 style="height:64px;object-fit:contain;">
                        </div>
                    </div>
                @endif
            </div>

            {{-- ── Director Countersign Form ─────────────────────── --}}
            @if($client->director_signed_at)
                {{-- ── Already Countersigned ── --}}
                <div style="background:#fff;border:1px solid #bbf7d0;border-radius:4px;overflow:hidden;margin-bottom:24px;">
                    <div style="padding:16px 24px;border-bottom:1px solid #bbf7d0;display:flex;align-items:center;justify-content:space-between;">
                        <div>
                            <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#166534;margin:0 0 4px;">&#10003; Agreement Countersigned</h3>
                            <p style="font-size:12px;color:#16a34a;margin:0;">
                                Signed by <strong>{{ trim(auth()->user()->first_name . ' ' . auth()->user()->last_name) }}</strong>
                                on {{ $client->director_signed_at->format('d M Y \a\t H:i') }}
                            </p>
                        </div>
                        <span style="display:inline-flex;align-items:center;gap:6px;padding:6px 16px;background:#16a34a;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;border-radius:3px;">
                            Fully Executed
                        </span>
                    </div>

                    {{-- Signature preview --}}
                    @if($client->director_signature_data)
                        <div style="padding:20px 24px;">
                            <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 8px;">Director Signature on File</p>
                            <div style="display:inline-block;border:1px solid #e2e8f0;border-radius:3px;background:#fff;padding:12px;">
                                <img src="{{ $client->director_signature_data }}"
                                     alt="Director Signature"
                                     style="max-height:80px;object-fit:contain;">
                            </div>
                            <p style="font-size:12px;color:#94a3b8;margin:8px 0 0;">
                                Method:
                                <span style="font-weight:600;color:#64748b;text-transform:capitalize;">
                                    {{ $client->director_signature_type === 'drawn' ? 'Hand drawn' : 'Uploaded image' }}
                                </span>
                            </p>
                        </div>
                    @endif

                    {{-- Agreement summary --}}
                    <div style="padding:0 24px 20px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                        <div style="background:#f8fafc;border-radius:3px;padding:16px;">
                            <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#64748b;margin:0 0 4px;">Client Signed</p>
                            <p style="font-size:13px;font-weight:600;color:#1e293b;margin:0;">{{ $client->service_agreement_signed_at?->format('d M Y') }}</p>
                            <p style="font-size:12px;color:#64748b;margin:2px 0 0;">{{ $client->service_agreement_signed_at?->format('H:i') }}</p>
                        </div>
                        <div style="background:#f0fdf4;border-radius:3px;padding:16px;">
                            <p style="font-size:9px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#16a34a;margin:0 0 4px;">Director Countersigned</p>
                            <p style="font-size:13px;font-weight:600;color:#166534;margin:0;">{{ $client->director_signed_at?->format('d M Y') }}</p>
                            <p style="font-size:12px;color:#16a34a;margin:2px 0 0;">{{ $client->director_signed_at?->format('H:i') }}</p>
                        </div>
                    </div>

                    <div style="padding:0 24px 20px;display:flex;gap:12px;">
                        <a href="{{ route('director.agreements.index') }}"
                           style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                            &larr; Back to Agreements
                        </a>
                        <a href="{{ route('director.agreements.download', $client->id) }}"
                           style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#0d9488;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #0d9488;border-radius:3px;text-decoration:none;">
                            Download PDF
                        </a>
                    </div>
                </div>

            @else
                {{-- ── Countersign Form ── --}}
                <div style="background:#fff;border:1px solid #e2e8f0;border-radius:4px;overflow:hidden;margin-bottom:24px;"
                 x-data="{
                     tab: 'drawn',
                     canvasEmpty: true,
                     drawing: false,
                     lastX: 0, lastY: 0,
                     initCanvas() {
                         const canvas = this.$refs.canvas;
                         const ctx    = canvas.getContext('2d');
                         ctx.strokeStyle = '#1e3a5f';
                         ctx.lineWidth   = 2.5;
                         ctx.lineCap     = 'round';
                         ctx.lineJoin    = 'round';

                         canvas.addEventListener('mousedown',  e => this.startDraw(e));
                         canvas.addEventListener('mousemove',  e => this.draw(e));
                         canvas.addEventListener('mouseup',    () => this.stopDraw());
                         canvas.addEventListener('mouseleave', () => this.stopDraw());
                         canvas.addEventListener('touchstart', e => { e.preventDefault(); this.startDraw(e.touches[0]); }, { passive: false });
                         canvas.addEventListener('touchmove',  e => { e.preventDefault(); this.draw(e.touches[0]); }, { passive: false });
                         canvas.addEventListener('touchend',   () => this.stopDraw());
                     },
                     getPos(e) {
                         const r = this.$refs.canvas.getBoundingClientRect();
                         return { x: e.clientX - r.left, y: e.clientY - r.top };
                     },
                     startDraw(e) {
                         this.drawing = true;
                         const pos = this.getPos(e);
                         this.lastX = pos.x; this.lastY = pos.y;
                     },
                     draw(e) {
                         if (!this.drawing) return;
                         const canvas = this.$refs.canvas;
                         const ctx    = canvas.getContext('2d');
                         const pos    = this.getPos(e);
                         ctx.beginPath();
                         ctx.moveTo(this.lastX, this.lastY);
                         ctx.lineTo(pos.x, pos.y);
                         ctx.stroke();
                         this.lastX = pos.x; this.lastY = pos.y;
                         this.canvasEmpty = false;
                         this.$refs.sigData.value = canvas.toDataURL('image/png');
                     },
                     stopDraw() { this.drawing = false; },
                     clearCanvas() {
                         const canvas = this.$refs.canvas;
                         canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
                         this.canvasEmpty = true;
                         this.$refs.sigData.value = '';
                     }
                 }"
                 x-init="initCanvas()">

                <div style="padding:16px 24px;border-bottom:1px solid #e2e8f0;">
                    <h3 style="font-family:'Georgia',serif;font-size:14px;font-weight:700;color:#1a2f4e;margin:0 0 4px;">Director Countersignature</h3>
                    <p style="font-size:12px;color:#94a3b8;margin:0;">
                        Signed as: <strong style="color:#475569;">{{ trim(auth()->user()->first_name . ' ' . auth()->user()->last_name) }}</strong>
                        &middot; Laboratory Director, KSTL
                    </p>
                </div>

                <form method="POST"
                      action="{{ route('director.agreements.countersign', $client->id) }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div style="padding:20px 24px;">

                        {{-- Signature type tabs --}}
                        <div style="margin-bottom:20px;">
                            <div style="display:flex;gap:8px;margin-bottom:16px;">
                                <button type="button"
                                        @click="tab = 'drawn'"
                                        :style="tab === 'drawn'
                                            ? 'background:#1a2f4e;color:#fff;'
                                            : 'background:#f1f5f9;color:#475569;'"
                                        style="padding:8px 20px;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                                    Draw Signature
                                </button>
                                <button type="button"
                                        @click="tab = 'upload'"
                                        :style="tab === 'upload'
                                            ? 'background:#1a2f4e;color:#fff;'
                                            : 'background:#f1f5f9;color:#475569;'"
                                        style="padding:8px 20px;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                                    Upload Signature
                                </button>
                            </div>

                            <input type="hidden" name="signature_type" x-bind:value="tab">
                            <input type="hidden" name="signature_data" x-ref="sigData">

                            {{-- Draw --}}
                            <div x-show="tab === 'drawn'">
                                <div style="border:2px dashed #cbd5e1;border-radius:3px;overflow:hidden;background:#f8fafc;position:relative;">
                                    <canvas x-ref="canvas"
                                            width="700" height="200"
                                            style="width:100%;cursor:crosshair;touch-action:none;display:block;">
                                    </canvas>
                                    <button type="button"
                                            @click="clearCanvas()"
                                            style="position:absolute;top:8px;right:8px;font-size:11px;color:#64748b;background:#fff;border:1px solid #e2e8f0;padding:3px 10px;border-radius:3px;cursor:pointer;">
                                        Clear
                                    </button>
                                    <p style="position:absolute;bottom:8px;left:12px;font-size:12px;color:#cbd5e1;pointer-events:none;margin:0;"
                                       x-show="canvasEmpty">
                                        Sign here...
                                    </p>
                                </div>
                                <p style="font-size:12px;color:#94a3b8;margin:6px 0 0;">Draw your signature above using mouse or touch.</p>
                            </div>

                            {{-- Upload --}}
                            <div x-show="tab === 'upload'" x-cloak>
                                <input type="file"
                                       name="signature_upload"
                                       accept="image/png,image/jpeg"
                                       style="display:block;width:100%;font-size:13px;color:#475569;">
                                <p style="font-size:12px;color:#94a3b8;margin:6px 0 0;">Upload a PNG or JPG of your signature. Max 2MB.</p>
                            </div>
                        </div>

                        {{-- Confirmation --}}
                        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:3px;padding:16px;">
                            <p style="font-size:13px;color:#1e40af;line-height:1.6;margin:0;">
                                By countersigning this agreement, I confirm that I have reviewed the service
                                agreement with <strong>{{ $client->company_name }}</strong> and authorise
                                the laboratory to provide testing services under the terms of this agreement.
                            </p>
                        </div>

                    </div>

                    <div style="padding:16px 24px;border-top:1px solid #e2e8f0;background:#f8fafc;display:flex;align-items:center;justify-content:space-between;">
                        <a href="{{ route('director.agreements.index') }}"
                           style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#1a2f4e;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #1a2f4e;border-radius:3px;text-decoration:none;">
                            Cancel
                        </a>
                        <div style="display:flex;gap:10px;">
                            <a href="{{ route('director.agreements.download', $client->id) }}"
                               style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#fff;color:#0d9488;font-size:12px;font-weight:700;letter-spacing:.06em;border:1px solid #0d9488;border-radius:3px;text-decoration:none;">
                                Download PDF
                            </a>
                            <button type="submit"
                                    onclick="return confirm('Countersign this service agreement? This action cannot be undone.')"
                                    style="display:inline-flex;align-items:center;gap:8px;padding:8px 20px;background:#0d9488;color:#fff;font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border-radius:3px;border:none;cursor:pointer;">
                                Countersign Agreement
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
