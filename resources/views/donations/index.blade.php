<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-base font-semibold text-gray-800">Donations</h1>
                <p class="text-xs text-gray-400 mt-0.5">
                    <a href="{{ route('dashboard') }}" class="hover:text-teal-500 transition-colors">Dashboard</a>
                    <span class="mx-1.5 text-gray-300">&rsaquo;</span>
                    <span class="text-gray-600">Donations</span>
                </p>
            </div>
            @hasanyrole('Admin|Fundraising Manager|Finance')
            <a href="{{ route('donations.create') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-teal-500 hover:bg-teal-600 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                New Donation
            </a>
            @endhasanyrole
        </div>
    </x-slot>

    <div class="space-y-4">

        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('status') }}</div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <form method="GET" action="{{ route('donations.index') }}" class="flex flex-wrap gap-3">
                <select name="donor_id" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all">
                    <option value="">All Donors</option>
                    @foreach($donors as $donor)
                        <option value="{{ $donor->id }}" {{ request('donor_id') == $donor->id ? 'selected' : '' }}>
                            {{ $donor->first_name }} {{ $donor->last_name }}
                        </option>
                    @endforeach
                </select>
                <select name="campaign_id" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all">
                    <option value="">All Campaigns</option>
                    @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>
                            {{ $campaign->name }}
                        </option>
                    @endforeach
                </select>
                <select name="payment_status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all">
                    <option value="">All Statuses</option>
                    <option value="pending"   {{ request('payment_status') === 'pending'   ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('payment_status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed"    {{ request('payment_status') === 'failed'    ? 'selected' : '' }}>Failed</option>
                    <option value="refunded"  {{ request('payment_status') === 'refunded'  ? 'selected' : '' }}>Refunded</option>
                </select>
                <select name="donation_type" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all">
                    <option value="">All Types</option>
                    <option value="one_time"  {{ request('donation_type') === 'one_time'  ? 'selected' : '' }}>One-Time</option>
                    <option value="recurring" {{ request('donation_type') === 'recurring' ? 'selected' : '' }}>Recurring</option>
                    <option value="pledge"    {{ request('donation_type') === 'pledge'    ? 'selected' : '' }}>Pledge</option>
                    <option value="in_kind"   {{ request('donation_type') === 'in_kind'   ? 'selected' : '' }}>In-Kind</option>
                </select>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all" />
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all" />
                <button type="submit"
                        class="px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white text-sm font-semibold rounded-lg transition-colors">
                    Filter
                </button>
                @if(request()->hasAny(['donor_id','campaign_id','payment_status','donation_type','date_from','date_to']))
                <a href="{{ route('donations.index') }}"
                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-semibold rounded-lg transition-colors">
                    Clear
                </a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Donor</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Campaign</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($donations as $donation)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-3.5">
                                    @if($donation->donor)
                                        <a href="{{ route('donors.show', $donation->donor) }}" class="font-medium text-gray-800 hover:text-teal-600 transition-colors">
                                            {{ $donation->donor->first_name }} {{ $donation->donor->last_name }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-gray-600 text-xs">
                                    {{ $donation->campaign?->name ?? '—' }}
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 capitalize">
                                        {{ str_replace('_', ' ', $donation->donation_type) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-right font-semibold text-gray-800">
                                    {{ $donation->currency }} {{ number_format($donation->amount_original, 2) }}
                                    @if($donation->currency !== 'USD')
                                        <span class="block text-xs text-gray-400 font-normal">≈ ${{ number_format($donation->amount_base, 2) }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5">
                                    @php
                                        $statusColors = [
                                            'completed' => 'bg-green-100 text-green-700',
                                            'pending'   => 'bg-amber-100 text-amber-700',
                                            'failed'    => 'bg-red-100 text-red-700',
                                            'refunded'  => 'bg-gray-100 text-gray-600',
                                        ];
                                        $color = $statusColors[$donation->payment_status] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $color }} capitalize">
                                        {{ $donation->payment_status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-gray-500 text-xs">
                                    {{ $donation->donated_at?->format('d M Y') ?? '—' }}
                                </td>
                                <td class="px-6 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('donations.show', $donation) }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700 transition-colors">View</a>
                                        <a href="{{ route('donations.receipt', $donation) }}" target="_blank" class="text-xs font-semibold text-gray-500 hover:text-gray-700 transition-colors">Receipt</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-400">No donations found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($donations->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $donations->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
