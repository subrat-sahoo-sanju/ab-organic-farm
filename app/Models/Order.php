<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number', 'user_id', 'status',
        'cancellation_reason', 'cancelled_by',
        'subtotal', 'product_discount', 'coupon_discount', 'delivery_charge', 'grand_total',
        'coupon_id',
        'ship_name', 'ship_phone', 'ship_house_no', 'ship_street', 'ship_area',
        'ship_landmark', 'ship_city', 'ship_state', 'ship_pincode', 'address_id',
        'payment_method',
        'placed_at', 'confirmed_at', 'delivered_at', 'cancelled_at',
        'ip_address', 'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'subtotal' => 'decimal:2',
            'product_discount' => 'decimal:2',
            'coupon_discount' => 'decimal:2',
            'delivery_charge' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'placed_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'delivered_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    /* ---------------- Relationships ---------------- */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function assignments()
    {
        return $this->hasMany(DeliveryAssignment::class);
    }

    public function activeAssignment()
    {
        return $this->hasOne(DeliveryAssignment::class)
            ->whereIn('status', ['assigned', 'picked_up', 'out_for_delivery'])
            ->latest();
    }

    public function latestAssignment()
    {
        return $this->hasOne(DeliveryAssignment::class)->latestOfMany();
    }

    /* ---------------- Helpers ---------------- */

    /** Visual timeline steps for the customer order page. */
    public function timeline(): array
    {
        $flow = [
            OrderStatus::Pending,
            OrderStatus::Confirmed,
            OrderStatus::Packed,
            OrderStatus::OutForDelivery,
            OrderStatus::Delivered,
        ];

        // Index in flow for current status (Preparing counts as Confirmed stage, Assigned as Packed stage)
        $stageAlias = [
            OrderStatus::Preparing->value => OrderStatus::Confirmed->value,
            OrderStatus::Assigned->value => OrderStatus::Packed->value,
        ];
        $current = $stageAlias[$this->status->value] ?? $this->status->value;
        $currentIndex = array_search($current, array_column($flow, 'value'), true);

        if (in_array($this->status, [OrderStatus::Cancelled, OrderStatus::FailedDelivery], true)) {
            $currentIndex = -1;
        }

        return collect($flow)->map(fn ($s) => [
            'status' => $s,
            'reached' => $currentIndex !== false && $s === $flow[$currentIndex]
                || ($currentIndex !== false && array_search($s, array_column($flow, 'value'), true) < $currentIndex),
            'is_current' => $currentIndex !== false && $s === $flow[$currentIndex],
        ])->all();
    }

    public function fullShippingAddress(): string
    {
        return collect([
            $this->ship_name,
            $this->ship_house_no,
            $this->ship_street,
            $this->ship_area,
            $this->ship_landmark,
            $this->ship_city,
            $this->ship_state.' - '.$this->ship_pincode,
        ])->filter()->implode(', ');
    }
}
