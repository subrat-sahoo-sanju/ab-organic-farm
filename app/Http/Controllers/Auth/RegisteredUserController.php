<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(protected CartService $carts) {}

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:190', 'unique:users,email'],
            'phone' => ['required', 'digits:10', 'unique:users,phone'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'dob' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female,other'],
        ]);

        $user = User::create([
            ...$data,
            'uuid' => (string) Str::uuid(),
            'password' => Hash::make($data['password']),
        ]);

        $user->roles()->attach(Role::where('name', UserRole::Customer->value)->value('id'));

        Auth::login($user);
        $request->session()->regenerate();
        $this->carts->mergeGuestCart();

        return redirect()->route('shop.index')->with('success', 'Welcome to AB Organic Farm! Your account is ready.');
    }
}
