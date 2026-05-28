<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-800">New Pledge</h1>
            <p class="text-xs text-gray-400 mt-0.5">
                <a href="{{ route('dashboard') }}" class="hover:text-teal-500 transition-colors">Dashboard</a>
                <span class="mx-1.5 text-gray-300">&rsaquo;</span>
                <a href="{{ route('pledges.index') }}" class="hover:text-teal-500 transition-colors">Pledges</a>
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
            <form method="POST" action="{{ route('pledges.store') }}" class="space-y-5">
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

                <!-- Currency & Amount -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="currency" class="block text-xs font-semibold text-gray-600 mb-1.5">Currency <span class="text-red-500">*</span></label>
                        <select id="currency" name="currency" required
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all">
                            <option value="USD" {{ old('currency', 'USD') === 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="GBP" {{ old('currency') === 'GBP' ? 'selected' : '' }}>GBP</option>
                            <option value="EUR" {{ old('currency') === 'EUR' ? 'selected' : '' }}>EUR</option>
                            <option value="ETB" {{ old('currency') === 'ETB' ? 'selected' : '' }}>ETB</option>
                        </select>
                        <x-input-error :messages="$errors->get('currency')" class="mt-1" />
                    </div>
                    <div>
                        <label for="pledged_amount" class="block text-xs font-semibold text-gray-600 mb-1.5">Pledged Amount <span class="text-red-500">*</span></label>
                        <input type="number" id="pledged_amount" name="pledged_amount" step="0.01" min="0.01"
                               value="{{ old('pledged_amount') }}" required
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all" />
                        <x-input-error :messages="$errors->get('pledged_amount')" class="mt-1" />
                    </div>
                </div>

                <!-- Due Date -->
                <div>
                    <label for="due_date" class="block text-xs font-semibold text-gray-600 mb-1.5">Due Date</label>
                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all" />
                    <x-input-error :messages="$errors->get('due_date')" class="mt-1" />
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-xs font-semibold text-gray-600 mb-1.5">Notes</label>
                    <textarea id="notes" name="notes" rows="3"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all resize-none">{{ old('notes') }}</textarea>
                    <x-input-error :messages="$errors->get('notes')" class="mt-1" />
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 bg-teal-500 hover:bg-teal-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                        Create Pledge
                    </button>
                    <a href="{{ route('pledges.index') }}"
                       class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-semibold rounded-lg transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
