<x-guest-layout title="Forgot password — AB Organic Farm">
    <h1 class="font-display text-2xl font-bold">Forgot your password?</h1>
    <p class="mt-1 text-sm text-charcoal-600/80">Enter your email and we'll send you a reset link.</p>

    @if(session('success'))
        <div class="mt-4"><x-ui.alert type="success">{{ session('success') }}</x-ui.alert></div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
        @csrf
        <x-ui.input label="Email" name="email" type="email" value="{{ old('email') }}" :error="$errors->first('email')" required/>
        <x-ui.button type="submit" class="w-full">Send reset link</x-ui.button>
    </form>

    <p class="mt-6 text-sm text-center">
        <a href="{{ route('login') }}" class="font-semibold text-forest-700 hover:text-forest-500">Back to login</a>
    </p>
</x-guest-layout>
