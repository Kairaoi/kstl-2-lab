<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('staff.documents.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <p class="dr-eyebrow">Lab Staff Only · {{ $document->category_label }}</p>
                <h2 class="dr-title text-xl font-bold leading-tight mt-0.5">{{ $document->title }}</h2>
            </div>
        </div>
    </x-slot>

    @push('styles')
    <style>
        .dr-eyebrow { letter-spacing: .16em; text-transform: uppercase; font-size: 10px; color: var(--gold); font-weight: 700; }
        .dr-title { font-family: 'Noto Serif', serif; color: var(--navy); letter-spacing: .01em; }
        .dr-section-title { font-family: 'Noto Serif', serif; color: var(--navy); font-size: 14px; font-weight: 700; }
        .dr-meta-label { letter-spacing: .07em; text-transform: uppercase; font-size: 10px; color: var(--subtle); font-weight: 600; }
        [x-cloak] { display: none !important; }
    </style>
    @endpush

    <div class="py-8" x-data="{ showUpload: false }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg">
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('info'))
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                    <p class="text-sm text-blue-800">{{ session('info') }}</p>
                </div>
            @endif

            {{-- Details --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <h3 class="dr-section-title">Document Details</h3>
                    @if($canManage)
                        <div class="flex items-center gap-2">
                            <button @click="showUpload = !showUpload"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-emerald-700 border border-emerald-200 rounded-lg hover:bg-emerald-50 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Upload New Version
                            </button>
                            <form method="POST" action="{{ route('staff.documents.destroy', $document->id) }}"
                                  onsubmit="return confirm('Delete this document and all its versions? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-500 border border-red-200 rounded-lg hover:bg-red-50 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
                <dl class="px-6 py-5 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <dt class="dr-meta-label">Category</dt>
                        <dd class="mt-1 text-sm text-gray-800">{{ $document->category_label }}{{ $document->subcategory ? ' · ' . $document->subcategory : '' }}</dd>
                    </div>
                    <div>
                        <dt class="dr-meta-label">Reference Code</dt>
                        <dd class="mt-1 text-sm text-gray-800 font-mono">{{ $document->reference_code ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="dr-meta-label">Current Version</dt>
                        <dd class="mt-1 text-sm text-gray-800">
                            @if($document->currentVersion)
                                v{{ $document->currentVersion->version_number }}
                                <a href="{{ route('staff.documents.download', $document->currentVersion->id) }}"
                                   class="ml-2 text-emerald-700 hover:underline">Download current</a>
                            @else — @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="dr-meta-label">Added By</dt>
                        <dd class="mt-1 text-sm text-gray-800">{{ $document->createdBy?->name ?? trim(($document->createdBy->first_name ?? '') . ' ' . ($document->createdBy->last_name ?? '')) ?: '—' }}</dd>
                    </div>
                    @if($document->description)
                        <div class="sm:col-span-2">
                            <dt class="dr-meta-label">Description</dt>
                            <dd class="mt-1 text-sm text-gray-700 whitespace-pre-line">{{ $document->description }}</dd>
                        </div>
                    @endif
                </dl>

                {{-- Upload new version (managers) --}}
                @if($canManage)
                    <div x-show="showUpload" x-cloak class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        <form method="POST" action="{{ route('staff.documents.versions.store', $document->id) }}" enctype="multipart/form-data" class="space-y-3">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">New file *</label>
                                    <input type="file" name="file" required
                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.txt"
                                           class="block w-full text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                                    <x-input-error for="file" class="mt-1"/>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">What changed?</label>
                                    <input type="text" name="change_note" maxlength="500"
                                           placeholder="e.g. Updated acceptance limits in §4"
                                           class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit"
                                        class="px-4 py-2 bg-emerald-700 text-white text-xs font-medium rounded-lg hover:bg-emerald-800 transition">
                                    Upload as v{{ $document->next_version_number }}
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Version history --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-3.5 border-b border-gray-100">
                    <h3 class="dr-section-title">Version History</h3>
                    <p class="text-xs text-gray-400 mt-0.5">All prior versions are retained for audit. The current version is highlighted.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Version</th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">File</th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Change Note</th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Uploaded</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($document->versions as $version)
                                <tr class="{{ $document->current_version_id === $version->id ? 'bg-emerald-50/40' : '' }}">
                                    <td class="px-6 py-3">
                                        <span class="font-medium text-gray-800">v{{ $version->version_number }}</span>
                                        @if($document->current_version_id === $version->id)
                                            <span class="ml-1 inline-flex px-1.5 py-0.5 text-xs bg-emerald-100 text-emerald-700 rounded-full">current</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3">
                                        <p class="text-gray-700">{{ $version->original_filename }}</p>
                                        <p class="text-xs text-gray-400">{{ $version->human_size }}</p>
                                    </td>
                                    <td class="px-6 py-3 text-gray-600">{{ $version->change_note ?: '—' }}</td>
                                    <td class="px-6 py-3 text-gray-500">
                                        <p>{{ $version->created_at->format('d M Y H:i') }}</p>
                                        @if($version->uploadedBy)
                                            <p class="text-xs text-gray-400">{{ $version->uploadedBy->name ?? trim(($version->uploadedBy->first_name ?? '') . ' ' . ($version->uploadedBy->last_name ?? '')) }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 text-right">
                                        <a href="{{ route('staff.documents.download', $version->id) }}"
                                           class="inline-flex items-center gap-1 text-xs font-medium text-emerald-700 hover:text-emerald-900 hover:underline">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Download
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>