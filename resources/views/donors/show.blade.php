<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Donor Profile</h2>
            <div class="space-x-2">
                <a href="{{ route('donors.edit', $donor) }}" class="px-4 py-2 bg-amber-500 text-white rounded-md">Edit</a>
                <a href="{{ route('donors.index') }}" class="px-4 py-2 border border-gray-300 rounded-md">Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-green-100 text-green-800 p-3 rounded">{{ session('status') }}</div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1 bg-white rounded shadow-sm p-6">
                    <h3 class="font-semibold text-lg">Profile & Contact</h3>
                    <dl class="mt-3 space-y-2 text-sm">
                        <div><dt class="font-semibold">Name:</dt><dd>{{ $donor->first_name }} {{ $donor->last_name }}</dd></div>
                        <div><dt class="font-semibold">Organization:</dt><dd>{{ $donor->organization_name ?? '-' }}</dd></div>
                        <div><dt class="font-semibold">Segment:</dt><dd class="capitalize">{{ $donor->donor_type }}</dd></div>
                        <div><dt class="font-semibold">Category:</dt><dd class="uppercase">{{ $donor->category }}</dd></div>
                        <div><dt class="font-semibold">Email:</dt><dd>{{ $donor->email ?? '-' }}</dd></div>
                        <div><dt class="font-semibold">Phone:</dt><dd>{{ $donor->phone ?? '-' }}</dd></div>
                        <div><dt class="font-semibold">Address:</dt><dd>{{ $donor->address ?? '-' }}</dd></div>
                        <div><dt class="font-semibold">Preferred channel:</dt><dd>{{ $donor->preferred_channel ?? '-' }}</dd></div>
                    </dl>
                </div>

                <div class="lg:col-span-2 bg-white rounded shadow-sm p-6">
                    <h3 class="font-semibold text-lg">Preferences</h3>
                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        <div>Email opt-in: {{ $donor->preference?->accepts_email ? 'Yes' : 'No' }}</div>
                        <div>SMS opt-in: {{ $donor->preference?->accepts_sms ? 'Yes' : 'No' }}</div>
                        <div>Newsletter: {{ $donor->preference?->newsletter_opt_in ? 'Yes' : 'No' }}</div>
                        <div>Language: {{ $donor->preference?->preferred_language ?? 'en' }}</div>
                    </div>
                    <div class="mt-3 text-sm">
                        <span class="font-semibold">Interests:</span>
                        {{ implode(', ', $donor->interests ?? []) ?: '-' }}
                    </div>
                    <div class="mt-1 text-sm">
                        <span class="font-semibold">Campaign interests:</span>
                        {{ implode(', ', $donor->preference?->campaign_interests ?? []) ?: '-' }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded shadow-sm p-6">
                    <h3 class="font-semibold text-lg">Past Donations</h3>
                    <div class="mt-3 space-y-2 text-sm">
                        @forelse($donor->donations as $donation)
                            <div class="border p-3 rounded">
                                <div><strong>{{ $donation->amount_original }} {{ $donation->currency }}</strong> ({{ $donation->payment_status }})</div>
                                <div>Type: {{ $donation->donation_type }} | Date: {{ optional($donation->donated_at)->format('Y-m-d') ?? '-' }}</div>
                            </div>
                        @empty
                            <p class="text-gray-500">No donation history.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded shadow-sm p-6">
                    <h3 class="font-semibold text-lg">Communication Logs</h3>
                    <div class="mt-3 space-y-2 text-sm">
                        @forelse($donor->communicationLogs as $log)
                            <div class="border p-3 rounded">
                                <div><strong>{{ strtoupper($log->channel) }}</strong> - {{ $log->message_type }}</div>
                                <div>Status: {{ $log->status }} | Sent: {{ optional($log->sent_at)->format('Y-m-d H:i') ?? '-' }}</div>
                                <div>{{ $log->subject ?? 'No subject' }}</div>
                            </div>
                        @empty
                            <p class="text-gray-500">No communication logs.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white rounded shadow-sm p-6">
                <h3 class="font-semibold text-lg">Profile Update History</h3>
                <div class="mt-3 space-y-2 text-sm">
                    @forelse($donor->profileHistories as $history)
                        <div class="border p-3 rounded">
                            <div class="font-semibold">{{ $history->created_at->format('Y-m-d H:i') }} by {{ $history->updater?->name ?? 'System' }}</div>
                            <pre class="mt-2 text-xs bg-gray-50 p-2 rounded overflow-auto">{{ json_encode($history->changes, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    @empty
                        <p class="text-gray-500">No profile changes tracked yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
