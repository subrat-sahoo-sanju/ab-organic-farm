<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Notifications\Customer\OrderStatusUpdate;

class NotifyCustomerOfStatusChange
{
    public function handle(OrderStatusChanged $event): void
    {
        $event->order->user?->notify(new OrderStatusUpdate($event->order, $event->to));
    }
}
