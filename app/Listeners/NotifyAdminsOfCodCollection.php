<?php

namespace App\Listeners;

use App\Events\CodCollected;
use App\Models\User;
use App\Enums\UserRole;
use App\Notifications\Admin\CodCollectedAlert;

class NotifyAdminsOfCodCollection
{
    public function handle(CodCollected $event): void
    {
        User::whereHas('roles', fn ($q) => $q->whereIn('name', [UserRole::SuperAdmin->value, UserRole::Admin->value, UserRole::DeliveryManager->value]))
            ->get()
            ->each(fn ($admin) => $admin->notify(new CodCollectedAlert($event->collection)));
    }
}
