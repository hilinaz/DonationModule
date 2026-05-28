<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-base font-semibold text-gray-800">Fundraising Dashboard</h1>
                <p class="text-xs text-gray-400 mt-0.5">
                    <a href="{{ route('dashboard') }}" class="hover:text-teal-500 transition-colors">Dashboards</a>
                    <span class="mx-1.5 text-gray-300">&rsaquo;</span>
                    <span class="text-gray-600">Fundraising</span>
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('campaigns.create') }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-teal-500 hover:bg-teal-600 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                    New Campaign
                </a>
                <a href="{{ route('donors.create') }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-gray-50 text-gray-700 text-xs font-semibold rounded-lg border border-gray-200 transition-colors shadow-sm">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                    Add Donor
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">

        <!-- Stat Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-teal-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $campaignCount }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Total Campaigns</p>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ $activeCampaignCount }}</p>
                    <p class="text-xs text-green-500 font-medium mt-0.5">Active</p>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">${{ number_format($totalGoalAmount, 0) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Total Goal</p>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    @php $overallPct = $totalGoalAmount > 0 ? min(100, round(($totalRaisedAmount / $totalGoalAmount) * 100, 1)) : 0; @endphp
                    <p class="text-2xl font-bold text-teal-600">${{ number_format($totalRaisedAmount, 0) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $overallPct }}% of goal</p>
                </div>
            </div>

        </div>

        <!-- Tables grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Recent Campaigns -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-800">Recent Campaigns</h3>
                    <a href="{{ route('campaigns.index') }}" class="text-xs font-semibold text-teal-500 hover:text-teal-600 transition-colors">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Campaign</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Raised</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recentCampaigns as $camp)
                            @php
                                $campRaised = $camp->donations()->where('payment_status', 'completed')->sum('amount_base');
                                $campPct = $camp->goal_amount > 0 ? min(100, round(($campRaised / $camp->goal_amount) * 100)) : 0;
                                $badges = ['draft'=>'bg-gray-100 text-gray-600','active'=>'bg-green-100 text-green-700','completed'=>'bg-teal-100 text-teal-700','cancelled'=>'bg-red-100 text-red-600'];
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-3.5">
                                    <a href="{{ route('campaigns.show', $camp) }}" class="font-medium text-gray-800 hover:text-teal-600 transition-colors">{{ $camp->name }}</a>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $camp->code }} &bull; {{ ucfirst($camp->type) }}</p>
                                </td>
                                <td class="px-4 py-3.5">
                                    <p class="font-semibold text-gray-800">${{ number_format($campRaised, 0) }}</p>
                                    <p class="text-xs text-teal-500 mt-0.5">{{ $campPct }}%</p>
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $badges[$camp->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst($camp->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="px-6 py-8 text-center text-sm text-gray-400">No campaigns yet. Create your first one!</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Donors -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-800">Recent Donors</h3>
                    <a href="{{ route('donors.index') }}" class="text-xs font-semibold text-teal-500 hover:text-teal-600 transition-colors">View All</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recentDonors as $donor)
                    <div class="px-6 py-3.5 flex items-center gap-3">
                        <div class="h-8 w-8 rounded-full bg-teal-50 flex items-center justify-center text-teal-600 text-xs font-bold shrink-0">
                            {{ strtoupper(substr($donor->first_name, 0, 1)) }}{{ strtoupper(substr($donor->last_name, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <a href="{{ route('donors.show', $donor) }}" class="text-sm font-medium text-gray-800 hover:text-teal-600 transition-colors truncate block">
                                {{ $donor->first_name }} {{ $donor->last_name }}
                            </a>
                            <p class="text-xs text-gray-400 truncate">{{ $donor->email ?? 'No email' }}</p>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 shrink-0">
                            {{ ucfirst($donor->category ?? '') }}
                        </span>
                    </div>
                    @empty
                    <div class="px-6 py-8 text-center text-sm text-gray-400">No donors yet.</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
