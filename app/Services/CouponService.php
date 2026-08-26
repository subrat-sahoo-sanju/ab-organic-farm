<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CouponService
{
    public function recordUsage(Coupon $coupon, User $user, Order $order): void
    {
        DB::transaction(function () use ($coupon, $user, $order) {
            // Atomic guard against over-usage races
            if ($coupon->usage_limit !== null) {
                $updated = Coupon::whereKey($coupon->id)
                    ->where('usage_count', '<', $coupon->usage_limit)
                    ->increment('usage_count');

                if ($updated === 0) {
                    throw new \DomainException('Coupon usage limit reached.');
                }
            } else {
                $coupon->increment('usage_count');
            }

            CouponUsage::create([
                'coupon_id' => $coupon->id,
                'user_id' => $user->id,
                'order_id' => $order->id,
                'discount_amount' => $order->coupon_discount,
                'used_at' => now(),
            ]);
        });
    }
}
