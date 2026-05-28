<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-base font-semibold text-gray-800">Donors</h1>
                <p class="text-xs text-gray-400 mt-0.5">
                    <a href="{{ route('dashboard') }}" class="hover:text-teal-500 transition-colors">Dashboard</a>
                    <span class="mx-1.5 text-gray-300">&rsaquo;</span>
                    <span class="text-gray-600">Donors</span>
                </p>
            </div>
            <a href="{{ route('donors.create') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-teal-500 hover:bg-teal-600 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                New Donor
            </a>
        </div>
    </x-slot>

    <div class="space-y-4">

        @if (session('status'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('status') }}</div>
        @endif

        <!-- Search -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <form method="GET" action="{{ route('donors.index') }}" class="flex gap-3">
                <input id="search" name="search" type="text" value="{{ $search }}"
                       placeholder="Search by name, email, organization..."
                       class="flex-1 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-400/20 transition-all" />
                <button type="submit"
                        class="px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white text-sm font-semibold rounded-lg transition-colors">
                    Search
                </button>
                @if($search)
                <a href="{{ route('donors.index') }}"
                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-semibold rounded-lg transition-colors">
                    Clear
                </a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Donor</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($donors as $donor)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-full bg-teal-50 flex items-center justify-center text-teal-600 text-xs font-bold shrink-0">
                                            {{ strtoupper(substr($donor->first_name,0,1)) }}{{ strtoupper(substr($donor->last_name,0,1)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $donor->first_name }} {{ $donor->last_name }}</p>
                                            @if($donor->organization_name)
                                                <p class="text-xs text-gray-400">{{ $donor->organization_name }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="capitalize text-gray-600 text-xs">{{ $donor->donor_type }}</span>
                                </td>
                                <td class="px-4 py-3.5">
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600 uppercase">{{ $donor->category }}</span>
                                </td>
                                <td class="px-4 py-3.5 text-gray-500 text-xs">{{ $donor->email ?? '—' }}</td>
                                <td class="px-4 py-3.5">
                                    @if($donor->is_active)
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">Active</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-500">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('donors.show', $donor) }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700 transition-colors">View</a>
                                        <a href="{{ route('donors.edit', $donor) }}" class="text-xs font-semibold text-amber-500 hover:text-amber-600 transition-colors">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-400">No donors found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($donors->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                    {{ $donors->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
