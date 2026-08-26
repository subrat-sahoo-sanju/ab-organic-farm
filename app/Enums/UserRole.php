<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case DeliveryManager = 'delivery_manager';
    case DeliveryPerson = 'delivery_person';
    case Customer = 'customer';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::Admin => 'Admin',
            self::DeliveryManager => 'Delivery Manager',
            self::DeliveryPerson => 'Delivery Person',
            self::Customer => 'Customer',
        };
    }

    /** Roles that may access the admin panel. */
    public static function staffRoles(): array
    {
        return [self::SuperAdmin->value, self::Admin->value, self::DeliveryManager->value];
    }
}
