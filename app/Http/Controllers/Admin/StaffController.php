<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class StaffController extends Controller
{
    public function index()
    {
        return view('admin.staff.index', [
            'staff' => User::whereHas('roles', fn ($q) => $q->where('name', '!=', 'customer'))
                ->with('roles')
                ->latest('created_at')
                ->paginate(20),
            'roles' => Role::all(),
        ]);
    }

    public function store(\Illuminate\Http\Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'digits:10', 'unique:users,phone'],
            'password' => ['required', 'min:8', 'confirmed'],
            'role' => ['required', 'exists:roles,name'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => bcrypt($data['password']),
            'is_active' => $data['is_active'] ?? true,
        ]);

        $user->roles()->attach(Role::where('name', $data['role'])->first());

        return back()->with('success', 'Staff member created.');
    }

    public function update(\Illuminate\Http\Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:120'],
            'email' => ['sometimes', 'required', 'email', 'unique:users,email,'.$user->id],
            'phone' => ['sometimes', 'required', 'digits:10', 'unique:users,phone,'.$user->id],
            'password' => ['nullable', 'min:8'],
            'role' => ['required', 'exists:roles,name'],
        ]);

        $user->update(collect($data)->except('role')->filter()->all());

        $user->roles()->sync([Role::where('name', $data['role'])->first()->id]);

        return back()->with('success', 'Staff member updated.');
    }

    public function toggle(User $user): RedirectResponse
    {
        $user->update(['is_active' => !$user->is_active]);

        return back()->with('success', $user->is_active ? 'Staff member activated.' : 'Staff member deactivated.');
    }
}
