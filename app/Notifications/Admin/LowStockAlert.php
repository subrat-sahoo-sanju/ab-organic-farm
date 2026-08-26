<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LowStockAlert extends Notification
{
    use Queueable;

    public function __construct(public $inventory) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $variant = $this->inventory->variant->load('product');

        return [
            'title' => 'Low stock',
            'message' => sprintf('%s (%s): only %d units left', $variant->product->name, $variant->name, $this->inventory->available()),
            'url' => \Illuminate\Support\Facades\Route::has('admin.inventory.index') ? route('admin.inventory.index') : '#',
            'type' => 'stock',
        ];
    }
}
