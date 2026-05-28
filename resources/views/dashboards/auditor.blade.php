<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-800">Auditor Dashboard</h1>
            <p class="text-xs text-gray-400 mt-0.5">
                <a href="{{ route('dashboard') }}" class="hover:text-teal-500 transition-colors">Dashboards</a>
                <span class="mx-1.5 text-gray-300">&rsaquo;</span>
                <span class="text-gray-600">Auditor</span>
            </p>
        </div>
    </x-slot>

    @php
        $totalDonations = \App\Models\Donation::count();
        $totalRaised    = \App\Models\Donation::where('payment_status', 'completed')->sum('amount_base');
        $totalCampaigns = \App\Models\Campaign::count();
        $totalDonors    = \App\Models\Donor::count();

        $statusSummary = \App\Models\Donation::selectRaw('payment_status, count(*) as count, sum(amount_base) as total')
            ->groupBy('payment_status')
            ->get()
            ->keyBy('payment_status');

        $largeDonations = \App\Models\Donation::with(['donor', 'campaign'])
            ->orderByDesc('amount_base')
            ->limit(10)
            ->get();
    @endphp

    <div class="space-y-6">

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 text-center">
                <p class="text-2xl font-bold text-gray-800">{{ number_format($totalDonations) }}</p>
                <p class="text-xs text-gray-400 mt-1">Total Donations</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 text-center">
                <p class="text-2xl font-bold text-teal-600">${{ number_format($totalRaised, 0) }}</p>
                <p class="text-xs text-gray-400 mt-1">Total Raised (USD)</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 text-center">
                <p class="text-2xl font-bold text-gray-800">{{ number_format($totalCampaigns) }}</p>
                <p class="text-xs text-gray-400 mt-1">Campaigns</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 text-center">
                <p class="text-2xl font-bold text-gray-800">{{ number_format($totalDonors) }}</p>
                <p class="text-xs text-gray-400 mt-1">Donors</p>
            </div>
        </div>

        <!-- Donation Summary by Status -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="text-sm font-semibold text-gray-700">Donation Summary by Status</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Count</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Amount (USD)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach(['completed', 'pending', 'failed', 'refunded'] as $status)
                            @php
                                $row = $statusSummary[$status] ?? null;
                                $statusColors = [
                                    'completed' => 'bg-green-100 text-green-700',
                                    'pending'   => 'bg-amber-100 text-amber-700',
                                    'failed'    => 'bg-red-100 text-red-700',
                                    'refunded'  => 'bg-gray-100 text-gray-600',
                                ];
                                $color = $statusColors[$status];
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-3.5">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $color }} capitalize">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-right font-semibold text-gray-800">
                                    {{ number_format($row?->count ?? 0) }}
                                </td>
                                <td class="px-6 py-3.5 text-right font-semibold text-gray-800">
                                    ${{ number_format($row?->total ?? 0, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Large Donations -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-700">Top 10 Donations by Amount</h3>
                <a href="{{ route('reports.donations') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700 transition-colors">Full Report →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Donor</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Campaign</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount (USD)</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($largeDonations as $i => $donation)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-3.5 text-gray-400 text-xs font-semibold">{{ $i + 1 }}</td>
                                <td class="px-4 py-3.5 font-medium text-gray-800">
                                    {{ $donation->donor ? $donation->donor->first_name . ' ' . $donation->donor->last_name : '—' }}
                                </td>
                                <td class="px-4 py-3.5 text-gray-600 text-xs">{{ $donation->campaign?->name ?? '—' }}</td>
                                <td class="px-4 py-3.5 text-right font-bold text-gray-800">
                                    ${{ number_format($donation->amount_base, 2) }}
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
                                <td class="px-6 py-3.5 text-gray-500 text-xs">
                                    {{ $donation->donated_at?->format('d M Y') ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-400">No donations yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Report Links -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Reports</h3>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('reports.donations') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-teal-50 hover:bg-teal-100 text-teal-700 text-sm font-semibold rounded-lg transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    Donation Report
                </a>
                <a href="{{ route('reports.donors') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-sm font-semibold rounded-lg transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    Donor Analytics
                </a>
                <a href="{{ route('reports.campaigns') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-sm font-semibold rounded-lg transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                    Campaign Report
                </a>
            </div>
        </div>

    </div>
</x-app-layout>
