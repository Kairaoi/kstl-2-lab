{{--
    resources/views/kstl/analyst/tests/_attachments.blade.php

    Supporting files attached to a test. Included from tests/show.blade.php:
        @include('kstl.analyst.tests._attachments', ['test' => $test])

    Eager-load to avoid N+1 (in AnalystController::show):
        $test = $this->testRepo->getById($id);   // ensure ->load('attachments.uploadedBy')

    Matches AnalystController:
        upload  → analyst.tests.attachments.store    (POST /tests/{id}/attachments, field: attachment)
        download→ analyst.tests.attachments.download (GET  /attachments/{attachment}/download)
        delete  → analyst.tests.attachments.destroy  (DELETE /attachments/{attachment})
--}}

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mt-5">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <div>
            <h3 class="at-section-title">Supporting Files</h3>
            <p class="text-xs text-gray-400 mt-1">
                Attach raw data, instrument output, photos, or method records for this test.
            </p>
        </div>
        <span class="text-xs text-gray-400 bg-gray-50 px-2.5 py-1 rounded-full border border-gray-100">
            {{ $test->attachments->count() }} file{{ $test->attachments->count() === 1 ? '' : 's' }}
        </span>
    </div>

    {{-- Existing attachments --}}
    @if($test->attachments->isNotEmpty())
        <ul class="divide-y divide-gray-50">
            @foreach($test->attachments as $attachment)
                <li class="px-6 py-3 flex items-center justify-between gap-4">
                    <div class="flex items-start gap-3 min-w-0">
                        <svg class="w-4 h-4 text-gray-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <div class="min-w-0">
                            <a href="{{ route('analyst.tests.attachments.download', $attachment->id) }}"
                               class="text-sm font-medium text-gray-800 hover:text-indigo-600 hover:underline truncate block">
                                {{ $attachment->original_filename }}
                            </a>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $attachment->human_size }}
                                @if($attachment->uploadedBy)
                                    &middot; {{ $attachment->uploadedBy->name ?? trim(($attachment->uploadedBy->first_name ?? '') . ' ' . ($attachment->uploadedBy->last_name ?? '')) }}
                                @endif
                                &middot; {{ $attachment->created_at->format('d M Y H:i') }}
                            </p>
                            @if($attachment->description)
                                <p class="text-xs text-gray-500 mt-0.5">{{ $attachment->description }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <a href="{{ route('analyst.tests.attachments.download', $attachment->id) }}"
                           class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:text-indigo-800 font-medium hover:underline transition"
                           title="Download">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download
                        </a>
                        @unless($locked ?? false)
                        <form method="POST"
                              action="{{ route('analyst.tests.attachments.destroy', $attachment->id) }}"
                              onsubmit="return confirm('Remove this attachment? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-1 text-xs text-red-500 hover:text-red-700 transition"
                                    title="Remove">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Remove
                            </button>
                        </form>
                        @endunless
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="px-6 py-8 text-center">
            <svg class="w-8 h-8 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            <p class="text-sm text-gray-400">No files attached yet.</p>
        </div>
    @endif

    {{-- Upload form (single file, matches controller's 'attachment' field) --}}
    @if($locked ?? false)
        <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 text-center">
            <p class="text-xs text-gray-400">This result is locked. Supporting files can be viewed and downloaded, but not added or removed.</p>
        </div>
    @else
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        <form method="POST"
              action="{{ route('analyst.tests.attachments.store', $test->id) }}"
              enctype="multipart/form-data"
              class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Add a file</label>
                <input type="file"
                       name="attachment"
                       required
                       accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.csv,.txt"
                       class="block w-full text-sm text-gray-600
                              file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                              file:text-xs file:font-medium file:bg-indigo-50 file:text-indigo-700
                              hover:file:bg-indigo-100 cursor-pointer"/>
                <p class="text-xs text-gray-400 mt-1">PDF, image, Office doc, CSV or text. Max 20&nbsp;MB.</p>
                <x-input-error for="attachment" class="mt-1"/>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Description (optional)</label>
                <input type="text"
                       name="description"
                       maxlength="255"
                       placeholder="e.g. HPLC chromatogram, raw instrument export..."
                       class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"/>
                <x-input-error for="description" class="mt-1"/>
            </div>
            <div class="flex justify-end">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Upload
                </button>
            </div>
        </form>
    </div>
    @endif
</div>