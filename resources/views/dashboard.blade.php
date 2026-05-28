<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-800">Dashboard</h1>
            <p class="text-xs text-gray-400 mt-0.5">Welcome back, {{ Auth::user()->name }}</p>
        </div>
    </x-slot>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-8 text-center max-w-md mx-auto mt-8">
        <div class="h-14 w-14 rounded-2xl bg-teal-50 flex items-center justify-center mx-auto mb-4">
            <svg class="h-7 w-7 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <h3 class="text-base font-semibold text-gray-800">No role assigned</h3>
        <p class="text-sm text-gray-500 mt-1.5">Contact your administrator to have a role assigned to your account.</p>
        <a href="{{ route('profile.edit') }}" class="inline-block mt-4 px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white text-sm font-semibold rounded-lg transition-colors">
            View Profile
        </a>
    </div>
</x-app-layout>
