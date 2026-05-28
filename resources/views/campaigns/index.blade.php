<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-base font-semibold text-gray-800">Campaigns</h1>
                <p class="text-xs text-gray-400 mt-0.5">
                    <a href="{{ route('dashboard') }}" class="hover:text-teal-500 transition-colors">Dashboard</a>
                    <span class="mx-1.5 text-gray-300">&rsaquo;</span>
                    <span class="text-gray-600">Campaigns</span>
                </p>
            </div>
            <a href="{{ route('campaigns.create') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-teal-500 hover:bg-teal-600 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                New Campaign
            </a>
        </div>
    </x-slot>

    <div class="space-y-4">

        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('status') }}</div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <form method="GET" action="{{ route('campaigns.index') }}" class="flex flex-col md:flex-row gap-3 items-end">
                <div class="flex-grow">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Search</label>
                    <input name="search" type="text" value="{{ $search }}" placeholder="Name, code or description..."
                           class="w-full border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all" />
                </div>
                <div class="w-full md:w-44">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all">
                        <option value="">All Statuses</option>
                        <option value="draft" @selected($status === 'draft')>Draft</option>
                        <option value="active" @selected($status === 'active')>Active</option>
                        <option value="completed" @selected($status === 'completed')>Completed</option>
                        <option value="cancelled" @selected($status === 'cancelled')>Cancelled</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white text-sm font-semibold rounded-lg transition-colors">Filter</button>
                    <a href="{{ route('campaigns.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-semibold rounded-lg transition-colors">Reset</a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Campaign</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Progress</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Dates</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($campaigns as $campaign)
                        @php
                            $raised = $campaign->donations()->where('payment_status', 'completed')->sum('amount_base');
                            $pct = $campaign->goal_amount > 0 ? min(100, round(($raised / $campaign->goal_amount) * 100, 1)) : 0;
                            $typeColors = ['general'=>'bg-blue-50 text-blue-700','event'=>'bg-purple-50 text-purple-700','emergency'=>'bg-red-50 text-red-700'];
                            $statusColors = ['draft'=>'bg-gray-100 text-gray-600','active'=>'bg-green-100 text-green-700','completed'=>'bg-teal-100 text-teal-700','cancelled'=>'bg-red-100 text-red-600'];
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-3.5">
                                <a href="{{ route('campaigns.show', $campaign) }}" class="font-medium text-gray-800 hover:text-teal-600 transition-colors">{{ $campaign->name }}</a>
                                <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $campaign->code }}</p>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $typeColors[$campaign->type] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($campaign->type) }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="w-40">
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="font-semibold text-teal-600">${{ number_format($raised, 0) }}</span>
                                        <span class="text-gray-400">of ${{ number_format($campaign->goal_amount, 0) }}</span>
                                    </div>
                                    <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-teal-500 rounded-full" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-0.5 text-right">{{ $pct }}%</p>
                                </div>
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $statusColors[$campaign->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($campaign->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-xs text-gray-500">
                                <p>{{ $campaign->starts_at ? $campaign->starts_at->format('M d, Y') : 'Immediate' }}</p>
                                <p class="mt-0.5 text-gray-400">{{ $campaign->ends_at ? $campaign->ends_at->format('M d, Y') : 'Ongoing' }}</p>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('campaigns.show', $campaign) }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700 transition-colors">View</a>
                                    <a href="{{ route('campaigns.edit', $campaign) }}" class="text-xs font-semibold text-amber-500 hover:text-amber-600 transition-colors">Edit</a>
                                    <form method="POST" action="{{ route('campaigns.destroy', $campaign) }}"
                                          onsubmit="return confirm('Delete this campaign?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-400">No campaigns found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($campaigns->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $campaigns->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
