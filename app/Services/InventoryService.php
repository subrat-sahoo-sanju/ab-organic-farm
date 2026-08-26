<?php

namespace App\Services;

use App\Enums\InventoryTxnType;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Adjust stock under a row lock. All stock mutations MUST go through here.
     *
     * @return Inventory refreshed model
     */
    public function adjust(Inventory $inventory, int $delta, InventoryTxnType $type, ?string $reason = null, $reference = null): Inventory
    {
        DB::transaction(function () use (&$inventory, $delta, $type, $reason, $reference) {
            $locked = Inventory::whereKey($inventory->id)->lockForUpdate()->first();

            // Reservation / release mutate `reserved`, others mutate `stock`
            if ($type === InventoryTxnType::Reservation) {
                $available = max(0, $locked->stock - $locked->reserved);
                if ($delta > 0 && $delta > $available) {
                    abort(422, 'Insufficient stock.');
                }
                $locked->reserved = max(0, $locked->reserved + $delta);
            } elseif (in_array($type, [InventoryTxnType::Release, InventoryTxnType::Cancel], true)) {
                if ($type === InventoryTxnType::Release) {
                    $locked->reserved = max(0, $locked->reserved - abs($delta));
                } else {
                    $locked->stock = max(0, $locked->stock - abs($delta));
                }
            } else {
                $locked->stock = max(0, $locked->stock + $delta);
            }

            $locked->save();
            $inventory = $locked;

            InventoryTransaction::create([
                'inventory_id' => $locked->id,
                'user_id' => auth()->id(),
                'type' => $type->value,
                'quantity' => $delta,
                'stock_after' => $locked->stock,
                'reason' => $reason,
                'reference_type' => $reference ? $reference::class : null,
                'reference_id' => $reference?->getKey(),
            ]);
        });

        return $inventory;
    }

    public function reserveForCart(Inventory $inventory, int $qty, ?string $reason = null): void
    {
        $this->adjust($inventory, $qty, InventoryTxnType::Reservation, $reason ?? 'Cart reservation');
    }

    /** Convert a reservation into a real deduction. */
    public function commitReservation(Inventory $inventory, int $qty, $order = null): void
    {
        // release reservation then deduct stock
        $this->adjust($inventory, -$qty, InventoryTxnType::Release, 'Dispatched', $order);
        $this->adjust($inventory, -$qty, InventoryTxnType::Sale, 'Sold on order dispatch', $order);
    }

    public function releaseReservation(Inventory $inventory, int $qty, ?string $reason = null, $order = null): void
    {
        $this->adjust($inventory, -$qty, InventoryTxnType::Release, $reason ?? 'Reservation released', $order);
    }

    public function restock(Inventory $inventory, int $qty, string $reason, $order = null): void
    {
        $this->adjust($inventory, $qty, InventoryTxnType::Return, $reason, $order);
    }

    public function ensureForVariant(ProductVariant $variant, int $threshold = 10): Inventory
    {
        return Inventory::firstOrCreate(
            ['product_variant_id' => $variant->id],
            ['stock' => 0, 'reserved' => 0, 'low_stock_threshold' => $threshold]
        );
    }
}
