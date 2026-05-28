<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create New Campaign') }}
            </h2>
            <a href="{{ route('campaigns.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm transition ease-in-out duration-150">
                &larr; Back to Campaigns
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    <div class="mb-6 pb-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Campaign Details</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Set up your fundraising goals, start/end dates, and visibility options for the new campaign.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('campaigns.store') }}">
                        @include('campaigns._form')

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
                            <a href="{{ route('campaigns.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150 me-3">
                                {{ __('Cancel') }}
                            </a>
                            <x-primary-button>
                                {{ __('Create Campaign') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
