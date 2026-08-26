<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'inventory_id', 'user_id', 'type', 'quantity', 'stock_after',
        'reason', 'reference_type', 'reference_id',
    ];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
