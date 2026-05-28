<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Donor Profile</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('donors.store') }}" class="bg-white p-6 rounded shadow-sm">
                @csrf
                @include('donors._form', ['donor' => new \App\Models\Donor()])

                <div class="mt-6 flex gap-2">
                    <x-primary-button>Save Donor</x-primary-button>
                    <a href="{{ route('donors.index') }}" class="px-4 py-2 rounded border border-gray-300">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
