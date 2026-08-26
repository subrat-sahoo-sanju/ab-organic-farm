<?php

namespace App\Models;

use App\Enums\DeliveryAssignmentStatus;
use Illuminate\Database\Eloquent\Model;

class DeliveryPerson extends Model
{
    protected $table = 'delivery_persons';

    protected $fillable = [
        'user_id', 'employee_code', 'vehicle_type', 'vehicle_number', 'license_plate',
        'delivery_areas', 'is_available', 'joined_on',
    ];

    protected function casts(): array
    {
        return [
            'joined_on' => 'date',
            'is_available' => 'boolean',
            'delivery_areas' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignments()
    {
        return $this->hasMany(DeliveryAssignment::class);
    }

    public function openAssignments()
    {
        return $this->hasMany(DeliveryAssignment::class)
            ->whereIn('status', ['assigned', 'picked_up', 'out_for_delivery']);
    }

    public function codStats(): array
    {
        $payments = \App\Models\Payment::where('method', 'cod')
            ->whereHas('order', fn ($q) => $q->where('assigned_to', $this->id));

        return [
            'pending_amount' => (float) $payments->where('status', 'pending')->sum('amount'),
            'collected_amount' => (float) $payments->where('status', 'collected')->sum('amount'),
            'pending_count' => (int) $payments->where('status', 'pending')->count(),
            'collected_count' => (int) $payments->where('status', 'collected')->count(),
        ];
    }

    public function stats(): array
    {
        $all = $this->assignments();

        return [
            'total_deliveries' => (int) $all->count(),
            'successful' => (int) $all->where('status', DeliveryAssignmentStatus::Delivered)->count(),
            'failed' => (int) $all->where('status', DeliveryAssignmentStatus::Failed)->count(),
            'cod_collected' => (float) \App\Models\Payment::where('method', 'cod')
                ->where('status', 'collected')
                ->whereHas('order', fn ($q) => $q->where('assigned_to', $this->id))
                ->sum('amount'),
            'today_deliveries' => (int) $all->where('status', DeliveryAssignmentStatus::Delivered)
                ->whereDate('delivered_at', now())->count(),
        ];
    }
}
