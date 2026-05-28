<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-800">Campaign Performance</h1>
            <p class="text-xs text-gray-400 mt-0.5">
                <a href="{{ route('dashboard') }}" class="hover:text-teal-500 transition-colors">Dashboard</a>
                <span class="mx-1.5 text-gray-300">&rsaquo;</span>
                <span class="text-gray-600">Reports</span>
                <span class="mx-1.5 text-gray-300">&rsaquo;</span>
                <span class="text-gray-600">Campaigns</span>
            </p>
        </div>
    </x-slot>

    <div class="space-y-4">

        <!-- Summary Totals -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <p class="text-2xl font-bold text-gray-800">{{ $campaigns->count() }}</p>
                <p class="text-xs text-gray-400 mt-1">Total Campaigns</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <p class="text-2xl font-bold text-gray-600">${{ number_format($totalGoal, 2) }}</p>
                <p class="text-xs text-gray-400 mt-1">Total Goal (USD)</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <p class="text-2xl font-bold text-teal-600">${{ number_format($totalRaised, 2) }}</p>
                <p class="text-xs text-gray-400 mt-1">Total Raised (USD)</p>
            </div>
        </div>

        <!-- Campaign Table -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Campaign</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Goal</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Raised</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider w-40">Progress</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Donors</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($campaigns as $campaign)
                            @php
                                $raised = (float) ($campaign->raised_amount ?? 0);
                                $goal   = (float) ($campaign->goal_amount ?? 0);
                                $pct    = $goal > 0 ? min(100, round(($raised / $goal) * 100, 1)) : 0;
                                $statusColors = [
                                    'active'    => 'bg-green-100 text-green-700',
                                    'draft'     => 'bg-gray-100 text-gray-500',
                                    'completed' => 'bg-blue-100 text-blue-700',
                                    'paused'    => 'bg-amber-100 text-amber-700',
                                    'cancelled' => 'bg-red-100 text-red-600',
                                ];
                                $sc = $statusColors[$campaign->status] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-3.5">
                                    <a href="{{ route('campaigns.show', $campaign) }}" class="font-medium text-gray-800 hover:text-teal-600 transition-colors">
                                        {{ $campaign->name }}
                                    </a>
                                    @if($campaign->code)
                                        <p class="text-xs text-gray-400 font-mono">{{ $campaign->code }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-gray-600 text-xs capitalize">{{ $campaign->type ?? '—' }}</td>
                                <td class="px-4 py-3.5 text-right text-gray-700">
                                    {{ $campaign->goal_currency ?? 'USD' }} {{ number_format($goal, 2) }}
                                </td>
                                <td class="px-4 py-3.5 text-right font-semibold text-teal-700">
                                    ${{ number_format($raised, 2) }}
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-100 rounded-full h-2">
                                            <div class="bg-teal-500 h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="text-xs font-semibold text-gray-600 w-10 text-right">{{ $pct }}%</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-right text-gray-600">{{ $campaign->donations_count }}</td>
                                <td class="px-6 py-3.5">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $sc }} capitalize">
                                        {{ $campaign->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-400">No campaigns found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
