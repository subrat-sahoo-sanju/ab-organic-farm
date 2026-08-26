<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = ['product_variant_id', 'stock', 'reserved', 'low_stock_threshold'];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function available(): int
    {
        return max(0, $this->stock - $this->reserved);
    }

    public function isLow(): bool
    {
        return $this->available() <= $this->low_stock_threshold && $this->available() > 0;
    }

    public function isOutOfStock(): bool
    {
        return $this->available() <= 0;
    }
}
