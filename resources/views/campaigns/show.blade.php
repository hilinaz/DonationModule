<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ $campaign->name }}
                    </h2>
                    @php
                        $typeColors = [
                            'general' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'event' => 'bg-purple-100 text-purple-800 border-purple-200',
                            'emergency' => 'bg-red-100 text-red-800 border-red-200 animate-pulse',
                        ];
                        $statusColors = [
                            'draft' => 'bg-gray-100 text-gray-800 border-gray-200',
                            'active' => 'bg-green-100 text-green-800 border-green-200',
                            'completed' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                        ];
                    @endphp
                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full border {{ $typeColors[$campaign->type] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($campaign->type) }}
                    </span>
                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full border {{ $statusColors[$campaign->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($campaign->status) }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Code: <span class="font-mono bg-gray-100 px-1.5 py-0.5 rounded text-gray-700 font-semibold">{{ $campaign->code }}</span></p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('campaigns.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition ease-in-out duration-150">
                    &larr; All Campaigns
                </a>
                <a href="{{ route('campaigns.edit', $campaign) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm transition ease-in-out duration-150">
                    Edit Campaign
                </a>
                
                <form method="POST" action="{{ route('campaigns.destroy', $campaign) }}" onsubmit="return confirm('Are you sure you want to delete this campaign? This action can be undone by restoring later.');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm transition ease-in-out duration-150">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Success Status Message -->
            @if (session('status'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-md shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ms-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('status') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Campaign Stats and Overview Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Main Campaign Details -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg lg:col-span-2">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Campaign Overview</h3>
                        
                        <div class="prose max-w-none text-gray-600 mb-6">
                            @if ($campaign->description)
                                <p class="whitespace-pre-line leading-relaxed">{{ $campaign->description }}</p>
                            @else
                                <p class="text-gray-400 italic">No description provided for this campaign.</p>
                            @endif
                        </div>

                        <!-- Schedule and Metadata -->
                        <div class="border-t border-gray-100 pt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <span class="block text-xs font-semibold text-gray-400 uppercase">Start Date</span>
                                <span class="text-sm font-medium text-gray-800 mt-1 block">
                                    {{ $campaign->starts_at ? $campaign->starts_at->format('M d, Y') : 'Immediate' }}
                                </span>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-gray-400 uppercase">End Date</span>
                                <span class="text-sm font-medium text-gray-800 mt-1 block">
                                    {{ $campaign->ends_at ? $campaign->ends_at->format('M d, Y') : 'Ongoing' }}
                                </span>
                            </div>
                            <div>
                                <span class="block text-xs font-semibold text-gray-400 uppercase">Visibility</span>
                                <span class="text-sm font-medium mt-1 block">
                                    @if ($campaign->is_public)
                                        <span class="inline-flex items-center text-green-700 bg-green-50 px-2 py-0.5 rounded text-xs border border-green-200">
                                            Public (Portal)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center text-amber-700 bg-amber-50 px-2 py-0.5 rounded text-xs border border-amber-200">
                                            Internal Only
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fundraising Progress Card -->
                <div class="bg-gradient-to-br from-white to-gray-50 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Financial Progress</h3>
                        
                        @php
                            $percentage = $campaign->goal_amount > 0 ? min(100, round(($totalRaised / $campaign->goal_amount) * 100, 1)) : 0;
                        @endphp

                        <div class="space-y-4">
                            <!-- Goal and Raised Metric -->
                            <div class="flex justify-between items-end">
                                <div>
                                    <span class="block text-xs font-semibold text-gray-400 uppercase">Raised so far</span>
                                    <span class="text-2xl font-bold text-indigo-600">
                                        {{ number_format($totalRaised, 2) }} <span class="text-sm font-normal text-gray-500">{{ $campaign->goal_currency }}</span>
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="block text-xs font-semibold text-gray-400 uppercase">Goal</span>
                                    <span class="text-lg font-semibold text-gray-700">
                                        {{ number_format($campaign->goal_amount, 2) }} <span class="text-xs font-normal text-gray-500">{{ $campaign->goal_currency }}</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="relative w-full h-4 bg-gray-200 rounded-full overflow-hidden shadow-inner">
                                <div class="absolute h-full rounded-full bg-gradient-to-r from-indigo-500 to-indigo-600 transition-all duration-500" style="width: {{ $percentage }}%"></div>
                            </div>
                            
                            <div class="flex justify-between text-xs text-gray-500">
                                <span class="font-semibold text-indigo-600">{{ $percentage }}% achieved</span>
                                <span>
                                    @if ($campaign->goal_amount - $totalRaised > 0)
                                        {{ number_format($campaign->goal_amount - $totalRaised, 2) }} {{ $campaign->goal_currency }} remaining
                                    @else
                                        Goal reached!
                                    @endif
                                </span>
                            </div>

                            <!-- Dynamic Counter Widget -->
                            <div class="pt-4 mt-4 border-t border-gray-200 grid grid-cols-2 gap-4 text-center">
                                <div class="bg-indigo-50 p-3 rounded-lg border border-indigo-100">
                                    <span class="block text-xs font-semibold text-indigo-500 uppercase">Donations</span>
                                    <span class="text-xl font-bold text-indigo-900 mt-1 block">{{ $campaign->donations_count ?? $campaign->donations()->count() }}</span>
                                </div>
                                <div class="bg-emerald-50 p-3 rounded-lg border border-emerald-100">
                                    <span class="block text-xs font-semibold text-emerald-500 uppercase">Pledges</span>
                                    <span class="text-xl font-bold text-emerald-900 mt-1 block">{{ $campaign->pledges()->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Recent Activity Tabs (Donations / Pledges) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Recent Donations -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                            <h3 class="text-md font-semibold text-gray-800">Recent Donations</h3>
                            <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-0.5 rounded-full font-semibold">
                                Total: {{ $campaign->donations()->count() }}
                            </span>
                        </div>

                        <div class="space-y-4">
                            @forelse ($campaign->donations as $donation)
                                <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 border border-gray-100 hover:bg-gray-100 transition-colors">
                                    <div>
                                        <span class="text-sm font-semibold text-gray-800 block">
                                            @if ($donation->donor)
                                                {{ $donation->donor->first_name }} {{ $donation->donor->last_name }}
                                            @else
                                                Anonymous Donor
                                            @endif
                                        </span>
                                        <span class="text-xs text-gray-400 block mt-0.5">
                                            {{ $donation->donated_at->format('M d, Y H:i') }} &bull; {{ ucfirst($donation->donation_type) }}
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-bold text-emerald-600 block">
                                            +{{ number_format($donation->amount_original, 2) }} {{ $donation->currency }}
                                        </span>
                                        <span class="text-xs text-gray-400 block mt-0.5">
                                            Base: {{ number_format($donation->amount_base, 2) }} {{ $donation->base_currency }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-400 italic text-sm">
                                    No donations registered for this campaign yet.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Pledges -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4 border-b border-gray-100 pb-3">
                            <h3 class="text-md font-semibold text-gray-800">Recent Pledges</h3>
                            <span class="bg-amber-100 text-amber-800 text-xs px-2 py-0.5 rounded-full font-semibold">
                                Total: {{ $campaign->pledges()->count() }}
                            </span>
                        </div>

                        <div class="space-y-4">
                            @forelse ($campaign->pledges as $pledge)
                                <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 border border-gray-100 hover:bg-gray-100 transition-colors">
                                    <div>
                                        <span class="text-sm font-semibold text-gray-800 block">
                                            @if ($pledge->donor)
                                                {{ $pledge->donor->first_name }} {{ $pledge->donor->last_name }}
                                            @else
                                                Anonymous
                                            @endif
                                        </span>
                                        <span class="text-xs text-gray-400 block mt-0.5">
                                            Status: <span class="capitalize text-amber-600 font-semibold">{{ $pledge->status }}</span>
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm font-bold text-amber-600 block">
                                            {{ number_format($pledge->amount, 2) }} {{ $pledge->currency }}
                                        </span>
                                        <span class="text-xs text-gray-400 block mt-0.5">
                                            Due: {{ $pledge->due_date ? \Carbon\Carbon::parse($pledge->due_date)->format('M d, Y') : '-' }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-400 italic text-sm">
                                    No pledges registered for this campaign yet.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
