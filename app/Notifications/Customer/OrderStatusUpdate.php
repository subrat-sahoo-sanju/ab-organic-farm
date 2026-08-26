<?php

namespace App\Notifications\Customer;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusUpdate extends Notification
{
    use Queueable;

    public function __construct(
        public Order $order,
        public OrderStatus $status,
    ) {}

    public function via($notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Order '.$this->order->order_number.' — '.$this->status->label())
            ->greeting('Hello '.$notifiable->name.',')
            ->line('Your order '.$this->order->order_number.' is now: '.$this->status->label().'.')
            ->line('Total payable on delivery: ₹'.number_format((float) $this->order->grand_total, 2))
            ->action('Track your order', \Illuminate\Support\Facades\Route::has('account.orders.show') ? route('account.orders.show', $this->order) : url('/'));
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Order '.$this->status->label(),
            'message' => $this->order->order_number.' is now '.$this->status->label().'.',
            'url' => \Illuminate\Support\Facades\Route::has('account.orders.show') ? route('account.orders.show', $this->order) : '#',
            'type' => 'order_status',
        ];
    }
}
