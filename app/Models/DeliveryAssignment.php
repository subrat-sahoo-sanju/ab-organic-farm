<?php

namespace App\Models;

use App\Enums\DeliveryAssignmentStatus;
use Illuminate\Database\Eloquent\Model;

class DeliveryAssignment extends Model
{
    protected $fillable = [
        'order_id', 'delivery_person_id', 'assigned_by',
        'status', 'attempt_count', 'failed_reason',
        'assigned_at', 'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => DeliveryAssignmentStatus::class,
            'assigned_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function deliveryPerson()
    {
        return $this->belongsTo(DeliveryPerson::class);
    }
}
