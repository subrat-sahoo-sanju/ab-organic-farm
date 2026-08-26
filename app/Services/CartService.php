<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Cookie;

class CartService
{
    public const GUEST_COOKIE = 'aborganic_cart';

    /**
     * Resolve the active cart for the current request (user or guest).
     */
    public function resolve(bool $create = true): ?Cart
    {
        $cart = null;

        if ($userId = auth()->id()) {
            $cart = Cart::firstOrCreate(['user_id' => $userId]);
        } else {
            $sessionId = (string) Cookie::get(self::GUEST_COOKIE, '');
            if (! $sessionId && ! $create) {
                return null;
            }
            if ($sessionId) {
                $cart = Cart::whereNull('user_id')->where('session_id', $sessionId)->first();
            }
            if (! $cart && $create) {
                if (! $sessionId) {
                    $sessionId = bin2hex(random_bytes(16));
                    Cookie::queue(self::GUEST_COOKIE, $sessionId, 60 * 24 * 30);
                }
                $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
            }
        }

        return $cart;
    }

    public function add(ProductVariant $variant, int $qty = 1): void
    {
        $cart = $this->resolve();
        $available = $variant->inventory?->available() ?? 0;

        $existing = $cart->items()->where('product_variant_id', $variant->id)->first();
        $newQty = min($existing?->quantity ?? 0, 0) + max(1, $qty);

        if ($newQty > $available) {
            throw new \DomainException($available > 0
                ? "Only {$available} unit(s) available."
                : 'This item is out of stock.');
        }

        if ($existing) {
            $existing->increment('quantity', $newQty - $existing->quantity);
        } else {
            $cart->items()->create([
                'product_variant_id' => $variant->id,
                'product_id' => $variant->product_id,
                'quantity' => $newQty,
                'price_at_add' => $variant->effectivePrice(),
            ]);
        }
    }

    public function updateQuantity(CartItem $item, int $qty): void
    {
        $available = $item->variant?->inventory?->available() ?? 0;

        if ($qty <= 0) {
            $item->delete();

            return;
        }

        if ($qty > $available) {
            throw new \DomainException("Only {$available} unit(s) available.");
        }

        $item->update(['quantity' => $qty]);
    }

    /** Merge a guest cart into the user's cart after login. */
    public function mergeGuestCart(): void
    {
        $sessionId = (string) Cookie::get(self::GUEST_COOKIE, '');
        if (! $sessionId || ! auth()->check()) {
            return;
        }

        $guest = Cart::with('items')->whereNull('user_id')->where('session_id', $sessionId)->first();
        if (! $guest) {
            return;
        }

        $user = auth()->user();
        $target = Cart::firstOrCreate(['user_id' => $user->id]);

        foreach ($guest->items as $item) {
            $existing = $target->items()->where('product_variant_id', $item->product_variant_id)->first();

            if ($existing) {
                $max = $item->variant?->inventory?->stock ?? 0;
                $existing->update(['quantity' => min($existing->quantity + $item->quantity, max(1, $max))]);
            } else {
                $item->update(['cart_id' => $target->id]);
            }
        }

        // Prefer an applied guest coupon if target has none
        if ($guest->coupon_id && ! $target->coupon_id) {
            $target->update(['coupon_id' => $guest->coupon_id]);
        }

        $guest->items()->delete();
        $guest->forceDelete();
        Cookie::queue(Cookie::forget(self::GUEST_COOKIE));
    }

    /** Remove items whose product/variant is no longer purchasable. */
    public function purgeDeadItems(Cart $cart): void
    {
        foreach ($cart->items()->with('product', 'variant')->get() as $item) {
            if (! $item->product
                || $item->product->status !== 'active'
                || ! $item->product->published_at
                || ! $item->variant?->is_active) {
                $item->delete();
            }
        }
    }

    public function countForHeader(): int
    {
        $cart = $this->resolve(false);

        return $cart ? (int) $cart->items()->sum('quantity') : 0;
    }
}
