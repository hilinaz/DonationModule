<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-white tracking-tight">Reset Password</h2>
        <p class="text-sm text-slate-400 mt-1.5">
            {{ __('No problem. Enter your registered email address and we will send you a secure password reset link.') }}
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-3 rounded-lg text-sm">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-300">{{ __('Email Address') }}</label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   placeholder="name@example.com"
                   class="block mt-1.5 w-full bg-slate-950/80 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-white rounded-lg px-4 py-2.5 transition-all text-sm placeholder-slate-600" />
            @if ($errors->has('email'))
                <p class="text-xs text-red-400 mt-1.5 font-medium">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" 
                    class="w-full py-3 bg-gradient-to-r from-indigo-500 to-fuchsia-600 hover:from-indigo-600 hover:to-fuchsia-700 text-white font-bold rounded-lg shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/30 transition-all text-sm tracking-wide active:scale-[0.98]">
                {{ __('Email Reset Link') }}
            </button>
        </div>
    </form>

    <div class="mt-6 pt-5 border-t border-slate-800/80 text-center">
        <p class="text-xs text-slate-500">
            {{ __('Remembered your password?') }}
            <a href="{{ route('login') }}" class="font-bold text-indigo-400 hover:text-indigo-300 hover:underline transition-all ms-1">
                {{ __('Sign In') }}
            </a>
        </p>
    </div>
</x-guest-layout>
