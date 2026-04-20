{{-- resources/views/kstl/client/dashboard.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Welcome back, {{ $user->first_name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash: Success (e.g. after signing agreement) --}}
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Flash: Info --}}
            @if(session('info'))
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5 text-blue-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-blue-800">{{ session('info') }}</p>
                </div>
            @endif

            {{-- Flash: Warning --}}
            @if(session('warning'))
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5 text-yellow-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-yellow-800">{{ session('warning') }}</p>
                </div>
            @endif

            {{-- ── Onboarding Steps ──────────────────────────────────────────── --}}

            {{-- Step 1 — No client profile yet --}}
            @if(! $client)
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center shrink-0 font-bold text-blue-600 text-sm">
                                1
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-blue-800">Step 1 of 2 — Complete Your Company Profile</p>
                                <p class="text-sm text-blue-700 mt-0.5">
                                    Provide your company details before signing the service agreement.
                                    This information will appear on your test reports and invoices.
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('client.profile.company.show') }}"
                           class="shrink-0 inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Complete Profile →
                        </a>
                    </div>
                </div>

            {{-- Step 2 — Profile done but agreement not signed --}}
            @elseif($client && ! $client->service_agreement_signed_at)
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center shrink-0 font-bold text-yellow-700 text-sm">
                                2
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-yellow-800">Step 2 of 2 — Sign the Service Agreement</p>
                                <p class="text-sm text-yellow-700 mt-0.5">
                                    Your company details are saved. Please read and sign the agreement to unlock full access.
                                </p>
                                <p class="text-xs text-yellow-600 mt-1">
                                    Signed as: <strong>{{ $client->company_name }}</strong>
                                    @if($client->responsible_officer_name)
                                        — {{ $client->responsible_officer_name }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-col sm:items-end gap-2 shrink-0">
                            <a href="{{ route('client.agreement.show') }}"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-400 text-yellow-900 text-sm font-semibold rounded-lg hover:bg-yellow-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2a2 2 0 01.586-1.414z"/>
                                </svg>
                                Read &amp; Sign Agreement →
                            </a>
                            <a href="{{ route('client.profile.company.show') }}"
                               class="text-xs text-yellow-700 hover:underline">
                                ← Edit company details
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

                {{-- Total Submissions --}}
                <a href="{{ route('client.submissions.index') }}"
                   class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition group
                          {{ !$client || !$client->service_agreement_signed_at ? 'pointer-events-none opacity-50' : '' }}">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Submissions</span>
                        <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center group-hover:bg-blue-100 transition">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ $summary['total_submissions'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Total submitted</p>
                </a>

                {{-- Pending --}}
                <a href="{{ route('client.submissions.index') }}"
                   class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition group
                          {{ !$client || !$client->service_agreement_signed_at ? 'pointer-events-none opacity-50' : '' }}">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pending</span>
                        <div class="w-9 h-9 bg-amber-50 rounded-lg flex items-center justify-center group-hover:bg-amber-100 transition">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ $summary['pending_submissions'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Awaiting processing</p>
                </a>

                {{-- Results Ready --}}
                <a href="{{ route('client.results.index') }}"
                   class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition group
                          {{ !$client || !$client->service_agreement_signed_at ? 'pointer-events-none opacity-50' : '' }}">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Results</span>
                        <div class="w-9 h-9 bg-green-50 rounded-lg flex items-center justify-center group-hover:bg-green-100 transition">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ $summary['results_ready'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Ready to view</p>
                </a>

                {{-- Unpaid Invoices --}}
                <a href="{{ route('client.invoices.index') }}"
                   class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition group
                          {{ !$client || !$client->service_agreement_signed_at ? 'pointer-events-none opacity-50' : '' }}">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Invoices</span>
                        <div class="w-9 h-9 bg-red-50 rounded-lg flex items-center justify-center group-hover:bg-red-100 transition">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ $summary['unpaid_invoices'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Unpaid</p>
                </a>

                {{-- Open Complaints --}}
                <a href="{{ route('client.complaints.index') }}"
                   class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition group
                          {{ !$client || !$client->service_agreement_signed_at ? 'pointer-events-none opacity-50' : '' }}">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Complaints</span>
                        <div class="w-9 h-9 bg-purple-50 rounded-lg flex items-center justify-center group-hover:bg-purple-100 transition">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ $summary['open_complaints'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">Open</p>
                </a>

            </div>

            {{-- Main Content Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Quick Actions + Client Info --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-800">Quick Actions</h3>
                        </div>
                        <div class="p-4 space-y-2">

                            @if(! $client)
                                {{-- Step 1: No profile — complete profile first --}}
                                <a href="{{ route('client.profile.company.show') }}"
                                   class="flex items-center gap-3 w-full px-4 py-3 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Complete Company Profile
                                </a>
                                <div class="flex items-center gap-3 w-full px-4 py-3 rounded-lg bg-gray-50 text-gray-300 text-sm font-medium cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    New Submission
                                </div>
                                <div class="flex items-center gap-3 w-full px-4 py-3 rounded-lg bg-gray-50 text-gray-300 text-sm font-medium cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    View Results
                                </div>
                                <div class="flex items-center gap-3 w-full px-4 py-3 rounded-lg bg-gray-50 text-gray-300 text-sm font-medium cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Pay Invoice
                                </div>
                            @elseif(! $client->service_agreement_signed_at)
                                {{-- Step 2: Profile done, sign agreement --}}
                                <a href="{{ route('client.agreement.show') }}"
                                   class="flex items-center gap-3 w-full px-4 py-3 rounded-lg bg-yellow-400 text-yellow-900 text-sm font-medium hover:bg-yellow-500 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2a2 2 0 01.586-1.414z"/>
                                    </svg>
                                    Sign Service Agreement
                                </a>
                                <a href="{{ route('client.profile.company.show') }}"
                                   class="flex items-center gap-3 w-full px-4 py-3 rounded-lg bg-gray-50 text-gray-600 text-sm font-medium hover:bg-gray-100 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Edit Company Details
                                </a>
                                <div class="flex items-center gap-3 w-full px-4 py-3 rounded-lg bg-gray-50 text-gray-300 text-sm font-medium cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    New Submission
                                </div>
                                <div class="flex items-center gap-3 w-full px-4 py-3 rounded-lg bg-gray-50 text-gray-300 text-sm font-medium cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Pay Invoice
                                </div>
                            @else
                                {{-- Agreement signed — all actions available --}}
                                <a href="{{ route('client.submissions.create') }}"
                                   class="flex items-center gap-3 w-full px-4 py-3 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    New Submission
                                </a>
                                <a href="{{ route('client.results.index') }}"
                                   class="flex items-center gap-3 w-full px-4 py-3 rounded-lg bg-gray-50 text-gray-700 text-sm font-medium hover:bg-gray-100 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    View Results
                                </a>
                                <a href="{{ route('client.invoices.index') }}"
                                   class="flex items-center gap-3 w-full px-4 py-3 rounded-lg bg-gray-50 text-gray-700 text-sm font-medium hover:bg-gray-100 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Pay Invoice
                                </a>
                                <a href="{{ route('client.complaints.create') }}"
                                   class="flex items-center gap-3 w-full px-4 py-3 rounded-lg bg-gray-50 text-gray-700 text-sm font-medium hover:bg-gray-100 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                    </svg>
                                    Lodge Complaint
                                </a>
                            @endif

                        </div>
                    </div>

                    {{-- Client Info --}}
                    @if($client)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="font-semibold text-gray-800">Company Details</h3>
                            <a href="{{ route('client.profile.company.show') }}"
                               class="text-xs text-gray-400 hover:text-gray-600 hover:underline transition">
                                Edit
                            </a>
                        </div>
                        <div class="px-6 py-4 space-y-3 text-sm">
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Company</p>
                                <p class="text-gray-700 font-medium">{{ $client->company_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Address</p>
                                <p class="text-gray-700">{{ $client->address }}</p>
                            </div>
                            @if($client->company_phone)
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide">Phone</p>
                                <p class="text-gray-700">{{ $client->company_phone }}</p>
                            </div>
                            @endif

                            {{-- Service Agreement Status --}}
                            <div class="pt-2 border-t border-gray-50">
                                <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Service Agreement</p>
                                @if($client->service_agreement_signed_at)
                                    <div class="flex items-center justify-between">
                                        <span class="inline-flex items-center gap-1.5 text-xs text-green-700 bg-green-50 px-2.5 py-1 rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                                            </svg>
                                            Signed
                                        </span>
                                        <span class="text-xs text-gray-400">
                                            {{ $client->service_agreement_signed_at->format('d M Y') }}
                                        </span>
                                    </div>
                                    {{-- Expiry notice (1 year from signing) --}}
                                    @php
                                        $expiresAt = $client->service_agreement_signed_at->addYear();
                                        $daysLeft  = now()->diffInDays($expiresAt, false);
                                    @endphp
                                    @if($daysLeft <= 30 && $daysLeft > 0)
                                        <p class="text-xs text-amber-600 mt-1">
                                            ⚠ Agreement expires in {{ $daysLeft }} day{{ $daysLeft === 1 ? '' : 's' }}.
                                            Contact the lab to renew.
                                        </p>
                                    @elseif($daysLeft <= 0)
                                        <p class="text-xs text-red-600 mt-1">
                                            Agreement has expired. Please contact the lab.
                                        </p>
                                    @else
                                        <p class="text-xs text-gray-400 mt-1">
                                            Valid until {{ $expiresAt->format('d M Y') }}
                                        </p>
                                    @endif

                                    {{-- Download & View links --}}
                                    <div class="mt-2 flex items-center gap-2">
                                        <a href="{{ route('client.agreement.download') }}"
                                           class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 font-medium hover:underline transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Download PDF
                                        </a>
                                        <span class="text-gray-200 text-xs">|</span>
                                        <a href="{{ route('client.agreement.show') }}"
                                           class="text-xs text-gray-400 hover:text-gray-600 hover:underline transition">
                                            View Agreement
                                        </a>
                                    </div>
                                    {{-- Director countersign status --}}
                                    @if($client->director_signed_at)
                                        <div class="mt-3 flex items-center gap-2 text-xs text-green-700">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                                            </svg>
                                            Countersigned by Director on {{ $client->director_signed_at->format('d M Y') }}
                                        </div>
                                    @else
                                        <div class="mt-3 flex items-center gap-2 text-xs text-amber-600">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a1 1 0 112 0v4a1 1 0 11-2 0V9zm1-5a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                                            </svg>
                                            Awaiting Director countersignature
                                        </div>
                                    @endif
                                @else
                                    <div class="flex items-center justify-between">
                                        <span class="inline-flex items-center gap-1.5 text-xs text-yellow-700 bg-yellow-50 px-2.5 py-1 rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                            </svg>
                                            Not signed
                                        </span>
                                        <a href="{{ route('client.agreement.show') }}"
                                           class="text-xs text-yellow-600 hover:underline font-medium">
                                            Sign now →
                                        </a>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>