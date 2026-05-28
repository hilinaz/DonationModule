<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-base font-semibold text-gray-800">Donation #{{ str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}</h1>
                <p class="text-xs text-gray-400 mt-0.5">
                    <a href="{{ route('dashboard') }}" class="hover:text-teal-500 transition-colors">Dashboard</a>
                    <span class="mx-1.5 text-gray-300">&rsaquo;</span>
                    <a href="{{ route('donations.index') }}" class="hover:text-teal-500 transition-colors">Donations</a>
                    <span class="mx-1.5 text-gray-300">&rsaquo;</span>
                    <span class="text-gray-600">#{{ str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}</span>
                </p>
            </div>
            <a href="{{ route('donations.receipt', $donation) }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-teal-500 hover:bg-teal-600 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Print Receipt
            </a>
        </div>
    </x-slot>

    <div class="max-w-2xl space-y-4">

        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('status') }}</div>
        @endif

        <!-- Main Details -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700">Donation Details</h2>
            </div>
            <dl class="divide-y divide-gray-50">
                <div class="px-6 py-3.5 flex items-center justify-between">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Receipt No.</dt>
                    <dd class="text-sm font-semibold text-gray-800">#{{ str_pad($donation->id, 6, '0', STR_PAD_LEFT) }}</dd>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Donor</dt>
                    <dd class="text-sm text-gray-800">
                        @if($donation->donor)
                            <a href="{{ route('donors.show', $donation->donor) }}" class="text-teal-600 hover:text-teal-700 font-medium transition-colors">
                                {{ $donation->donor->first_name }} {{ $donation->donor->last_name }}
                            </a>
                        @else
                            —
                        @endif
                    </dd>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Campaign</dt>
                    <dd class="text-sm text-gray-800">{{ $donation->campaign?->name ?? '—' }}</dd>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Donation Type</dt>
                    <dd>
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 capitalize">
                            {{ str_replace('_', ' ', $donation->donation_type) }}
                        </span>
                    </dd>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Source</dt>
                    <dd class="text-sm text-gray-800 capitalize">{{ $donation->source }}</dd>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Amount</dt>
                    <dd class="text-sm font-bold text-gray-800">
                        {{ $donation->currency }} {{ number_format($donation->amount_original, 2) }}
                        @if($donation->currency !== 'USD')
                            <span class="text-xs text-gray-400 font-normal ml-1">(≈ ${{ number_format($donation->amount_base, 2) }} USD)</span>
                        @endif
                    </dd>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Exchange Rate</dt>
                    <dd class="text-sm text-gray-800">{{ $donation->exchange_rate }}</dd>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Payment Status</dt>
                    <dd>
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
                    </dd>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Gift Aid Eligible</dt>
                    <dd>
                        @if($donation->gift_aid_eligible)
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Yes</span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">No</span>
                        @endif
                    </dd>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Transaction Ref.</dt>
                    <dd class="text-sm text-gray-800 font-mono">{{ $donation->transaction_reference ?? '—' }}</dd>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Donated At</dt>
                    <dd class="text-sm text-gray-800">{{ $donation->donated_at?->format('d M Y, H:i') ?? '—' }}</dd>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Recorded At</dt>
                    <dd class="text-sm text-gray-800">{{ $donation->created_at->format('d M Y, H:i') }}</dd>
                </div>
            </dl>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('donations.index') }}"
               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-semibold rounded-lg transition-colors">
                ← Back to Donations
            </a>
        </div>
    </div>
</x-app-layout>
