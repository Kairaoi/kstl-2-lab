<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="dr-eyebrow">Lab Staff Only</p>
            <h2 class="dr-title text-xl font-bold leading-tight mt-0.5">Documents Repository</h2>
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

    <div class="py-8" x-data="{ showAdd: false }">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

            {{-- Staff-only banner --}}
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-6 py-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-emerald-800 text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-emerald-900">Controlled Document Repository</p>
                        <p class="text-xs text-emerald-700">Accessible to laboratory staff only. SOPs, manuals, records and templates with version history.</p>
                    </div>
                </div>
                @if($canManage)
                    <button @click="showAdd = !showAdd"
                            class="shrink-0 inline-flex items-center gap-2 px-4 py-2 bg-emerald-700 text-white text-sm font-medium rounded-lg hover:bg-emerald-800 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Document
                    </button>
                @endif
            </div>

            {{-- Add document form (managers only) --}}
            @if($canManage)
                <div x-show="showAdd" x-cloak class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="dr-section-title">Add a Document</h3>
                        <p class="text-xs text-gray-400 mt-1">Uploads the first version. New versions can be added later from the document page.</p>
                    </div>
                    <form method="POST" action="{{ route('staff.documents.store') }}" enctype="multipart/form-data" class="px-6 py-5 space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Title *</label>
                                <input type="text" name="title" required maxlength="255"
                                       value="{{ old('title') }}"
                                       placeholder="e.g. Aerobic Plate Count SOP"
                                       class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                <x-input-error for="title" class="mt-1"/>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Category *</label>
                                <select name="category" required
                                        class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    @foreach($categories as $value => $label)
                                        <option value="{{ $value }}" @selected(old('category') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <x-input-error for="category" class="mt-1"/>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Subcategory</label>
                                <input type="text" name="subcategory" maxlength="255"
                                       value="{{ old('subcategory') }}"
                                       placeholder="e.g. Microbiology"
                                       class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Reference Code</label>
                                <input type="text" name="reference_code" maxlength="60"
                                       value="{{ old('reference_code') }}"
                                       placeholder="e.g. SOP-MICRO-001"
                                       class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                            <textarea name="description" rows="2" maxlength="2000"
                                      class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('description') }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">File *</label>
                                <input type="file" name="file" required
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.csv,.txt"
                                       class="block w-full text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                                <p class="text-xs text-gray-400 mt-1">PDF, Word, Excel, CSV or text. Max 20&nbsp;MB.</p>
                                <x-input-error for="file" class="mt-1"/>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Change note</label>
                                <input type="text" name="change_note" maxlength="500"
                                       placeholder="e.g. Initial issue"
                                       class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="showAdd = false"
                                    class="px-4 py-2 text-sm font-medium text-gray-500 border border-gray-200 rounded-lg hover:bg-gray-50 transition">Cancel</button>
                            <button type="submit"
                                    class="px-5 py-2 bg-emerald-700 text-white text-sm font-medium rounded-lg hover:bg-emerald-800 transition">Save Document</button>
                        </div>
                    </form>
                </div>
            @endif

            {{-- Category filter chips --}}
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('staff.documents.index') }}"
                   class="px-3 py-1.5 text-xs font-medium rounded-full border transition {{ ! $active ? 'bg-emerald-700 text-white border-emerald-700' : 'text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                    All
                </a>
                @foreach($categories as $value => $label)
                    <a href="{{ route('staff.documents.index', ['category' => $value]) }}"
                       class="px-3 py-1.5 text-xs font-medium rounded-full border transition {{ $active === $value ? 'bg-emerald-700 text-white border-emerald-700' : 'text-gray-600 border-gray-200 hover:bg-gray-50' }}">
                        {{ $label }}
                        <span class="ml-1 opacity-70">{{ $counts[$value] ?? 0 }}</span>
                    </a>
                @endforeach
            </div>

            {{-- Documents by category --}}
            @forelse($categories as $value => $label)
                @php $docs = $documents[$value] ?? collect(); @endphp
                @if(! $active || $active === $value)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-3.5 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="dr-section-title">{{ $label }}</h3>
                            <span class="text-xs text-gray-400">{{ $docs->count() }} document{{ $docs->count() === 1 ? '' : 's' }}</span>
                        </div>

                        @if($docs->isEmpty())
                            <div class="px-6 py-8 text-center">
                                <p class="text-sm text-gray-400">No documents in this category yet.</p>
                            </div>
                        @else
                            <ul class="divide-y divide-gray-50">
                                @foreach($docs as $doc)
                                    <li class="px-6 py-3.5 flex items-center justify-between gap-4">
                                        <div class="min-w-0">
                                            <a href="{{ route('staff.documents.show', $doc->id) }}"
                                               class="text-sm font-medium text-gray-800 hover:text-emerald-700 hover:underline">
                                                {{ $doc->title }}
                                            </a>
                                            <div class="flex flex-wrap items-center gap-x-3 gap-y-0.5 mt-0.5 text-xs text-gray-400">
                                                @if($doc->reference_code)
                                                    <span class="font-mono">{{ $doc->reference_code }}</span>
                                                @endif
                                                @if($doc->subcategory)
                                                    <span>{{ $doc->subcategory }}</span>
                                                @endif
                                                @if($doc->currentVersion)
                                                    <span>v{{ $doc->currentVersion->version_number }} · {{ $doc->currentVersion->human_size }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="shrink-0 flex items-center gap-3">
                                            @if($doc->currentVersion)
                                                <a href="{{ route('staff.documents.download', $doc->currentVersion->id) }}"
                                                   class="inline-flex items-center gap-1 text-xs font-medium text-emerald-700 hover:text-emerald-900 hover:underline">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                    Download
                                                </a>
                                            @endif
                                            <a href="{{ route('staff.documents.show', $doc->id) }}"
                                               class="text-xs text-gray-400 hover:text-gray-600">History →</a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif
            @empty
            @endforelse

        </div>
    </div>
</x-app-layout>