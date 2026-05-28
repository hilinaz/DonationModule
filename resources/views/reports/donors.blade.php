<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-base font-semibold text-gray-800">Donor Analytics</h1>
                <p class="text-xs text-gray-400 mt-0.5">
                    <a href="{{ route('dashboard') }}" class="hover:text-teal-500 transition-colors">Dashboard</a>
                    <span class="mx-1.5 text-gray-300">&rsaquo;</span>
                    <span class="text-gray-600">Reports</span>
                    <span class="mx-1.5 text-gray-300">&rsaquo;</span>
                    <span class="text-gray-600">Donor Analytics</span>
                </p>
            </div>
            <a href="{{ route('reports.export.donors') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-teal-500 hover:bg-teal-600 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export CSV
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">

        <!-- KPI Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <p class="text-2xl font-bold text-gray-800">{{ number_format($totalDonors) }}</p>
                <p class="text-xs text-gray-400 mt-1">Total Donors</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <p class="text-2xl font-bold text-green-600">{{ number_format($activeDonors) }}</p>
                <p class="text-xs text-gray-400 mt-1">Active</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <p class="text-2xl font-bold text-gray-500">{{ number_format($inactiveDonors) }}</p>
                <p class="text-xs text-gray-400 mt-1">Inactive</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <p class="text-2xl font-bold text-blue-600">{{ number_format($newDonors) }}</p>
                <p class="text-xs text-gray-400 mt-1">New</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <p class="text-2xl font-bold text-teal-600">{{ $avgEngagement }}</p>
                <p class="text-xs text-gray-400 mt-1">Avg Engagement Score</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <p class="text-2xl font-bold text-indigo-600">{{ $retentionRate }}%</p>
                <p class="text-xs text-gray-400 mt-1">Retention Rate (12-month)</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <p class="text-2xl font-bold text-red-500">{{ $churnRate }}%</p>
                <p class="text-xs text-gray-400 mt-1">Churn Rate</p>
            </div>
        </div>

        <!-- Top Donors Table -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700">Top Donors by Total Given</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Donor</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Given</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Donations</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Last Donation</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Stage</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Score</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($topDonors as $i => $donor)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-3.5 text-gray-400 text-xs font-semibold">{{ $i + 1 }}</td>
                                <td class="px-4 py-3.5">
                                    <a href="{{ route('donors.show', $donor) }}" class="font-medium text-gray-800 hover:text-teal-600 transition-colors">
                                        {{ $donor->first_name }} {{ $donor->last_name }}
                                    </a>
                                    @if($donor->organization_name)
                                        <p class="text-xs text-gray-400">{{ $donor->organization_name }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-right font-bold text-gray-800">
                                    ${{ number_format($donor->total_given ?? 0, 2) }}
                                </td>
                                <td class="px-4 py-3.5 text-right text-gray-600">{{ $donor->donations_count }}</td>
                                <td class="px-4 py-3.5 text-gray-500 text-xs">
                                    {{ $donor->last_engaged_at?->format('d M Y') ?? '—' }}
                                </td>
                                <td class="px-4 py-3.5">
                                    @php
                                        $stageColors = [
                                            'active'   => 'bg-green-100 text-green-700',
                                            'inactive' => 'bg-gray-100 text-gray-500',
                                            'new'      => 'bg-blue-100 text-blue-700',
                                        ];
                                        $sc = $stageColors[$donor->lifecycle_stage] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $sc }} capitalize">
                                        {{ $donor->lifecycle_stage }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-right">
                                    <span class="text-sm font-bold text-teal-600">{{ $donor->engagement_score }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-400">No donor data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
