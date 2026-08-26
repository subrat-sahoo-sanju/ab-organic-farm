<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [UserRole::SuperAdmin, 'Super Admin'],
            [UserRole::Admin, 'Admin'],
            [UserRole::DeliveryManager, 'Delivery Manager'],
            [UserRole::DeliveryPerson, 'Delivery Person'],
            [UserRole::Customer, 'Customer'],
        ];

        foreach ($roles as [$enum, $label]) {
            Role::firstOrCreate(['name' => $enum->value], ['label' => $label]);
        }

        // ---- Staff accounts (documented credentials — DEV ONLY) ----
        $staff = [
            ['Subrat Admin', 'admin@verdura.test', UserRole::SuperAdmin],
            ['Priya Manager', 'manager@verdura.test', UserRole::Admin],
            ['Rakesh Das', 'delivery.manager@verdura.test', UserRole::DeliveryManager],
            ['Dillip Sahu', 'dillip@verdura.test', UserRole::DeliveryPerson],
            ['Manoj Behera', 'manoj@verdura.test', UserRole::DeliveryPerson],
            ['Sanjay Malik', 'sanjay@verdura.test', UserRole::DeliveryPerson],
        ];

        foreach ($staff as [$name, $email, $role]) {
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'uuid' => (string) \Illuminate\Support\Str::uuid(),
                    'name' => $name,
                    'phone' => null,
                    'password' => Hash::make('password'),
                    'is_active' => true,
                ]
            );
            $user->roles()->syncWithoutDetaching([Role::where('name', $role->value)->value('id')]);
        }

        // Delivery-person profiles
        $profiles = [
            ['dillip@verdura.test', 'DP-001', 'OD-05-AB-1234'],
            ['manoj@verdura.test', 'DP-002', 'OD-05-BC-5678'],
            ['sanjay@verdura.test', 'DP-003', null],
        ];

        foreach ($profiles as [$email, $code, $vehicle]) {
            $u = User::where('email', $email)->first();
            \App\Models\DeliveryPerson::updateOrCreate(
                ['user_id' => $u->id],
                ['employee_code' => $code, 'vehicle_number' => $vehicle, 'joined_on' => now()->subMonths(6)->toDateString()]
            );
        }
    }
}
