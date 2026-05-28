<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-800">Record Donation</h1>
            <p class="text-xs text-gray-400 mt-0.5">
                <a href="{{ route('dashboard') }}" class="hover:text-teal-500 transition-colors">Dashboard</a>
                <span class="mx-1.5 text-gray-300">&rsaquo;</span>
                <a href="{{ route('donations.index') }}" class="hover:text-teal-500 transition-colors">Donations</a>
                <span class="mx-1.5 text-gray-300">&rsaquo;</span>
                <span class="text-gray-600">New</span>
            </p>
        </div>
    </x-slot>

    <div class="max-w-2xl">

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-4">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <form method="POST" action="{{ route('donations.store') }}" class="space-y-5">
                @csrf

                <!-- Donor -->
                <div>
                    <label for="donor_id" class="block text-xs font-semibold text-gray-600 mb-1.5">Donor <span class="text-red-500">*</span></label>
                    <select id="donor_id" name="donor_id" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all">
                        <option value="">Select a donor...</option>
                        @foreach($donors as $donor)
                            <option value="{{ $donor->id }}"
                                {{ (old('donor_id', $selectedDonor?->id) == $donor->id) ? 'selected' : '' }}>
                                {{ $donor->first_name }} {{ $donor->last_name }}
                                @if($donor->organization_name) — {{ $donor->organization_name }} @endif
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('donor_id')" class="mt-1" />
                </div>

                <!-- Campaign -->
                <div>
                    <label for="campaign_id" class="block text-xs font-semibold text-gray-600 mb-1.5">Campaign</label>
                    <select id="campaign_id" name="campaign_id"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all">
                        <option value="">No campaign</option>
                        @foreach($campaigns as $campaign)
                            <option value="{{ $campaign->id }}" {{ old('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                {{ $campaign->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('campaign_id')" class="mt-1" />
                </div>

                <!-- Type & Source -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="donation_type" class="block text-xs font-semibold text-gray-600 mb-1.5">Donation Type <span class="text-red-500">*</span></label>
                        <select id="donation_type" name="donation_type" required
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all">
                            <option value="one_time"  {{ old('donation_type', 'one_time') === 'one_time'  ? 'selected' : '' }}>One-Time</option>
                            <option value="recurring" {{ old('donation_type') === 'recurring' ? 'selected' : '' }}>Recurring</option>
                            <option value="pledge"    {{ old('donation_type') === 'pledge'    ? 'selected' : '' }}>Pledge</option>
                            <option value="in_kind"   {{ old('donation_type') === 'in_kind'   ? 'selected' : '' }}>In-Kind</option>
                        </select>
                        <x-input-error :messages="$errors->get('donation_type')" class="mt-1" />
                    </div>
                    <div>
                        <label for="source" class="block text-xs font-semibold text-gray-600 mb-1.5">Source <span class="text-red-500">*</span></label>
                        <select id="source" name="source" required
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all">
                            <option value="offline" {{ old('source', 'offline') === 'offline' ? 'selected' : '' }}>Offline</option>
                            <option value="portal"  {{ old('source') === 'portal'  ? 'selected' : '' }}>Portal</option>
                            <option value="event"   {{ old('source') === 'event'   ? 'selected' : '' }}>Event</option>
                        </select>
                        <x-input-error :messages="$errors->get('source')" class="mt-1" />
                    </div>
                </div>

                <!-- Currency & Amount -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="currency" class="block text-xs font-semibold text-gray-600 mb-1.5">Currency <span class="text-red-500">*</span></label>
                        <select id="currency" name="currency" required
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all">
                            <option value="USD" {{ old('currency', 'USD') === 'USD' ? 'selected' : '' }}>USD — US Dollar</option>
                            <option value="GBP" {{ old('currency') === 'GBP' ? 'selected' : '' }}>GBP — British Pound</option>
                            <option value="EUR" {{ old('currency') === 'EUR' ? 'selected' : '' }}>EUR — Euro</option>
                            <option value="ETB" {{ old('currency') === 'ETB' ? 'selected' : '' }}>ETB — Ethiopian Birr</option>
                        </select>
                        <x-input-error :messages="$errors->get('currency')" class="mt-1" />
                    </div>
                    <div>
                        <label for="amount_original" class="block text-xs font-semibold text-gray-600 mb-1.5">Amount <span class="text-red-500">*</span></label>
                        <input type="number" id="amount_original" name="amount_original" step="0.01" min="0.01"
                               value="{{ old('amount_original') }}" required
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all" />
                        <x-input-error :messages="$errors->get('amount_original')" class="mt-1" />
                    </div>
                </div>

                <!-- Exchange Rate -->
                <div>
                    <label for="exchange_rate" class="block text-xs font-semibold text-gray-600 mb-1.5">Exchange Rate to USD</label>
                    <input type="number" id="exchange_rate" name="exchange_rate" step="0.000001" min="0"
                           value="{{ old('exchange_rate', 1) }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all" />
                    <p class="text-xs text-gray-400 mt-1">Set to 1 if currency is already USD.</p>
                    <x-input-error :messages="$errors->get('exchange_rate')" class="mt-1" />
                </div>

                <!-- Payment Status -->
                <div>
                    <label for="payment_status" class="block text-xs font-semibold text-gray-600 mb-1.5">Payment Status <span class="text-red-500">*</span></label>
                    <select id="payment_status" name="payment_status" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all">
                        <option value="pending"   {{ old('payment_status', 'pending') === 'pending'   ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ old('payment_status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="failed"    {{ old('payment_status') === 'failed'    ? 'selected' : '' }}>Failed</option>
                        <option value="refunded"  {{ old('payment_status') === 'refunded'  ? 'selected' : '' }}>Refunded</option>
                    </select>
                    <x-input-error :messages="$errors->get('payment_status')" class="mt-1" />
                </div>

                <!-- Donated At -->
                <div>
                    <label for="donated_at" class="block text-xs font-semibold text-gray-600 mb-1.5">Donation Date <span class="text-red-500">*</span></label>
                    <input type="datetime-local" id="donated_at" name="donated_at"
                           value="{{ old('donated_at', now()->format('Y-m-d\TH:i')) }}" required
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all" />
                    <x-input-error :messages="$errors->get('donated_at')" class="mt-1" />
                </div>

                <!-- Transaction Reference -->
                <div>
                    <label for="transaction_reference" class="block text-xs font-semibold text-gray-600 mb-1.5">Transaction Reference</label>
                    <input type="text" id="transaction_reference" name="transaction_reference"
                           value="{{ old('transaction_reference') }}" placeholder="e.g. TXN-12345"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all" />
                    <x-input-error :messages="$errors->get('transaction_reference')" class="mt-1" />
                </div>

                <!-- Gift Aid -->
                <div class="flex items-center gap-3">
                    <input type="checkbox" id="gift_aid_eligible" name="gift_aid_eligible" value="1"
                           {{ old('gift_aid_eligible') ? 'checked' : '' }}
                           class="h-4 w-4 rounded border-gray-300 text-teal-500 focus:ring-teal-400" />
                    <label for="gift_aid_eligible" class="text-sm text-gray-700">Gift Aid Eligible</label>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-teal-500 hover:bg-teal-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                        Record Donation
                    </button>
                    <a href="{{ route('donations.index') }}"
                       class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-semibold rounded-lg transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
