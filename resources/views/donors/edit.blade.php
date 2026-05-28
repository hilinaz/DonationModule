<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Donor Profile</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('donors.update', $donor) }}" class="bg-white p-6 rounded shadow-sm">
                @csrf
                @method('PUT')
                @include('donors._form', ['donor' => $donor])

                <div class="mt-6 flex gap-2">
                    <x-primary-button>Update Donor</x-primary-button>
                    <a href="{{ route('donors.show', $donor) }}" class="px-4 py-2 rounded border border-gray-300">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
