<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-800">Admin Dashboard</h1>
            <p class="text-xs text-gray-400 mt-0.5">
                <a href="{{ route('dashboard') }}" class="hover:text-teal-500 transition-colors">Dashboards</a>
                <span class="mx-1.5 text-gray-300">&rsaquo;</span>
                <span class="text-gray-600">Admin</span>
            </p>
        </div>
    </x-slot>

    <div class="space-y-6">

        <!-- Stat Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-teal-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Donor::count() }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Total Donors</p>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-orange-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Campaign::count() }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Total Campaigns</p>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-pink-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ \App\Models\User::count() }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Staff Members</p>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">${{ number_format(\App\Models\Donation::where('payment_status','completed')->sum('amount_base'), 0) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Total Raised</p>
                </div>
            </div>

        </div>

        <!-- Main content grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Quick Actions -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-800">Quick Actions</h3>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <a href="{{ route('staff.index') }}"
                       class="group flex items-center gap-4 p-4 rounded-xl border border-gray-100 hover:border-teal-200 hover:bg-teal-50/50 transition-all">
                        <div class="h-10 w-10 rounded-lg bg-teal-50 group-hover:bg-teal-100 flex items-center justify-center shrink-0 transition-colors">
                            <svg class="h-5 w-5 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Staff Management</p>
                            <p class="text-xs text-gray-400 mt-0.5">Manage roles & members</p>
                        </div>
                    </a>

                    <a href="{{ route('donors.index') }}"
                       class="group flex items-center gap-4 p-4 rounded-xl border border-gray-100 hover:border-orange-200 hover:bg-orange-50/50 transition-all">
                        <div class="h-10 w-10 rounded-lg bg-orange-50 group-hover:bg-orange-100 flex items-center justify-center shrink-0 transition-colors">
                            <svg class="h-5 w-5 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Donor Records</p>
                            <p class="text-xs text-gray-400 mt-0.5">View & manage donors</p>
                        </div>
                    </a>

                    <a href="{{ route('campaigns.index') }}"
                       class="group flex items-center gap-4 p-4 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-blue-50/50 transition-all">
                        <div class="h-10 w-10 rounded-lg bg-blue-50 group-hover:bg-blue-100 flex items-center justify-center shrink-0 transition-colors">
                            <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Campaigns</p>
                            <p class="text-xs text-gray-400 mt-0.5">Oversee all campaigns</p>
                        </div>
                    </a>

                    <a href="{{ route('profile.edit') }}"
                       class="group flex items-center gap-4 p-4 rounded-xl border border-gray-100 hover:border-pink-200 hover:bg-pink-50/50 transition-all">
                        <div class="h-10 w-10 rounded-lg bg-pink-50 group-hover:bg-pink-100 flex items-center justify-center shrink-0 transition-colors">
                            <svg class="h-5 w-5 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Settings</p>
                            <p class="text-xs text-gray-400 mt-0.5">Profile & preferences</p>
                        </div>
                    </a>

                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-800">Recent Donors</h3>
                    <a href="{{ route('donors.index') }}" class="text-xs font-semibold text-teal-500 hover:text-teal-600 transition-colors">View All</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse(\App\Models\Donor::latest()->limit(5)->get() as $donor)
                    <div class="px-6 py-3.5 flex items-center gap-3">
                        <div class="h-8 w-8 rounded-full bg-teal-50 flex items-center justify-center text-teal-600 text-xs font-bold shrink-0">
                            {{ strtoupper(substr($donor->first_name, 0, 1)) }}{{ strtoupper(substr($donor->last_name, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $donor->first_name }} {{ $donor->last_name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $donor->email ?? 'No email' }}</p>
                        </div>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500 shrink-0">
                            {{ ucfirst($donor->category ?? 'donor') }}
                        </span>
                    </div>
                    @empty
                    <div class="px-6 py-8 text-center text-sm text-gray-400">No donors yet.</div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
