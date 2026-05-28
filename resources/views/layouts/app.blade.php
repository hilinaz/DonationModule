<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Donation Module') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="antialiased bg-gray-50 text-gray-800 min-h-screen text-[17px]">

        <div class="flex min-h-screen" x-data="{ sidebarOpen: false }">

            <!-- ===================== SIDEBAR ===================== -->
            <!-- Mobile overlay -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false"
                 class="fixed inset-0 bg-black/50 z-20 lg:hidden" x-transition.opacity></div>

            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                   class="fixed inset-y-0 left-0 z-30 w-88 bg-[#1a2035] flex flex-col transition-transform duration-300 lg:translate-x-0 lg:static lg:z-auto shrink-0">

                <!-- Brand -->
                <div class="flex items-center gap-3.5 px-8 py-7">
                    <div class="h-11 w-11 rounded-xl bg-teal-500 flex items-center justify-center shrink-0">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <span class="text-white font-bold text-[1.65rem] tracking-tight">Donation Module</span>
                </div>

                <!-- Nav -->
                <nav class="flex-1 px-6 pb-6 overflow-y-auto">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-widest px-3 mb-3 mt-2">Menu</p>

                    <!-- Dashboard / Portal -->
                    <a href="{{ Auth::user()->hasRole('Donor') ? route('donor.portal') : route('dashboard') }}"
                       class="flex items-center gap-4 px-4 py-4 rounded-lg text-[17px] font-medium mb-1.5 transition-all
                              {{ request()->routeIs('dashboard') || request()->routeIs('donor.portal') ? 'bg-teal-500/20 text-teal-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>

                    @role('Donor')
                    <!-- Donor-only: Campaigns (read-only view via portal) -->
                    <a href="{{ route('donor.portal') }}"
                       class="flex items-center gap-4 px-4 py-4 rounded-lg text-[17px] font-medium mb-1.5 transition-all text-slate-400 hover:text-white hover:bg-white/5">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        My Donations
                    </a>
                    @endrole

                    @hasanyrole('Admin|Fundraising Manager|Finance|Marketing|Auditor')
                    <!-- Staff-only nav items -->
                    <a href="{{ route('donors.index') }}"
                       class="flex items-center gap-4 px-4 py-4 rounded-lg text-[17px] font-medium mb-1.5 transition-all
                              {{ request()->routeIs('donors.*') ? 'bg-teal-500/20 text-teal-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Donors
                    </a>
                    @endhasanyrole

                    @hasanyrole('Fundraising Manager|Admin')
                    <a href="{{ route('campaigns.index') }}"
                       class="flex items-center gap-4 px-4 py-4 rounded-lg text-[17px] font-medium mb-1.5 transition-all
                              {{ request()->routeIs('campaigns.*') ? 'bg-teal-500/20 text-teal-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                        Campaigns
                    </a>
                    @endhasanyrole

                    @hasanyrole('Admin|Fundraising Manager|Finance')
                    <a href="{{ route('donations.index') }}"
                       class="flex items-center gap-4 px-4 py-4 rounded-lg text-[17px] font-medium mb-1.5 transition-all
                              {{ request()->routeIs('donations.*') ? 'bg-teal-500/20 text-teal-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Donations
                    </a>
                    @endhasanyrole

                    @hasanyrole('Admin|Fundraising Manager')
                    <a href="{{ route('pledges.index') }}"
                       class="flex items-center gap-4 px-4 py-4 rounded-lg text-[17px] font-medium mb-1.5 transition-all
                              {{ request()->routeIs('pledges.*') ? 'bg-teal-500/20 text-teal-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Pledges
                    </a>
                    @endhasanyrole

                    @hasanyrole('Admin|Finance|Auditor|Fundraising Manager')
                    <div x-data="{ reportsOpen: {{ request()->routeIs('reports.*') ? 'true' : 'false' }} }">
                        <button @click="reportsOpen = !reportsOpen"
                                class="w-full flex items-center gap-4 px-4 py-4 rounded-lg text-[17px] font-medium mb-1.5 transition-all
                                       {{ request()->routeIs('reports.*') ? 'bg-teal-500/20 text-teal-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span class="flex-1 text-left">Reports</span>
                            <svg class="h-3.5 w-3.5 transition-transform" :class="reportsOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="reportsOpen" x-transition class="ml-7 space-y-0.5 mt-0.5">
                            <a href="{{ route('reports.donations') }}"
                               class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium transition-all
                                      {{ request()->routeIs('reports.donations') ? 'text-teal-400 bg-teal-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                Donation Report
                            </a>
                            <a href="{{ route('reports.donors') }}"
                               class="flex items-center gap-2 px-3 py-2.5 rounded-lg text-sm font-medium transition-all
                                      {{ request()->routeIs('reports.donors') ? 'text-teal-400 bg-teal-500/10' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                Donor Analytics
                            </a>
                        </div>
                    </div>
                    @endhasanyrole

                    @role('Admin')
                    <a href="{{ route('staff.index') }}"
                       class="flex items-center gap-4 px-4 py-4 rounded-lg text-[17px] font-medium mb-1.5 transition-all
                              {{ request()->routeIs('staff.*') ? 'bg-teal-500/20 text-teal-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Staff
                    </a>
                    @endrole

                    <div class="border-t border-white/5 my-4"></div>
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-widest px-3 mb-3">Account</p>

                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-4 px-4 py-4 rounded-lg text-[17px] font-medium mb-1.5 transition-all text-slate-400 hover:text-white hover:bg-white/5">
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-4 px-4 py-4 rounded-lg text-[17px] font-medium transition-all text-slate-400 hover:text-red-400 hover:bg-red-500/10">
                            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Sign Out
                        </button>
                    </form>
                </nav>
            </aside>

            <!-- ===================== MAIN ===================== -->
            <div class="flex-1 flex flex-col min-w-0">

                <!-- Top Bar -->
                <header class="sticky top-0 z-10 bg-white border-b border-gray-200 px-5 sm:px-8 h-[72px] flex items-center justify-between gap-4 shrink-0">

                    <!-- Left: hamburger + page title -->
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        @isset($header)
                            <div>{{ $header }}</div>
                        @endisset
                    </div>

                    <!-- Right: actions + user -->
                    <div class="flex items-center gap-2 sm:gap-3">

                        <!-- Notification bell -->
                        @php $unreadCount = Auth::user()->unreadNotifications()->count(); @endphp
                        @role('Donor')
                        <a href="{{ route('donor.portal') }}#notifications"
                           class="relative p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors"
                           title="Notifications">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if($unreadCount > 0)
                                <span class="absolute top-1 right-1 h-4 w-4 rounded-full bg-red-500 text-white text-[9px] font-bold flex items-center justify-center leading-none">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                            @endif
                        </a>
                        @endrole
                        @hasanyrole('Admin|Fundraising Manager|Finance|Marketing|Auditor')
                        <div class="relative" x-data="{ notifOpen: false }">
                            <button @click="notifOpen = !notifOpen"
                                    class="relative p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors"
                                    title="Notifications">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if($unreadCount > 0)
                                    <span class="absolute top-1 right-1 h-4 w-4 rounded-full bg-red-500 text-white text-[9px] font-bold flex items-center justify-center leading-none">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </button>
                            <div x-show="notifOpen" @click.away="notifOpen = false" x-transition
                                 class="absolute right-0 top-12 w-80 bg-white border border-gray-200 rounded-xl shadow-xl z-50 overflow-hidden">
                                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-semibold text-gray-800">Notifications</p>
                                    @if($unreadCount > 0)
                                        <form method="POST" action="{{ route('notifications.read') }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-xs font-semibold text-teal-600 hover:text-teal-700">Mark all read</button>
                                        </form>
                                    @endif
                                </div>
                                @php $recentNotifs = Auth::user()->notifications()->latest()->limit(5)->get(); @endphp
                                @if($recentNotifs->isEmpty())
                                    <div class="px-4 py-6 text-center">
                                        <p class="text-xs text-gray-400">No notifications yet.</p>
                                    </div>
                                @else
                                    <div class="divide-y divide-gray-50 max-h-72 overflow-y-auto">
                                        @foreach($recentNotifs as $notif)
                                            @php $nd = $notif->data; $isUnread = is_null($notif->read_at); @endphp
                                            <a href="{{ $nd['action_url'] ?? '#' }}"
                                               class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors {{ $isUnread ? 'bg-teal-50/40' : '' }}">
                                                <span class="mt-1.5 h-2 w-2 rounded-full shrink-0 {{ $isUnread ? 'bg-teal-500' : 'bg-gray-200' }}"></span>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-xs font-semibold text-gray-800 truncate">{{ $nd['title'] ?? 'Notification' }}</p>
                                                    <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $nd['message'] ?? '' }}</p>
                                                    <p class="text-[10px] text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endhasanyrole

                        <!-- User avatar + dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open"
                                    class="flex items-center gap-2.5 pl-1 pr-3 py-1.5 rounded-xl hover:bg-gray-100 transition-colors">
                                <div class="h-8 w-8 rounded-full bg-teal-500 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <div class="hidden sm:block text-left">
                                    <p class="text-sm font-semibold text-gray-800 leading-none">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5 leading-none">
                                        {{ Auth::user()->getRoleNames()->first() ?? 'User' }}
                                    </p>
                                </div>
                                <svg class="h-4 w-4 text-gray-400 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-transition
                                 class="absolute right-0 top-12 w-48 bg-white border border-gray-200 rounded-xl shadow-lg py-1.5 z-50">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    Profile
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="dashboard-readable flex-1 p-5 sm:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
