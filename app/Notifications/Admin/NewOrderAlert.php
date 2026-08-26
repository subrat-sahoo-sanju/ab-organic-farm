<?php

namespace App\Notifications\Admin;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderAlert extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'New order received',
            'message' => sprintf('%s placed order %s (₹%s)', $this->order->user->name, $this->order->order_number, number_format((float) $this->order->grand_total, 2)),
            'url' => \Illuminate\Support\Facades\Route::has('admin.orders.show') ? route('admin.orders.show', $this->order) : '#',
            'type' => 'order',
        ];
    }
}
