<x-guest-layout>

    <div class="mb-7">
        <h2 class="text-2xl font-bold text-white">Sign in</h2>
        <p class="text-sm text-gray-400 mt-1">Welcome back. Enter your credentials to continue.</p>
    </div>

    @auth
        <div class="mb-5 bg-teal-500/10 border border-teal-500/20 text-teal-300 p-4 rounded-xl text-sm flex justify-between items-center gap-3">
            <div>
                <span class="block font-semibold text-white">Already signed in</span>
                <span class="text-xs text-gray-400">{{ auth()->user()->email }}</span>
            </div>
            <a href="{{ route('dashboard') }}" class="shrink-0 px-3 py-1.5 bg-teal-500 hover:bg-teal-600 text-white rounded-lg font-semibold text-xs transition-colors">
                Dashboard
            </a>
        </div>
    @endauth

    @if (session('status'))
        <div class="mb-5 bg-teal-500/10 border border-teal-500/20 text-teal-400 p-3 rounded-xl text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Email address</label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required autofocus autocomplete="username"
                   placeholder="you@example.com"
                   class="w-full bg-gray-800 border border-gray-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-white rounded-xl px-4 py-2.5 text-sm placeholder-gray-600 outline-none transition-all" />
            @error('email')
                <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-teal-400 hover:text-teal-300 transition-colors">Forgot password?</a>
                @endif
            </div>
            <input id="password"
                   type="password"
                   name="password"
                   required autocomplete="current-password"
                   placeholder="••••••••"
                   class="w-full bg-gray-800 border border-gray-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-white rounded-xl px-4 py-2.5 text-sm placeholder-gray-600 outline-none transition-all" />
            @error('password')
                <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-2.5">
            <input id="remember_me" type="checkbox" name="remember"
                   class="h-4 w-4 rounded border-gray-700 bg-gray-800 text-teal-500 focus:ring-teal-500/30 focus:ring-offset-gray-900" />
            <label for="remember_me" class="text-sm text-gray-400 cursor-pointer select-none">Keep me signed in</label>
        </div>

        <button type="submit"
                class="w-full py-2.5 bg-teal-500 hover:bg-teal-600 active:scale-[0.98] text-white font-semibold rounded-xl shadow-lg shadow-teal-500/20 transition-all text-sm mt-1">
            Sign In
        </button>
    </form>

    <div class="mt-6 pt-6 border-t border-gray-800 text-center">
        <p class="text-sm text-gray-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-teal-400 hover:text-teal-300 font-semibold ml-1 transition-colors">Create one</a>
        </p>
    </div>

</x-guest-layout>
