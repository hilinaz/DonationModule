<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-base font-semibold text-gray-800">Profile</h1>
            <p class="text-xs text-gray-400 mt-0.5">
                <a href="{{ route('dashboard') }}" class="hover:text-teal-500 transition-colors">Dashboard</a>
                <span class="mx-1.5 text-gray-300">&rsaquo;</span>
                <span class="text-gray-600">Profile</span>
            </p>
        </div>
    </x-slot>

    <div class="space-y-6 max-w-2xl">

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            @include('profile.partials.update-password-form')
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            @include('profile.partials.delete-user-form')
        </div>

    </div>
</x-app-layout>
