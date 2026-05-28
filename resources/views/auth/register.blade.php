<x-guest-layout>

    <div class="mb-7">
        <h2 class="text-2xl font-bold text-white">Create account</h2>
        <p class="text-sm text-gray-400 mt-1">Fill in your details to get started.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-gray-300 mb-1.5">Full name</label>
            <input id="name"
                   type="text"
                   name="name"
                   value="{{ old('name') }}"
                   required autofocus autocomplete="name"
                   placeholder="John Doe"
                   class="w-full bg-gray-800 border border-gray-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-white rounded-xl px-4 py-2.5 text-sm placeholder-gray-600 outline-none transition-all" />
            @error('name')
                <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Email address</label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required autocomplete="username"
                   placeholder="you@example.com"
                   class="w-full bg-gray-800 border border-gray-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-white rounded-xl px-4 py-2.5 text-sm placeholder-gray-600 outline-none transition-all" />
            @error('email')
                <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">Password</label>
            <input id="password"
                   type="password"
                   name="password"
                   required autocomplete="new-password"
                   placeholder="••••••••"
                   class="w-full bg-gray-800 border border-gray-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-white rounded-xl px-4 py-2.5 text-sm placeholder-gray-600 outline-none transition-all" />
            @error('password')
                <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1.5">Confirm password</label>
            <input id="password_confirmation"
                   type="password"
                   name="password_confirmation"
                   required autocomplete="new-password"
                   placeholder="••••••••"
                   class="w-full bg-gray-800 border border-gray-700 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 text-white rounded-xl px-4 py-2.5 text-sm placeholder-gray-600 outline-none transition-all" />
            @error('password_confirmation')
                <p class="text-xs text-red-400 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full py-2.5 bg-teal-500 hover:bg-teal-600 active:scale-[0.98] text-white font-semibold rounded-xl shadow-lg shadow-teal-500/20 transition-all text-sm mt-2">
            Create Account
        </button>
    </form>

    <div class="mt-6 pt-6 border-t border-gray-800 text-center">
        <p class="text-sm text-gray-500">
            Already have an account?
            <a href="{{ route('login') }}" class="text-teal-400 hover:text-teal-300 font-semibold ml-1 transition-colors">Sign in</a>
        </p>
    </div>

</x-guest-layout>
