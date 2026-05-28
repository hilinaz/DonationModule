<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-base font-semibold text-gray-800">Staff Management</h1>
                <p class="text-xs text-gray-400 mt-0.5">
                    <a href="{{ route('dashboard') }}" class="hover:text-teal-500 transition-colors">Dashboard</a>
                    <span class="mx-1.5 text-gray-300">&rsaquo;</span>
                    <span class="text-gray-600">Staff</span>
                </p>
            </div>
            <a href="{{ route('staff.create') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-teal-500 hover:bg-teal-600 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm">
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Add Staff Member
            </a>
        </div>
    </x-slot>

    <div class="space-y-4">

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Member</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Joined</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($staffUsers as $member)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-full bg-teal-50 flex items-center justify-center text-teal-600 text-xs font-bold shrink-0">
                                            {{ strtoupper(substr($member->name, 0, 2)) }}
                                        </div>
                                        <p class="font-medium text-gray-800">{{ $member->name }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-gray-500 text-xs">{{ $member->email }}</td>
                                <td class="px-4 py-3.5">
                                    @php
                                        $roleColors = ['Admin'=>'bg-red-100 text-red-700','Fundraising Manager'=>'bg-teal-100 text-teal-700','Finance'=>'bg-blue-100 text-blue-700','Marketing'=>'bg-pink-100 text-pink-700','Auditor'=>'bg-amber-100 text-amber-700'];
                                        $role = $member->getRoleNames()->first();
                                    @endphp
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $roleColors[$role] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $role }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-gray-400 text-xs">{{ $member->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-3.5 text-right">
                                    @if($member->id !== auth()->id())
                                        <form method="POST" action="{{ route('staff.destroy', $member) }}"
                                              onsubmit="return confirm('Delete this staff member?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700 transition-colors">Delete</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400">You</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400">No staff members found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
