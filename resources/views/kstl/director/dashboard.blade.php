{{-- resources/views/kstl/director/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Director Dashboard</h2>
                <p class="text-sm text-gray-600 mt-1">Authorise results and monitor laboratory operations</p>
            </div>
        </div>
    </x-slot>

    {{-- Audit Search Bar --}}
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg border-2 border-blue-400 p-6 mb-6" x-data="{ reference: '' }">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">Audit Search</h3>
                    <p class="text-blue-100 text-sm">Search submissions by reference number for audit sessions</p>
                </div>
            </div>
        </div>
        
        <div class="space-y-4">
            <div class="flex gap-3">
                <div class="flex-1">
                    <input type="text" 
                           x-model="reference"
                           placeholder="Enter reference number (e.g., KSTL-2026-00001)" 
                           class="w-full px-4 py-3 rounded-lg border-2 border-white bg-white bg-opacity-90 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white focus:border-white transition-all font-mono"
                           @keydown.enter="searchByReference()"
                           pattern="KSTL-\d{4}-\d{5}"
                           title="Format: KSTL-YYYY-NNNNN">
                </div>
                <button type="button" 
                        @click="searchByReference()"
                        class="px-6 py-3 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition-all shadow-md hover:shadow-lg font-semibold flex items-center gap-2 hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Search
                </button>
            </div>
            
            {{-- Quick Access Buttons --}}
            <div class="flex flex-wrap gap-2 items-center">
                <span class="text-white text-sm font-medium">Quick access:</span>
                <a href="{{ route('director.dashboard') }}" 
                   class="px-3 py-1 bg-white bg-opacity-20 text-white rounded-full text-sm hover:bg-opacity-30 transition-all">
                    🏠 Dashboard
                </a>
                <a href="{{ route('director.agreements.index') }}" 
                   class="px-3 py-1 bg-white bg-opacity-20 text-white rounded-full text-sm hover:bg-opacity-30 transition-all">
                    📄 Agreements
                </a>
                <a href="{{ route('director.invoices.index') }}" 
                   class="px-3 py-1 bg-white bg-opacity-20 text-white rounded-full text-sm hover:bg-opacity-30 transition-all">
                    💰 Invoices
                </a>
                <a href="{{ route('director.audit.index') }}" 
                   class="px-3 py-1 bg-white bg-opacity-20 text-white rounded-full text-sm hover:bg-opacity-30 transition-all">
                    📋 Audit Log
                </a>
                <a href="{{ route('director.complaints.index') }}"
                   class="px-3 py-1 bg-white bg-opacity-20 text-white rounded-full text-sm hover:bg-opacity-30 transition-all">
                    📧 Complaints
                </a>
                <a href="{{ route('director.submissions.index') }}"
                   class="px-3 py-1 bg-white bg-opacity-20 text-white rounded-full text-sm hover:bg-opacity-30 transition-all">
                    🔬 Pipeline
                </a>
                @if($flagged > 0)
                <a href="{{ route('director.flagged.index') }}" 
                        class="px-3 py-1 bg-red-500 bg-opacity-80 text-white rounded-full text-sm hover:bg-opacity-100 transition-all">
                    🚩 View Flagged ({{ $flagged }})
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        {{-- Awaiting Authorisation (clickable) --}}
        <a href="{{ route('director.submissions.index', ['status' => 'awaiting_authorisation']) }}"
           class="block bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all border-2 border-amber-300">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-3xl font-bold mb-1">{{ $pending->count() }}</h3>
            <p class="text-amber-100 text-sm font-medium">Awaiting Authorisation</p>
            @if($pending->isNotEmpty())
                <p class="text-xs text-amber-100 mt-2">
                    Oldest: {{ $pending->sortBy('created_at')->first()->created_at->diffForHumans() }}
                </p>
            @else
                <p class="text-xs text-amber-100 mt-2">All caught up!</p>
            @endif
        </a>

        {{-- Flagged Tests (clickable) --}}
        <a href="{{ route('director.flagged.index') }}"
           class="block bg-gradient-to-br from-red-500 to-pink-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all border-2 border-red-400">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-3xl font-bold mb-1">{{ $flagged }}</h3>
            <p class="text-red-100 text-sm font-medium">Flagged Tests</p>
            @if($flagged > 0)
                <p class="text-xs text-red-100 mt-2">Needs immediate review</p>
            @else
                <p class="text-xs text-red-100 mt-2">No issues flagged</p>
            @endif
        </a>

        {{-- Pending Payments (clickable) --}}
        <a href="{{ route('director.invoices.index') }}"
           class="block bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all border-2 border-purple-400">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-3xl font-bold mb-1">{{ $unpaid_invoices }}</h3>
            <p class="text-purple-100 text-sm font-medium">Pending Payments</p>
            @if($unpaid_invoices > 0)
                <p class="text-xs text-purple-100 mt-2">Awaiting payment confirmation</p>
            @else
                <p class="text-xs text-purple-100 mt-2">All invoices settled</p>
            @endif
        </a>

        {{-- Authorised Today --}}
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all border-2 border-green-400">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <h3 class="text-3xl font-bold mb-1">{{ $authorised_today }}</h3>
            <p class="text-green-100 text-sm font-medium">Authorised Today</p>
            <p class="text-xs text-green-100 mt-2">{{ now()->format('l, F j, Y') }}</p>
        </div>

        {{-- This Week Performance --}}
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all border-2 border-blue-400">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white bg-opacity-20 rounded-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
            </div>
            @php
                $thisWeekCount = $history->where('created_at', '>=', now()->startOfWeek())->count();
                $dailyAverage = $thisWeekCount > 0 ? round($thisWeekCount / max(1, now()->dayOfWeek ?: 1), 1) : 0;
            @endphp
            <h3 class="text-3xl font-bold mb-1">{{ $thisWeekCount }}</h3>
            <p class="text-blue-100 text-sm font-medium">This Week</p>
            <p class="text-xs text-blue-100 mt-2">Avg {{ $dailyAverage }}/day</p>
        </div>
    </div>

    {{-- Quick Actions Bar --}}
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
            </div>
            <div class="flex gap-2">
                @if($pending->isNotEmpty())
                    <a href="{{ route('director.submissions.show', $pending->first()->id) }}" 
                       class="px-4 py-2 bg-gradient-to-r from-teal-500 to-teal-600 text-white rounded-lg hover:from-teal-600 hover:to-teal-700 transition-all shadow-md hover:shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Authorise Next
                    </a>
                @endif
                <a href="{{ route('director.agreements.index') }}" 
                   class="px-4 py-2 bg-white text-gray-700 border-2 border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Agreements
                </a>
                @if($flagged > 0)
                    <a href="{{ route('director.flagged.index') }}" 
                            class="px-4 py-2 bg-red-50 text-red-700 border-2 border-red-200 rounded-lg hover:bg-red-100 hover:border-red-300 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                        </svg>
                        View Flagged ({{ $flagged }})
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if(isset($unsigned_agreements) && $unsigned_agreements > 0)
    {{-- Service Agreements Alert --}}
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-2 border-amber-400 rounded-xl shadow-md p-6 mb-6">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <div class="p-4 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-gray-900 mb-2">Service Agreements Awaiting Signature</h3>
                <p class="text-gray-700 mb-4">
                    You have <span class="font-bold text-amber-600">{{ $unsigned_agreements }}</span> service agreement(s) awaiting your countersignature
                </p>
                <a href="{{ route('director.agreements.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-lg hover:from-amber-600 hover:to-orange-700 transition-all shadow-md hover:shadow-lg hover:scale-105">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                    </svg>
                    Review & Sign
                </a>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Pending Authorisations --}}
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <div class="p-2 bg-amber-100 rounded-lg">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    Awaiting Your Authorisation
                </h3>
            </div>

            @if($pending->isEmpty())
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-500 font-medium">No pending authorisations</p>
                    <p class="text-gray-400 text-sm">All submissions have been reviewed</p>
                </div>
            @else
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach($pending as $submission)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-all">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-mono text-sm font-semibold text-indigo-600">{{ $submission->reference_number }}</span>
                                        @php
                                            $flaggedCount = $submission->samples->sum(function($s) {
                                                return $s->sampleTests ? $s->sampleTests->where('status', 'flagged')->count() : 0;
                                            });
                                        @endphp
                                        @if($flaggedCount > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                                                </svg>
                                                {{ $flaggedCount }} Flagged
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                All Clear
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-gray-700 text-sm font-medium">{{ $submission->client->user->name }}</p>
                                    <p class="text-gray-500 text-xs">{{ $submission->client->company_name }}</p>
                                </div>
                                <div class="text-right text-xs text-gray-500">
                                    <p>{{ $submission->created_at->diffForHumans() }}</p>
                                    @php
                                        $totalTests = $submission->samples->sum(function($s) {
                                            return $s->sampleTests ? $s->sampleTests->count() : 0;
                                        });
                                        $completedTests = $submission->samples->sum(function($s) {
                                            return $s->sampleTests ? $s->sampleTests->whereIn('status', ['completed', 'flagged'])->count() : 0;
                                        });
                                        $progress = $totalTests > 0 ? round(($completedTests / $totalTests) * 100) : 0;
                                    @endphp
                                    <div class="mt-1 w-24">
                                        <div class="flex items-center gap-1">
                                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                            </div>
                                            <span class="text-xs font-medium">{{ $completedTests }}/{{ $totalTests }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <a href="{{ route('director.submissions.show', $submission->id) }}" 
                                   class="flex-1 px-3 py-2 bg-gradient-to-r from-teal-500 to-teal-600 text-white rounded-lg hover:from-teal-600 hover:to-teal-700 transition-all text-sm font-medium flex items-center justify-center gap-1 shadow-md">
                                    Review & Authorise
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Authorisation History --}}
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6" id="flagged-section">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                Authorisation History
            </h3>

            @if($history->isEmpty())
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-gray-500 font-medium">No history yet</p>
                    <p class="text-gray-400 text-sm">Authorised submissions will appear here</p>
                </div>
            @else
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach($history->take(20) as $submission)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-all">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-sm font-semibold text-indigo-600">{{ $submission->reference_number }}</span>
                                    @if($submission->result)
                                        @if($submission->result->overall_outcome === 'pass')
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                Pass
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                </svg>
                                                Fail
                                            </span>
                                        @endif
                                    @endif
                                </div>
                                <span class="text-xs text-gray-500">{{ $submission->updated_at->format('M j, Y • g:i A') }}</span>
                            </div>
                            <p class="text-gray-700 text-sm font-medium">{{ $submission->client->user->name }}</p>
                            <p class="text-gray-500 text-xs">
                                {{ $submission->client->company_name }} • 
                                {{ $submission->samples->first()->sample_type ?? 'N/A' }}
                            </p>
                            <div class="mt-2">
                                <a href="{{ route('director.submissions.show', $submission->id) }}" 
                                   class="text-indigo-600 hover:text-indigo-800 text-xs font-medium inline-flex items-center gap-1">
                                    View Details
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Search Results Area (populated when search is performed) --}}
    <div id="search-results" class="mt-6 hidden">
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Search Results</h3>
            <div id="search-results-content"></div>
        </div>
    </div>

    <script>
        function searchByReference() {
            const reference = document.querySelector('[x-model="reference"]').value.trim();
            
            if (!reference) {
                alert('Please enter a reference number');
                return;
            }
            
            // Validate format
            const pattern = /^KSTL-\d{4}-\d{5}$/;
            if (!pattern.test(reference)) {
                alert('Invalid reference format. Use: KSTL-YYYY-NNNNN');
                return;
            }
            
            // Search in pending submissions
            const pendingSubmissions = @json($pending);
            const foundPending = pendingSubmissions.find(s => s.reference === reference);
            
            if (foundPending) {
                // Found in pending - redirect to submission
                window.location.href = `{{ url('director/submissions') }}/${foundPending.id}`;
                return;
            }
            
            // Search in history
            const historySubmissions = @json($history);
            const foundHistory = historySubmissions.find(s => s.reference === reference);
            
            if (foundHistory) {
                // Found in history - redirect to submission
                window.location.href = `{{ url('director/submissions') }}/${foundHistory.id}`;
                return;
            }
            
            // Not found
            alert(`Submission ${reference} not found in your accessible records. The submission may not exist or may still be in earlier processing stages (reception/testing).`);
        }
    </script>
</x-app-layout>