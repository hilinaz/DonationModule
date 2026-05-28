<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-800">Marketing Dashboard</h1>
            <p class="text-xs text-gray-400 mt-0.5">
                <a href="{{ route('dashboard') }}" class="hover:text-teal-500 transition-colors">Dashboards</a>
                <span class="mx-1.5 text-gray-300">&rsaquo;</span>
                <span class="text-gray-600">Marketing</span>
            </p>
        </div>
    </x-slot>

    @php
        $totalDonors       = \App\Models\Donor::count();
        $activeCampaigns   = \App\Models\Campaign::where('status', 'active')->count();
        $commsSent         = \App\Models\CommunicationLog::count();
        $newsletterSubs    = \App\Models\DonorPreference::where('accepts_email', true)->count();
        $recentComms       = \App\Models\CommunicationLog::with('donor')->orderByDesc('sent_at')->limit(10)->get();
        $lifecycleBreakdown = \App\Models\Donor::selectRaw('lifecycle_stage, count(*) as count')
            ->groupBy('lifecycle_stage')
            ->pluck('count', 'lifecycle_stage');
    @endphp

    <div class="space-y-6">

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-pink-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-800">{{ number_format($totalDonors) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Total Donors</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-teal-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-800">{{ number_format($activeCampaigns) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Active Campaigns</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-800">{{ number_format($commsSent) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Communications Sent</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-indigo-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-800">{{ number_format($newsletterSubs) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Newsletter Subscribers</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Lifecycle Breakdown -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Donor Engagement Overview</h3>
                <div class="space-y-3">
                    @php
                        $stages = [
                            'new'      => ['label' => 'New',      'color' => 'bg-blue-500'],
                            'active'   => ['label' => 'Active',   'color' => 'bg-green-500'],
                            'inactive' => ['label' => 'Inactive', 'color' => 'bg-gray-400'],
                        ];
                    @endphp
                    @foreach($stages as $stage => $meta)
                        @php
                            $count = $lifecycleBreakdown[$stage] ?? 0;
                            $pct   = $totalDonors > 0 ? round(($count / $totalDonors) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-semibold text-gray-600">{{ $meta['label'] }}</span>
                                <span class="text-xs text-gray-500">{{ number_format($count) }} ({{ $pct }}%)</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="{{ $meta['color'] }} h-2 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Communications -->
            <div class="md:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-700">Recent Communications</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Donor</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Channel</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Subject</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($recentComms as $comm)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-3 font-medium text-gray-800 text-xs">
                                        {{ $comm->donor ? $comm->donor->first_name . ' ' . $comm->donor->last_name : '—' }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 text-xs capitalize">{{ $comm->channel ?? '—' }}</td>
                                    <td class="px-4 py-3 text-gray-600 text-xs capitalize">{{ $comm->message_type ?? '—' }}</td>
                                    <td class="px-4 py-3 text-gray-600 text-xs max-w-xs truncate">{{ $comm->subject ?? '—' }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 capitalize">
                                            {{ $comm->status ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-500 text-xs">
                                        {{ $comm->sent_at?->format('d M Y') ?? '—' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-400">No communications logged yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
