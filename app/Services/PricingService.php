<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Collection;

/**
 * Single source of truth for ALL money math.
 * Works in integer paise internally; returns floats at the boundary only.
 */
class PricingService
{
    public function __construct(
        protected DiscountService $discounts,
        protected SettingsService $settings,
    ) {}

    /**
     * Compute the full cart breakdown from database prices.
     *
     * @return array{
     *   lines: Collection, subtotal: float, product_discount: float,
     *   coupon_discount: float, delivery_charge: float, grand_total: float
     * }
     */
    public function forCart(Cart $cart): array
    {
        $items = $cart->items()->with([
            'product.category', 'variant.inventory',
        ])->get();

        $lines = collect();
        $subtotalP = 0;      // sum of effective line prices (after auto discount)
        $listTotalP = 0;     // sum of regular/list prices
        $productDiscountP = 0;

        foreach ($items as $item) {
            if (! $item->variant?->is_active || ! $item->product) {
                continue; // silently skip dead rows; CartService cleans these up
            }

            $unitList = (int) round(((float) ($item->variant->sale_price ?? $item->variant->price)) * 100);

            // Best automatic discount for this product (product-level or category-level)
            $auto = $this->discounts->bestAutoDiscountFor($item->product, $unitList);
            $unitEffective = $unitList - $auto['per_unit'];

            // Bulk tier discount (aggregated qty of same product across cart)
            $sameQty = $items->where('product_id', $item->product_id)->sum('quantity');
            $bulkPercent = $this->discounts->bulkTierPercent($item->product, (int) $sameQty);
            $bulkPerUnit = (int) round($unitEffective * $bulkPercent / 100);
            $unitEffective -= $bulkPerUnit;

            $lineTotalP = $unitEffective * $item->quantity;
            $lineListP = $unitList * $item->quantity;

            $subtotalP += $lineTotalP;
            $listTotalP += $lineListP;
            $productDiscountP += ($lineListP - $lineTotalP);

            $lines->push([
                'item' => $item,
                'unit_list' => $this->p($unitList),
                'unit_effective' => $this->p($unitEffective),
                'line_total' => $this->p($lineTotalP),
                'discount_per_unit' => $this->p($auto['per_unit'] + $bulkPerUnit),
                'has_bulk' => $bulkPerUnit > 0,
                'in_stock' => ($item->variant->inventory?->available() ?? 0) >= $item->quantity,
            ]);
        }

        // Coupon on top of subtotal (never exceeds it)
        [$couponDiscountP] = $cart->coupon && $cart->coupon->is_active
            ? $this->discounts->couponDiscountFor($cart->coupon, $this->p($subtotalP), auth()->user(), $lines)
            : [0];
        $couponDiscountP = min($couponDiscountP, $subtotalP);

        $afterDiscountP = $subtotalP - $couponDiscountP;
        $deliveryP = $items->isEmpty() ? 0 : $this->deliveryChargeP($afterDiscountP);
        $totalP = $afterDiscountP + $deliveryP;

        return [
            'lines' => $lines,
            'subtotal' => $this->p($subtotalP),
            'product_discount' => $this->p(max(0, $productDiscountP)),
            'coupon_discount' => $this->p($couponDiscountP),
            'delivery_charge' => $this->p($deliveryP),
            'grand_total' => $this->p($totalP),
        ];
    }

    /** Delivery charge in paise based on configurable threshold. */
    public function deliveryChargeP(int $orderAmountP): int
    {
        $standard = (int) round((float) setting('delivery.standard_charge', 49) * 100);
        $freeAbove = (int) round((float) setting('delivery.free_above', 999) * 100);

        return $orderAmountP >= $freeAbove ? 0 : $standard;
    }

    /** paise -> rupee float */
    protected function p(int $paise): float
    {
        return round($paise / 100, 2);
    }
}
