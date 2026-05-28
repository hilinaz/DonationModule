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

    <div class="space-y-6">

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-teal-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">${{ number_format(\App\Models\Donation::where('payment_status','completed')->sum('amount_base'), 0) }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Total Collected</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Donation::where('payment_status','pending')->count() }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Pending Validation</p>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                <div class="h-12 w-12 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                    <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Pledge::count() }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">Active Pledges</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-800 mb-2">Reporting</h3>
            <p class="text-sm text-gray-500">Full reporting tools and transaction validation features are coming soon. Contact your system administrator for manual report exports.</p>
        </div>

    </div>
</x-app-layout>
