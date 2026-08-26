<?php

namespace App\Notifications\Admin;

use App\Models\CodCollection;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CodCollectedAlert extends Notification
{
    use Queueable;

    public function __construct(public CodCollection $collection) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'COD collected',
            'message' => sprintf('₹%s collected for %s', number_format((float) $this->collection->amount, 2), $this->collection->payment->order->order_number),
            'url' => \Illuminate\Support\Facades\Route::has('admin.cod.index') ? route('admin.cod.index') : '#',
            'type' => 'cod',
        ];
    }
}
