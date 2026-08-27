<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $table = 'admin_notifications';

    protected $fillable = [
        'type', 'title', 'message', 'icon', 'color', 'order_id', 'meta', 'read_at',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'read_at' => 'datetime',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // ---------- Scopes / Helpers ----------

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeForNow($query)
    {
        return $query->latest()->limit(30);
    }

    public function markRead(): void
    {
        $this->update(['read_at' => now()]);
    }

    /** Create a notification from an order (kept here for convenience). */
    public static function orderPlaced(\App\Models\Order $order): self
    {
        $count = $order->items()->count();
        return static::create([
            'type' => 'order',
            'title' => 'New order '.$order->order_number,
            'message' => $order->user?->name.' placed an order of ₹'.number_format($order->grand_total).' ('.$count.' item'.($count !== 1 ? 's' : '').')',
            'icon' => 'shopping-bag',
            'color' => 'forest',
            'order_id' => $order->id,
            'meta' => [
                'grand_total' => $order->grand_total,
                'customer' => $order->user?->name,
                'order_number' => $order->order_number,
                'items' => $count,
                'phone' => $order->ship_phone,
                'city' => $order->ship_city,
                'placed_at' => $order->placed_at?->toIso8601String(),
            ],
        ]);
    }
}
