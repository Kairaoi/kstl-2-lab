<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $client ? 'Update Company Details' : 'Complete Your Company Profile' }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Progress Steps --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-0">

                    {{-- Step 1 --}}
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $client ? 'bg-green-500 text-white' : 'bg-blue-600 text-white' }}">
                            {{ $client ? '✓' : '1' }}
                        </div>
                        <span class="text-sm font-medium {{ $client ? 'text-green-700' : 'text-blue-700' }}">
                            Company Details
                        </span>
                    </div>

                    <div class="flex-1 h-0.5 mx-3 {{ $client ? 'bg-green-300' : 'bg-gray-200' }}"></div>

                    {{-- Step 2 --}}
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $client && $client->service_agreement_signed_at ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                            {{ $client && $client->service_agreement_signed_at ? '✓' : '2' }}
                        </div>
                        <span class="text-sm font-medium text-gray-500">
                            Sign Agreement
                        </span>
                    </div>

                    <div class="flex-1 h-0.5 mx-3 bg-gray-200"></div>

                    {{-- Step 3 --}}
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-500">
                            3
                        </div>
                        <span class="text-sm font-medium text-gray-500">Access Lab</span>
                    </div>

                </div>
            </div>

            {{-- Info Banner --}}
            @if(! $client)
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-blue-800">Step 1 of 2 — Company Details</p>
                            <p class="text-sm text-blue-700 mt-1">
                                Please provide your company information before signing the service agreement.
                                This information will appear on your test reports and invoices.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a1 1 0 112 0v4a1 1 0 11-2 0V9zm1-5a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST"
                  action="{{ $client ? route('client.profile.company.update') : route('client.profile.company.store') }}">
                @csrf
                @if($client) @method('PUT') @endif

                <x-validation-errors class="mb-4 bg-red-50 border border-red-200 rounded-xl p-4"/>

                {{-- Company Details --}}
                <div class="md:grid md:grid-cols-3 md:gap-6 mb-6">
                    <div class="md:col-span-1 px-4 sm:px-0">
                        <h3 class="text-lg font-medium text-gray-900">Company / Organisation</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Your business details as they will appear on reports and invoices.
                        </p>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="bg-white shadow rounded-xl overflow-hidden">
                            <div class="px-6 py-5 space-y-5">

                                <div>
                                    <x-label for="company_name" value="Company / Organisation Name *"/>
                                    <x-input id="company_name"
                                             type="text"
                                             name="company_name"
                                             value="{{ old('company_name', $client?->company_name) }}"
                                             class="mt-1 block w-full"
                                             placeholder="e.g. Smith Seafoods Ltd"
                                             required autofocus/>
                                    <x-input-error for="company_name" class="mt-1"/>
                                </div>

                                <div>
                                    <x-label for="address" value="Business Address *"/>
                                    <textarea id="address"
                                              name="address"
                                              rows="3"
                                              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm"
                                              placeholder="Street address, City, Island"
                                              required>{{ old('address', $client?->address) }}</textarea>
                                    <x-input-error for="address" class="mt-1"/>
                                </div>

                                <div>
                                    <x-label for="company_phone" value="Company Phone"/>
                                    <x-input id="company_phone"
                                             type="tel"
                                             name="company_phone"
                                             value="{{ old('company_phone', $client?->company_phone) }}"
                                             class="mt-1 block w-full sm:w-1/2"
                                             placeholder="+686 12345"/>
                                    <x-input-error for="company_phone" class="mt-1"/>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <x-section-border/>

                {{-- Responsible Officer --}}
                <div class="md:grid md:grid-cols-3 md:gap-6 mb-6">
                    <div class="md:col-span-1 px-4 sm:px-0">
                        <h3 class="text-lg font-medium text-gray-900">Responsible Officer</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            The person responsible for this account and who signs agreements.
                            May be different from the login user.
                        </p>
                    </div>
                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="bg-white shadow rounded-xl overflow-hidden">
                            <div class="px-6 py-5 space-y-5">

                                <div>
                                    <x-label for="responsible_officer_name" value="Full Name *"/>
                                    <x-input id="responsible_officer_name"
                                             type="text"
                                             name="responsible_officer_name"
                                             value="{{ old('responsible_officer_name', $client?->responsible_officer_name) }}"
                                             class="mt-1 block w-full"
                                             placeholder="e.g. John Smith"
                                             required/>
                                    <x-input-error for="responsible_officer_name" class="mt-1"/>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <x-label for="responsible_officer_email" value="Email Address *"/>
                                        <x-input id="responsible_officer_email"
                                                 type="email"
                                                 name="responsible_officer_email"
                                                 value="{{ old('responsible_officer_email', $client?->responsible_officer_email ?? $user->email) }}"
                                                 class="mt-1 block w-full"
                                                 required/>
                                        <x-input-error for="responsible_officer_email" class="mt-1"/>
                                    </div>

                                    <div>
                                        <x-label for="responsible_officer_phone" value="Phone"/>
                                        <x-input id="responsible_officer_phone"
                                                 type="tel"
                                                 name="responsible_officer_phone"
                                                 value="{{ old('responsible_officer_phone', $client?->responsible_officer_phone) }}"
                                                 class="mt-1 block w-full"
                                                 placeholder="+686 12345"/>
                                        <x-input-error for="responsible_officer_phone" class="mt-1"/>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-between pt-2 pb-8">
                    <a href="{{ route('client.dashboard') }}">
                        <x-secondary-button type="button">
                            Back to Dashboard
                        </x-secondary-button>
                    </a>

                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                        {{ $client ? 'Update Details' : 'Save & Continue to Agreement' }}
                        @if(! $client)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        @endif
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>