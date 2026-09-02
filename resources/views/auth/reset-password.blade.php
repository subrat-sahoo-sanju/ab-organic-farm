<x-guest-layout title="Reset password">
    <h1 class="font-display text-2xl font-bold">Choose a new password</h1>

    <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <x-ui.input label="Email" name="email" type="email" value="{{ old('email', $email) }}" :error="$errors->first('email')" required/>
        <x-ui.input label="New password" name="password" type="password" :error="$errors->first('password')" required/>
        <x-ui.input label="Confirm new password" name="password_confirmation" type="password"/>

        <x-ui.button type="submit" class="w-full">Reset password</x-ui.button>
    </form>
</x-guest-layout>
