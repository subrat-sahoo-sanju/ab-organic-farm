<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryArea extends Model
{
    protected $fillable = [
        'pincode', 'city', 'state', 'area',
        'is_serviceable', 'delivery_charge', 'eta_days', 'cod_available',
    ];

    protected function casts(): array
    {
        return [
            'is_serviceable' => 'boolean',
            'cod_available' => 'boolean',
            'delivery_charge' => 'decimal:2',
        ];
    }
}
