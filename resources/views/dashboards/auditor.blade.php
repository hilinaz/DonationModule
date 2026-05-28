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

    <div class="space-y-6">

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 text-center">
                <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Donation::count() }}</p>
                <p class="text-xs text-gray-400 mt-1">Total Donations</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 text-center">
                <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Campaign::count() }}</p>
                <p class="text-xs text-gray-400 mt-1">Campaigns</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 text-center">
                <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Donor::count() }}</p>
                <p class="text-xs text-gray-400 mt-1">Donors</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 text-center">
                <p class="text-2xl font-bold text-teal-600">${{ number_format(\App\Models\Donation::where('payment_status','completed')->sum('amount_base'), 0) }}</p>
                <p class="text-xs text-gray-400 mt-1">Verified Raised</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center gap-3 mb-2">
                <div class="h-8 w-8 rounded-lg bg-amber-50 flex items-center justify-center">
                    <svg class="h-4 w-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                </div>
                <h3 class="text-sm font-semibold text-gray-800">Read-Only Access</h3>
            </div>
            <p class="text-sm text-gray-500">This dashboard provides a read-only summary for compliance review. Contact the system administrator for detailed audit reports.</p>
        </div>

    </div>
</x-app-layout>
