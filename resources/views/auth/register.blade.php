<x-guest-layout title="Create account">
    <h1 class="font-display text-2xl font-bold text-charcoal-800">Create your account</h1>
    <p class="mt-1 text-sm text-charcoal-600/80">Fresh organic products, one signup away.</p>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
        @csrf

        <x-ui.input label="Full name" name="name" value="{{ old('name') }}" :error="$errors->first('name')" required/>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-ui.input label="Email" name="email" type="email" value="{{ old('email') }}" :error="$errors->first('email')" required/>
            <x-ui.input label="Mobile number" name="phone" inputmode="numeric" value="{{ old('phone') }}"
                :error="$errors->first('phone')" required hint="10 digits, no country code"/>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <x-ui.input label="Password" name="password" type="password" :error="$errors->first('password')" required/>
            <x-ui.input label="Confirm password" name="password_confirmation" type="password"/>
        </div>

        <details class="text-sm">
            <summary class="cursor-pointer font-semibold text-charcoal-600 select-none">Optional details</summary>
            <div class="grid grid-cols-2 gap-4 mt-3">
                <x-ui.input label="Date of birth" name="dob" type="date" value="{{ old('dob') }}"/>
                <div>
                    <label for="gender" class="block text-sm font-semibold text-charcoal-700 mb-1.5">Gender</label>
                    <select id="gender" name="gender" class="w-full h-11 px-3 rounded-xl border border-cream-200 bg-white text-sm focus:border-forest-500 focus:ring-2 focus:ring-forest-500/30">
                        @foreach(['' => 'Prefer not to say', 'female' => 'Female', 'male' => 'Male', 'other' => 'Other'] as $v => $l)
                            <option value="{{ $v }}" @selected(old('gender') === $v)>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </details>

        <x-ui.button type="submit" class="w-full">Create account</x-ui.button>
    </form>

    <p class="mt-6 text-sm text-center text-charcoal-600/80">
        Already have an account?
        <a href="{{ route('login') }}" class="font-semibold text-forest-700 hover:text-forest-500">Login</a>
    </p>
</x-guest-layout>
