<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-800">Donor Portal</h1>
            <p class="text-xs text-gray-400 mt-0.5">Welcome back, {{ Auth::user()->name }}</p>
        </div>
    </x-slot>

    <div class="space-y-6"
         x-data="{
            modal: false,
            campaignId: null,
            campaignName: '',
            donationType: 'one_time',
            currency: 'USD',
            amount: '',
            openModal(id, name) {
                this.campaignId   = id;
                this.campaignName = name;
                this.donationType = 'one_time';
                this.currency     = 'USD';
                this.amount       = '';
                this.modal        = true;
            }
         }">

        {{-- Flash --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-teal-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">${{ number_format($totalGiven, 2) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Total Donated (USD)</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $myDonations->count() }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Donations Made</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $activeCampaigns->count() }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Active Campaigns</p>
                </div>
            </div>
        </div>

        {{-- Active Campaigns --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800">Active Campaigns</h3>
                <span class="text-xs text-gray-400">{{ $activeCampaigns->count() }} open for donations</span>
            </div>

            @if($activeCampaigns->isEmpty())
                <div class="px-6 py-12 text-center">
                    <svg class="h-10 w-10 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    <p class="text-sm text-gray-400">No active campaigns right now. Check back soon.</p>
                </div>
            @else
                <div class="divide-y divide-gray-50">
                    @foreach($activeCampaigns as $campaign)
                    <div class="px-6 py-5 flex items-start gap-4">
                        {{-- Campaign info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <h4 class="text-sm font-semibold text-gray-800">{{ $campaign->name }}</h4>
                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-green-100 text-green-700">Active</span>
                                @php $typeColors = ['general'=>'bg-blue-50 text-blue-600','event'=>'bg-purple-50 text-purple-600','emergency'=>'bg-red-50 text-red-600']; @endphp
                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full {{ $typeColors[$campaign->type] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($campaign->type) }}</span>
                            </div>

                            @if($campaign->description)
                                <p class="text-xs text-gray-500 mb-3 line-clamp-2">{{ $campaign->description }}</p>
                            @endif

                            <div class="flex justify-between text-xs mb-1.5">
                                <span class="font-semibold text-teal-600">${{ number_format($campaign->raised, 2) }} raised</span>
                                <span class="text-gray-400">Goal: ${{ number_format($campaign->goal_amount, 2) }}</span>
                            </div>
                            <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-teal-500 rounded-full" style="width: {{ $campaign->pct }}%"></div>
                            </div>
                            <div class="flex justify-between text-[10px] text-gray-400 mt-1">
                                <span>{{ $campaign->pct }}% of goal</span>
                                <span>{{ $campaign->ends_at ? 'Ends ' . $campaign->ends_at->format('M d, Y') : 'No end date' }}</span>
                            </div>
                        </div>

                        {{-- Donate button --}}
                        @if($donor)
                            <button @click="openModal({{ $campaign->id }}, '{{ addslashes($campaign->name) }}')"
                                    class="shrink-0 mt-1 px-5 py-2.5 bg-teal-500 hover:bg-teal-600 active:scale-95 text-white text-xs font-semibold rounded-lg transition-all shadow-sm">
                                Donate
                            </button>
                        @else
                            <span class="shrink-0 mt-1 px-5 py-2.5 bg-gray-100 text-gray-400 text-xs font-semibold rounded-lg cursor-not-allowed" title="No donor profile linked">
                                Donate
                            </span>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Donation History --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800">My Donation History</h3>
                @if($myDonations->isNotEmpty())
                    <span class="text-xs text-gray-400">{{ $myDonations->count() }} record(s)</span>
                @endif
            </div>

            @if($myDonations->isEmpty())
                <div class="px-6 py-12 text-center">
                    <svg class="h-10 w-10 text-gray-200 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    @if(!$donor)
                        <p class="text-sm text-gray-400">No donor profile linked to your account.</p>
                        <p class="text-xs text-gray-400 mt-1">Contact an administrator to get set up.</p>
                    @else
                        <p class="text-sm text-gray-400">No donations yet. Support a campaign above!</p>
                    @endif
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Campaign</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Receipt</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($myDonations as $don)
                            @php $sc = ['completed'=>'bg-green-100 text-green-700','pending'=>'bg-amber-100 text-amber-700','failed'=>'bg-red-100 text-red-600','refunded'=>'bg-gray-100 text-gray-600']; @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-3.5">
                                    <p class="font-medium text-gray-800">{{ $don->campaign?->name ?? 'General Fund' }}</p>
                                </td>
                                <td class="px-4 py-3.5">
                                    <p class="font-semibold text-gray-800">{{ $don->currency }} {{ number_format($don->amount_original, 2) }}</p>
                                    @if($don->currency !== 'USD')
                                        <p class="text-xs text-gray-400">${{ number_format($don->amount_base, 2) }} USD</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', $don->donation_type) }}</td>
                                <td class="px-4 py-3.5">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $sc[$don->payment_status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst($don->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-xs text-gray-500">
                                    {{ ($don->donated_at ?? $don->created_at)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-3.5 text-right">
                                    <a href="{{ route('donor.receipt', $don) }}" target="_blank"
                                       class="text-xs font-semibold text-teal-600 hover:text-teal-700 transition-colors">
                                        View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- ===================== DONATION MODAL ===================== --}}
        <div x-show="modal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="modal = false"
             class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 overflow-y-auto">

            <div x-show="modal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 @click.stop
                 class="bg-white rounded-2xl shadow-2xl w-full max-w-lg my-4">

                {{-- Modal header --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
                    <div>
                        <h3 class="text-base font-bold text-gray-800">Make a Donation</h3>
                        <p class="text-xs text-gray-400 mt-0.5" x-text="campaignName"></p>
                    </div>
                    <button @click="modal = false" class="p-1.5 rounded-lg text-gray-400 hover:bg-gray-100 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Form --}}
                <form :action="'/portal/donate/' + campaignId" method="POST" class="px-6 py-5 space-y-5">
                    @csrf

                    {{-- Step indicator --}}
                    <div class="flex items-center gap-2 text-xs text-gray-400 mb-1">
                        <span class="h-5 w-5 rounded-full bg-teal-500 text-white flex items-center justify-center font-bold text-[10px]">1</span>
                        <span class="text-gray-600 font-medium">Donation Details</span>
                        <span class="flex-1 h-px bg-gray-200"></span>
                        <span class="h-5 w-5 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-[10px]">2</span>
                        <span>Review & Confirm</span>
                    </div>

                    {{-- Donation Type --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Donation Type</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            @foreach(['one_time' => 'One-Time', 'recurring' => 'Recurring', 'pledge' => 'Pledge', 'in_kind' => 'In-Kind'] as $val => $label)
                            <label class="cursor-pointer">
                                <input type="radio" name="donation_type" value="{{ $val }}" x-model="donationType" class="sr-only peer" {{ $val === 'one_time' ? 'checked' : '' }}>
                                <div class="text-center py-2.5 px-2 rounded-xl border-2 text-xs font-semibold transition-all
                                            peer-checked:border-teal-500 peer-checked:bg-teal-50 peer-checked:text-teal-700
                                            border-gray-200 text-gray-500 hover:border-gray-300">
                                    {{ $label }}
                                </div>
                            </label>
                            @endforeach
                        </div>
                        <p x-show="donationType === 'recurring'" class="text-xs text-blue-600 mt-1.5 bg-blue-50 px-3 py-1.5 rounded-lg">
                            Recurring donations will be processed monthly. You can cancel anytime.
                        </p>
                        <p x-show="donationType === 'pledge'" class="text-xs text-amber-600 mt-1.5 bg-amber-50 px-3 py-1.5 rounded-lg">
                            A pledge is a commitment to donate. Our team will follow up to arrange payment.
                        </p>
                        <p x-show="donationType === 'in_kind'" class="text-xs text-purple-600 mt-1.5 bg-purple-50 px-3 py-1.5 rounded-lg">
                            In-kind donations are non-monetary (goods, services). Use the notes field to describe.
                        </p>
                    </div>

                    {{-- Amount + Currency --}}
                    <div x-show="donationType !== 'in_kind'">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Amount</label>
                        <div class="flex gap-2">
                            <select name="currency" x-model="currency"
                                    class="w-28 border border-gray-200 rounded-xl px-3 py-2.5 text-sm font-semibold text-gray-700 focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 bg-gray-50">
                                @foreach($currencies as $cur)
                                    <option value="{{ $cur }}">{{ $cur }}</option>
                                @endforeach
                            </select>
                            <div class="relative flex-1">
                                <input type="number" name="amount" x-model="amount"
                                       min="0.01" step="0.01" placeholder="0.00"
                                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all"
                                       :required="donationType !== 'in_kind'" />
                            </div>
                        </div>

                        {{-- Quick presets --}}
                        <div class="grid grid-cols-5 gap-1.5 mt-2">
                            @foreach([10, 25, 50, 100, 250] as $p)
                            <button type="button" @click="amount = '{{ $p }}'"
                                    class="py-1.5 text-xs font-semibold border border-gray-200 rounded-lg text-gray-500
                                           hover:border-teal-400 hover:text-teal-600 hover:bg-teal-50 transition-all"
                                    :class="amount == '{{ $p }}' ? 'border-teal-500 bg-teal-50 text-teal-700' : ''">
                                {{ $p }}
                            </button>
                            @endforeach
                        </div>

                        {{-- Live conversion hint --}}
                        <p x-show="currency !== 'USD' && amount > 0" class="text-xs text-gray-400 mt-1.5">
                            Approximate USD equivalent shown on receipt.
                        </p>
                    </div>

                    {{-- In-kind: amount field hidden, show note --}}
                    <div x-show="donationType === 'in_kind'">
                        <input type="hidden" name="amount" value="0.01">
                        <input type="hidden" name="currency" value="USD">
                    </div>

                    {{-- Gift Aid --}}
                    <div class="flex items-start gap-3 p-3.5 bg-green-50 border border-green-200 rounded-xl">
                        <input type="checkbox" name="gift_aid" id="gift_aid" value="1"
                               class="mt-0.5 h-4 w-4 rounded border-gray-300 text-teal-500 focus:ring-teal-400">
                        <div>
                            <label for="gift_aid" class="text-sm font-semibold text-gray-700 cursor-pointer">Claim Gift Aid</label>
                            <p class="text-xs text-gray-500 mt-0.5">If you're a UK taxpayer, we can reclaim 25p for every £1 you donate at no extra cost to you.</p>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
                            Notes <span class="text-gray-400 font-normal normal-case">(optional)</span>
                        </label>
                        <textarea name="notes" rows="2" placeholder="Any message or dedication..."
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all resize-none"></textarea>
                    </div>

                    {{-- Summary box --}}
                    <div x-show="amount > 0 || donationType === 'in_kind'"
                         class="bg-gray-50 border border-gray-200 rounded-xl p-4 space-y-2">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Summary</p>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Campaign</span>
                            <span class="font-semibold text-gray-800" x-text="campaignName"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Type</span>
                            <span class="font-semibold text-gray-800 capitalize" x-text="donationType.replace('_', ' ')"></span>
                        </div>
                        <div class="flex justify-between text-sm" x-show="donationType !== 'in_kind'">
                            <span class="text-gray-600">Amount</span>
                            <span class="font-bold text-teal-600" x-text="currency + ' ' + (parseFloat(amount) || 0).toFixed(2)"></span>
                        </div>
                        <div class="flex justify-between text-sm" x-show="donationType === 'in_kind'">
                            <span class="text-gray-600">Amount</span>
                            <span class="font-semibold text-gray-800">In-Kind (non-monetary)</span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3 pt-1">
                        <button type="button" @click="modal = false"
                                class="flex-1 py-2.5 border border-gray-200 text-gray-600 text-sm font-semibold rounded-xl hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 py-2.5 bg-teal-500 hover:bg-teal-600 active:scale-[0.98] text-white text-sm font-semibold rounded-xl transition-all shadow-sm flex items-center justify-center gap-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            Confirm Donation
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
