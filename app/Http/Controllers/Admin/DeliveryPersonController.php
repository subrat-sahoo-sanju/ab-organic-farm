<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryPerson;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DeliveryPersonController extends Controller
{
    public function index(): View
    {
        return view('admin.delivery.persons', [
            'persons' => DeliveryPerson::with('user:id,name,email,phone')
                ->when(request('active'), fn ($q) => $q->where('is_available', true))
                ->get(),
        ]);
    }

    public function store(\Illuminate\Http\Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'digits:10', 'unique:users,phone'],
            'vehicle_type' => ['nullable', 'string', 'max:64'],
            'license_plate' => ['nullable', 'string', 'max:32'],
            'delivery_areas' => ['nullable', 'array'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => bcrypt('aborganic'),
            'phone_verified_at' => now(),
        ]);

        $user->roles()->attach(\App\Models\Role::where('name', 'delivery_person')->first());

        DeliveryPerson::create([
            'user_id' => $user->id,
            'vehicle_type' => $data['vehicle_type'] ?? null,
            'license_plate' => $data['license_plate'] ?? null,
            'delivery_areas' => $data['delivery_areas'] ?? [],
            'is_available' => true,
        ]);

        return back()->with('success', 'Delivery person created. Default password: aborganic');
    }

    public function toggle(DeliveryPerson $person): RedirectResponse
    {
        $person->update(['is_available' => !$person->is_available]);

        return back()->with('success', $person->is_available ? 'Now available.' : 'Set unavailable.');
    }
}
