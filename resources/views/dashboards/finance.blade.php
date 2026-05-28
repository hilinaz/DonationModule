<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-800">Finance Dashboard</h1>
            <p class="text-xs text-gray-400 mt-0.5">
                <a href="{{ route('dashboard') }}" class="hover:text-teal-500 transition-colors">Dashboards</a>
                <span class="mx-1.5 text-gray-300">&rsaquo;</span>
                <span class="text-gray-600">Finance</span>
            </p>
        </div>
    </x-slot>

    @php
        $totalCollected   = \App\Models\Donation::where('payment_status', 'completed')->sum('amount_base');
        $pendingCount     = \App\Models\Donation::where('payment_status', 'pending')->count();
        $activePledges    = \App\Models\Pledge::whereIn('status', ['pending', 'partial'])->count();
        $giftAidCount     = \App\Models\Donation::where('gift_aid_eligible', true)->count();
        $recentDonations  = \App\Models\Donation::with(['donor', 'campaign'])->orderByDesc('donated_at')->limit(10)->get();
        $pendingDonations = \App\Models\Donation::with(['donor', 'campaign'])->where('payment_status', 'pending')->orderByDesc('donated_at')->get();
    @endphp

    <div class="space-y-6">

        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('status') }}</div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-teal-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-800">${{ number_format($totalCollected, 0) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Total Collected</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-800">{{ $pendingCount }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Pending Validation</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-800">{{ $activePledges }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Active Pledges</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-gray-800">{{ $giftAidCount }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Gift Aid Eligible</p>
                </div>
            </div>
        </div>

        <!-- Pending Donations (Validate) -->
        @if($pendingDonations->isNotEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-amber-50 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-amber-800">Pending Donations — Awaiting Validation</h3>
                <span class="text-xs font-semibold text-amber-600 bg-amber-100 px-2.5 py-1 rounded-full">{{ $pendingDonations->count() }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Donor</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Campaign</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($pendingDonations as $donation)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-3.5 font-medium text-gray-800">
                                    {{ $donation->donor ? $donation->donor->first_name . ' ' . $donation->donor->last_name : '—' }}
                                </td>
                                <td class="px-4 py-3.5 text-gray-600 text-xs">{{ $donation->campaign?->name ?? '—' }}</td>
                                <td class="px-4 py-3.5 text-right font-semibold text-gray-800">
                                    {{ $donation->currency }} {{ number_format($donation->amount_original, 2) }}
                                </td>
                                <td class="px-4 py-3.5 text-gray-500 text-xs">
                                    {{ $donation->donated_at?->format('d M Y') ?? '—' }}
                                </td>
                                <td class="px-6 py-3.5 text-right">
                                    <form method="POST" action="{{ route('donations.validate', $donation) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded-lg transition-colors">
                                            Validate
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Recent Donations -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-700">Recent Donations</h3>
                <a href="{{ route('donations.index') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700 transition-colors">View All →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Donor</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Campaign</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Receipt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentDonations as $donation)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-3.5 font-medium text-gray-800">
                                    {{ $donation->donor ? $donation->donor->first_name . ' ' . $donation->donor->last_name : '—' }}
                                </td>
                                <td class="px-4 py-3.5 text-gray-600 text-xs">{{ $donation->campaign?->name ?? '—' }}</td>
                                <td class="px-4 py-3.5 text-right font-semibold text-gray-800">
                                    {{ $donation->currency }} {{ number_format($donation->amount_original, 2) }}
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
                                    <a href="{{ route('donations.receipt', $donation) }}" target="_blank"
                                       class="text-xs font-semibold text-teal-600 hover:text-teal-700 transition-colors">Receipt</a>
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

    </div>
</x-app-layout>
