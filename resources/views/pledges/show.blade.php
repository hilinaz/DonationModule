<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-base font-semibold text-gray-800">Pledge #{{ str_pad($pledge->id, 6, '0', STR_PAD_LEFT) }}</h1>
                <p class="text-xs text-gray-400 mt-0.5">
                    <a href="{{ route('dashboard') }}" class="hover:text-teal-500 transition-colors">Dashboard</a>
                    <span class="mx-1.5 text-gray-300">&rsaquo;</span>
                    <a href="{{ route('pledges.index') }}" class="hover:text-teal-500 transition-colors">Pledges</a>
                    <span class="mx-1.5 text-gray-300">&rsaquo;</span>
                    <span class="text-gray-600">#{{ str_pad($pledge->id, 6, '0', STR_PAD_LEFT) }}</span>
                </p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl space-y-4">

        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('status') }}</div>
        @endif

        <!-- Pledge Details -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700">Pledge Details</h2>
            </div>
            <dl class="divide-y divide-gray-50">
                <div class="px-6 py-3.5 flex items-center justify-between">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Donor</dt>
                    <dd class="text-sm text-gray-800">
                        @if($pledge->donor)
                            <a href="{{ route('donors.show', $pledge->donor) }}" class="text-teal-600 hover:text-teal-700 font-medium transition-colors">
                                {{ $pledge->donor->first_name }} {{ $pledge->donor->last_name }}
                            </a>
                        @else
                            —
                        @endif
                    </dd>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Campaign</dt>
                    <dd class="text-sm text-gray-800">{{ $pledge->campaign?->name ?? '—' }}</dd>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pledged Amount</dt>
                    <dd class="text-sm font-bold text-gray-800">{{ $pledge->currency }} {{ number_format($pledge->pledged_amount, 2) }}</dd>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Fulfilled Amount</dt>
                    <dd class="text-sm font-semibold text-gray-800">{{ $pledge->currency }} {{ number_format($pledge->fulfilled_amount, 2) }}</dd>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Remaining</dt>
                    <dd class="text-sm font-semibold text-amber-600">
                        {{ $pledge->currency }} {{ number_format(max(0, $pledge->pledged_amount - $pledge->fulfilled_amount), 2) }}
                    </dd>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Due Date</dt>
                    <dd class="text-sm text-gray-800">{{ $pledge->due_date?->format('d M Y') ?? '—' }}</dd>
                </div>
                <div class="px-6 py-3.5 flex items-center justify-between">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</dt>
                    <dd>
                        @php
                            $statusColors = [
                                'pending'   => 'bg-amber-100 text-amber-700',
                                'partial'   => 'bg-blue-100 text-blue-700',
                                'fulfilled' => 'bg-green-100 text-green-700',
                                'cancelled' => 'bg-gray-100 text-gray-500',
                            ];
                            $color = $statusColors[$pledge->status] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $color }} capitalize">
                            {{ $pledge->status }}
                        </span>
                    </dd>
                </div>
                @if($pledge->notes)
                <div class="px-6 py-3.5">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Notes</dt>
                    <dd class="text-sm text-gray-700">{{ $pledge->notes }}</dd>
                </div>
                @endif
                <div class="px-6 py-3.5 flex items-center justify-between">
                    <dt class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</dt>
                    <dd class="text-sm text-gray-800">{{ $pledge->created_at->format('d M Y, H:i') }}</dd>
                </div>
            </dl>
        </div>

        <!-- Progress Bar -->
        @php
            $pct = $pledge->pledged_amount > 0
                ? min(100, round(($pledge->fulfilled_amount / $pledge->pledged_amount) * 100))
                : 0;
        @endphp
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-600">Fulfillment Progress</span>
                <span class="text-xs font-bold text-teal-600">{{ $pct }}%</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2.5">
                <div class="bg-teal-500 h-2.5 rounded-full transition-all" style="width: {{ $pct }}%"></div>
            </div>
        </div>

        <!-- Update Form -->
        @if($pledge->status !== 'fulfilled' && $pledge->status !== 'cancelled')
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Update Fulfillment</h3>
            <form method="POST" action="{{ route('pledges.update', $pledge) }}" class="space-y-4">
                @csrf @method('PATCH')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="fulfilled_amount" class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Fulfilled Amount <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="fulfilled_amount" name="fulfilled_amount"
                               step="0.01" min="0"
                               value="{{ old('fulfilled_amount', $pledge->fulfilled_amount) }}" required
                               class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all" />
                        <x-input-error :messages="$errors->get('fulfilled_amount')" class="mt-1" />
                    </div>
                    <div>
                        <label for="status" class="block text-xs font-semibold text-gray-600 mb-1.5">Status <span class="text-red-500">*</span></label>
                        <select id="status" name="status" required
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all">
                            <option value="pending"   {{ old('status', $pledge->status) === 'pending'   ? 'selected' : '' }}>Pending</option>
                            <option value="partial"   {{ old('status', $pledge->status) === 'partial'   ? 'selected' : '' }}>Partial</option>
                            <option value="fulfilled" {{ old('status', $pledge->status) === 'fulfilled' ? 'selected' : '' }}>Fulfilled</option>
                            <option value="cancelled" {{ old('status', $pledge->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-1" />
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-xs font-semibold text-gray-600 mb-1.5">Notes</label>
                    <textarea id="notes" name="notes" rows="2"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all resize-none">{{ old('notes', $pledge->notes) }}</textarea>
                </div>

                <button type="submit"
                        class="px-5 py-2.5 bg-teal-500 hover:bg-teal-600 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                    Update Pledge
                </button>
            </form>
        </div>
        @endif

        <div class="flex items-center gap-3">
            <a href="{{ route('pledges.index') }}"
               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-semibold rounded-lg transition-colors">
                ← Back to Pledges
            </a>
        </div>
    </div>
</x-app-layout>
