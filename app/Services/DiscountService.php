<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Discount;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;

class DiscountService
{
    /**
     * Best automatic discount (percentage or fixed) applicable to a product.
     *
     * @return array{per_unit:int, name:?string} per-unit saving in paise
     */
    public function bestAutoDiscountFor(Product $product, int $unitPriceP): array
    {
        $now = now();
        $best = ['per_unit' => 0, 'name' => null];

        $discounts = Discount::query()
            ->whereIn('scope', ['product', 'category'])
            ->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now))
            ->where('type', '!=', 'bulk_tier')
            ->orderByDesc('priority')
            ->get();

        foreach ($discounts as $d) {
            if (! $this->matchesProduct($d, $product)) {
                continue;
            }

            $saving = $d->type === 'percentage'
                ? (int) round($unitPriceP * ((float) $d->value) / 100)
                : (int) round(((float) $d->value) * 100);

            if ($d->max_discount_amount) {
                $saving = min($saving, (int) round(((float) $d->max_discount_amount) * 100));
            }

            if ($saving > 0 && $saving > $best['per_unit']) {
                $best = ['per_unit' => $saving, 'name' => $d->name];
            }
        }

        return $best;
    }

    /**
     * Bulk-tier percent for aggregated quantity of one product.
     */
    public function bulkTierPercent(Product $product, int $qty): int
    {
        $now = now();

        $tier = Discount::query()
            ->where('scope', 'product')
            ->where('type', 'bulk_tier')
            ->where('is_active', true)
            ->where('discountable_type', Product::class)
            ->where('discountable_id', $product->id)
            ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now))
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now))
            ->get()
            ->map(fn (Discount $d) => $d->tierPercentFor($qty))
            ->max();

        return (int) $tier;
    }

    /**
     * Validate + compute coupon discount for a cart subtotal.
     *
     * @param  Collection  $lines  pricing lines from PricingService
     * @return array{0:int,1:string|null} [discount_paise, error|null]
     */
    public function couponDiscountFor(Coupon $coupon, float $subtotal, ?User $user, Collection $lines): array
    {
        $error = $this->validateCoupon($coupon, $subtotal, $user, $lines);
        if ($error) {
            return [0, $error];
        }

        $base = $this->couponBaseAmountP($coupon, $lines);
        if ($base <= 0) {
            return [0, 'Coupon not valid for items in your cart.'];
        }

        $discount = $coupon->type === 'percentage'
            ? (int) round($base * ((float) $coupon->value) / 100)
            : (int) round(((float) $coupon->value) * 100);

        if ($coupon->max_discount_amount) {
            $discount = min($discount, (int) round(((float) $coupon->max_discount_amount) * 100));
        }

        return [min($discount, $base), null];
    }

    public function validateCoupon(Coupon $coupon, float $subtotal, ?User $user, Collection $lines): ?string
    {
        $now = now();

        if (! $coupon->is_active) {
            return 'This coupon is inactive.';
        }
        if ($coupon->starts_at && $coupon->starts_at->gt($now)) {
            return 'This coupon is not active yet.';
        }
        if ($coupon->ends_at && $coupon->ends_at->lt($now)) {
            return 'This coupon has expired.';
        }
        if ($subtotal < (float) $coupon->min_order_amount) {
            return sprintf('Add ₹%s more to use this coupon.', number_format((float) $coupon->min_order_amount - $subtotal, 2));
        }
        if ($coupon->usage_limit !== null && $coupon->usage_count >= $coupon->usage_limit) {
            return 'This coupon has reached its usage limit.';
        }

        if ($user) {
            $usedByUser = $coupon->usages()->where('user_id', $user->id)->count();
            if ($usedByUser >= $coupon->per_user_limit) {
                return 'You have already used this coupon.';
            }
            if ($coupon->first_order_only && $user->orders()->exists()) {
                return 'This coupon is only valid on your first order.';
            }
        }

        // Scope: at least one line must match when scoped coupons exist
        if ($coupon->products()->exists() || $coupon->categories()->exists()) {
            $hasMatch = $lines->contains(function ($line) use ($coupon) {
                $product = $line['item']->product;

                if (! $product) {
                    return false;
                }

                if ($coupon->products()->whereKey($product->id)->exists()) {
                    return true;
                }

                $catIds = $product->category?->descendantIds() ?? [];
                if ($catIds && \App\Models\Category::whereIn('id', $coupon->categories()->pluck('id'))->count() > 0) {
                    return $coupon->categories()->whereIn('categories.id', $catIds)->exists();
                }

                return false;
            });

            if (! $hasMatch) {
                return 'This coupon does not apply to your cart items.';
            }
        }

        return null;
    }

    /** Amount (paise) the coupon percentage applies to — respects product/category scope. */
    protected function couponBaseAmountP(Coupon $coupon, Collection $lines): int
    {
        $scoped = $coupon->products()->exists() || $coupon->categories()->exists();
        if (! $scoped) {
            return (int) round(((float) $lines->sum('line_total')) * 100);
        }

        $sum = 0;
        foreach ($lines as $line) {
            $product = $line['item']->product;
            if (! $product) {
                continue;
            }

            $match = $coupon->products()->whereKey($product->id)->exists()
                || $coupon->categories()->whereIn('categories.id', $product->category?->descendantIds() ?? [])->exists();

            if ($match) {
                $sum += (int) round(((float) $line['line_total']) * 100);
            }
        }

        return $sum;
    }

    protected function matchesProduct(Discount $d, Product $product): bool
    {
        return match ($d->scope) {
            'product' => $d->discountable_type === Product::class && (int) $d->discountable_id === $product->id,
            'category' => $d->discountable_type === Category::class
                && in_array($product->category_id, $product->category?->descendantIds() ?? [], true)
                && Category::find((int) $d->discountable_id)?->descendantIds()
                    && in_array(
                        $product->category_id,
                        app(self::class)->categoryTreeIds((int) $d->discountable_id),
                        true
                    ),
            default => false,
        };
    }

    protected function categoryTreeIds(int $categoryId): array
    {
        return Category::find($categoryId)?->descendantIds() ?? [$categoryId];
    }
}
