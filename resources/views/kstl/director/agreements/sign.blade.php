{{-- resources/views/kstl/director/agreements/sign.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('director.agreements.index') }}"
               class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Countersign Agreement — {{ $client->company_name }}
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    Client signed {{ $client->service_agreement_signed_at?->format('d M Y \a\t H:i') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg text-sm text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            {{-- ── Agreement Details ────────────────────────────── --}}
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-medium text-gray-800">Agreement Details</h3>
                </div>
                <div class="px-6 py-5 grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Company</p>
                        <p class="font-semibold text-gray-900">{{ $client->company_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Responsible Officer</p>
                        <p class="text-gray-700">{{ $client->responsible_officer_name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Address</p>
                        <p class="text-gray-700 whitespace-pre-line">{{ $client->address ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Phone</p>
                        <p class="text-gray-700">{{ $client->company_phone ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Client Signed</p>
                        <p class="text-gray-700">{{ $client->service_agreement_signed_at?->format('d M Y \a\t H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Email</p>
                        <p class="text-gray-700">{{ $client->user?->email ?? '—' }}</p>
                    </div>
                </div>

                {{-- Client signature preview --}}
                @if($client->signature_data)
                    <div class="px-6 pb-5">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Client Signature</p>
                        <div class="inline-block border border-gray-200 rounded-lg p-2 bg-gray-50">
                            <img src="{{ $client->signature_data }}"
                                 alt="Client signature"
                                 class="h-16 object-contain">
                        </div>
                    </div>
                @endif
            </div>

            {{-- ── Director Countersign Form ─────────────────────── --}}
            @if($client->director_signed_at)
                {{-- ── Already Countersigned ── --}}
                <div class="bg-white rounded-xl border border-green-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-green-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-green-800">✓ Agreement Countersigned</h3>
                            <p class="text-xs text-green-600 mt-0.5">
                                Signed by <strong>{{ trim(auth()->user()->first_name . ' ' . auth()->user()->last_name) }}</strong>
                                on {{ $client->director_signed_at->format('d M Y \a\t H:i') }}
                            </p>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-600 text-white text-xs font-semibold rounded-full">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Fully Executed
                        </span>
                    </div>

                    {{-- Signature preview --}}
                    @if($client->director_signature_data)
                        <div class="px-6 py-5">
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Director Signature on File</p>
                            <div class="inline-block border border-gray-200 rounded-xl bg-white p-3">
                                <img src="{{ $client->director_signature_data }}"
                                     alt="Director Signature"
                                     class="max-h-20 object-contain">
                            </div>
                            <p class="text-xs text-gray-400 mt-2">
                                Method:
                                <span class="capitalize font-medium text-gray-600">
                                    {{ $client->director_signature_type === 'drawn' ? '✏ Hand drawn' : '↑ Uploaded image' }}
                                </span>
                            </p>
                        </div>
                    @endif

                    {{-- Agreement summary --}}
                    <div class="px-6 pb-5 grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Client Signed</p>
                            <p class="text-sm font-medium text-gray-800">{{ $client->service_agreement_signed_at?->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $client->service_agreement_signed_at?->format('H:i') }}</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <p class="text-xs text-green-600 uppercase tracking-wide mb-1">Director Countersigned</p>
                            <p class="text-sm font-medium text-green-800">{{ $client->director_signed_at?->format('d M Y') }}</p>
                            <p class="text-xs text-green-600">{{ $client->director_signed_at?->format('H:i') }}</p>
                        </div>
                    </div>

                    <div class="px-6 pb-5 flex gap-3">
                        <a href="{{ route('director.agreements.index') }}"
                           class="px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            ← Back to Agreements
                        </a>
                        <a href="{{ route('director.agreements.download', $client->id) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-teal-700 border border-teal-200 rounded-lg hover:bg-teal-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download PDF
                        </a>
                    </div>
                </div>

            @else
                {{-- ── Countersign Form ── --}}
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden"
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

                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-medium text-gray-800">Director Countersignature</h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Signed as: <strong>{{ trim(auth()->user()->first_name . ' ' . auth()->user()->last_name) }}</strong>
                        · Laboratory Director, KSTL
                    </p>
                </div>

                <form method="POST"
                      action="{{ route('director.agreements.countersign', $client->id) }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="px-6 py-5 space-y-5">

                        {{-- Signature type tabs --}}
                        <div>
                            <div class="flex gap-2 mb-4">
                                <button type="button"
                                        @click="tab = 'drawn'"
                                        :class="tab === 'drawn'
                                            ? 'bg-teal-600 text-white'
                                            : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                        class="px-4 py-2 text-sm font-medium rounded-lg transition">
                                    Draw Signature
                                </button>
                                <button type="button"
                                        @click="tab = 'upload'"
                                        :class="tab === 'upload'
                                            ? 'bg-teal-600 text-white'
                                            : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                        class="px-4 py-2 text-sm font-medium rounded-lg transition">
                                    Upload Signature
                                </button>
                            </div>

                            <input type="hidden" name="signature_type" x-bind:value="tab">
                            <input type="hidden" name="signature_data" x-ref="sigData">

                            {{-- Draw --}}
                            <div x-show="tab === 'drawn'">

                                <div class="border-2 border-dashed border-gray-300 rounded-xl overflow-hidden bg-gray-50 relative">
                                    <canvas x-ref="canvas"
                                            width="700" height="200"
                                            class="w-full cursor-crosshair touch-none block">
                                    </canvas>
                                    <button type="button"
                                            @click="clearCanvas()"
                                            class="absolute top-2 right-2 text-xs text-gray-400 hover:text-gray-600 bg-white border border-gray-200 px-2 py-1 rounded-lg transition">
                                        Clear
                                    </button>
                                    <p class="absolute bottom-2 left-3 text-xs text-gray-300 pointer-events-none"
                                       x-show="canvasEmpty">
                                        Sign here...
                                    </p>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Draw your signature above using mouse or touch.</p>
                            </div>

                            {{-- Upload --}}
                            <div x-show="tab === 'upload'" x-cloak>
                                <input type="file"
                                       name="signature_upload"
                                       accept="image/png,image/jpeg"
                                       class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                                <p class="text-xs text-gray-400 mt-1">Upload a PNG or JPG of your signature. Max 2MB.</p>
                            </div>
                        </div>

                        {{-- Confirmation --}}
                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
                            <p class="text-sm text-blue-800 leading-relaxed">
                                By countersigning this agreement, I confirm that I have reviewed the service
                                agreement with <strong>{{ $client->company_name }}</strong> and authorise
                                the laboratory to provide testing services under the terms of this agreement.
                            </p>
                        </div>

                    </div>

                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                        <a href="{{ route('director.agreements.index') }}">
                            <x-secondary-button type="button">Cancel</x-secondary-button>
                        </a>
                        <button type="submit"
                                onclick="return confirm('Countersign this service agreement? This action cannot be undone.')"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                            Countersign Agreement
                        </button>
                        <a href="{{ route('director.agreements.download', $client->id) }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-medium text-teal-700 border border-teal-200 rounded-lg hover:bg-teal-50 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download PDF
                        </a>
                    </div>
                </form>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>