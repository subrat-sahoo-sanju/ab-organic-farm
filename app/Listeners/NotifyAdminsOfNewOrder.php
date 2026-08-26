<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Notifications\Admin\NewOrderAlert;
use App\Models\User;
use App\Enums\UserRole;

class NotifyAdminsOfNewOrder
{
    public function handle(OrderPlaced $event): void
    {
        User::whereHas('roles', fn ($q) => $q->whereIn('name', [UserRole::SuperAdmin->value, UserRole::Admin->value, UserRole::DeliveryManager->value]))
            ->get()
            ->each(fn ($admin) => $admin->notify(new NewOrderAlert($event->order)));
    }
}
