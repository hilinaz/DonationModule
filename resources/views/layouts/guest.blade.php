<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Antigravity Charity') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="antialiased min-h-screen bg-gray-950 text-white flex items-center justify-center p-4">

        <!-- Subtle background glow -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-1/4 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-teal-500/5 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-0 left-1/4 w-[400px] h-[400px] bg-teal-600/5 rounded-full blur-[100px]"></div>
        </div>

        <div class="relative z-10 w-full max-w-md">

            <!-- Logo / Brand -->
            <div class="flex flex-col items-center mb-8">
                <div class="h-12 w-12 rounded-2xl bg-teal-500 flex items-center justify-center shadow-lg shadow-teal-500/30 mb-4">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h1 class="text-xl font-semibold text-white tracking-tight">Antigravity Charity</h1>
                <p class="text-sm text-gray-500 mt-1">Donation Management Platform</p>
            </div>

            <!-- Card -->
            <div class="bg-gray-900 border border-gray-800 rounded-2xl shadow-2xl p-8">
                {{ $slot }}
            </div>

            <!-- Footer -->
            <p class="text-center text-xs text-gray-600 mt-6">&copy; {{ date('Y') }} Antigravity Systems Inc.</p>
        </div>
    </body>
</html>
