<x-guest-layout title="Login — AB Organic Farm">
    <div class="text-center lg:text-left">
        <h1 class="font-display text-2xl font-bold text-charcoal-800">Welcome back</h1>
        <p class="mt-1 text-sm text-charcoal-600/70">Log in to continue your organic journey.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
        @csrf

        <x-ui.input label="Email or mobile" name="login" type="text" value="{{ old('login') }}"
            :error="$errors->first('login')" required autofocus
            placeholder="you@example.com or 10-digit mobile"/>

        <x-ui.input label="Password" name="password" type="password"
            :error="$errors->first('password')" required
            placeholder="Enter your password"/>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-charcoal-600 cursor-pointer">
                <input type="checkbox" name="remember"
                    class="h-4 w-4 rounded border-sage-300 text-forest-600 focus:ring-forest-500/30 accent-forest-600">
                Remember me
            </label>
            <a href="{{ route('password.request') }}"
                class="text-sm font-semibold text-forest-700 hover:text-forest-500 transition-colors">
                Forgot password?
            </a>
        </div>

        <x-ui.button type="submit" class="w-full" size="lg">Login</x-ui.button>
    </form>

    <p class="mt-6 text-sm text-center text-charcoal-600/80">
        New here?
        <a href="{{ route('register') }}" class="font-semibold text-forest-700 hover:text-forest-500 transition-colors">Create an account</a>
    </p>

    {{-- Quick login (dev) --}}
    <div class="mt-8 border-t border-sage-100 pt-6">
        <p class="text-[11px] font-semibold text-center text-charcoal-400 uppercase tracking-widest mb-3">Quick Login (Dev)</p>
        <div class="grid grid-cols-3 gap-2">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input type="hidden" name="login" value="admin@verdura.test">
                <input type="hidden" name="password" value="password">
                <button type="submit"
                    class="w-full text-xs py-2.5 px-3 rounded-xl bg-forest-600 text-white hover:bg-forest-700 font-semibold transition-all duration-200 hover:shadow-card active:scale-[.97]">
                    Super Admin
                </button>
            </form>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input type="hidden" name="login" value="dillip@verdura.test">
                <input type="hidden" name="password" value="password">
                <button type="submit"
                    class="w-full text-xs py-2.5 px-3 rounded-xl bg-amber-500 text-white hover:bg-amber-600 font-semibold transition-all duration-200 hover:shadow-card active:scale-[.97]">
                    Delivery Person
                </button>
            </form>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <input type="hidden" name="login" value="ankita@example.com">
                <input type="hidden" name="password" value="password">
                <button type="submit"
                    class="w-full text-xs py-2.5 px-3 rounded-xl bg-mint-500 text-white hover:bg-mint-400 font-semibold transition-all duration-200 hover:shadow-card active:scale-[.97]">
                    Customer
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
