<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Services\CartService;
use App\Services\PricingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        protected CartService $carts,
        protected PricingService $pricing,
    ) {}

    public function index(): View
    {
        $cart = $this->carts->resolve();
        $this->carts->purgeDeadItems($cart);

        $breakdown = $this->pricing->forCart($cart->refresh());

        return view('customer.cart', [
            'breakdown' => $breakdown,
            'lines' => $breakdown['lines'],
        ]);
    }

    public function state(): \Illuminate\Http\JsonResponse
    {
        $cart = $this->carts->resolve(false);

        $qtys = [];
        $items = [];
        $total = 0.0;

        if ($cart) {
            $cart->items()->with('variant')->get()->each(function ($item) use (&$qtys, &$items, &$total) {
                $qtys[$item->product_variant_id] = (int) $item->quantity;
                $items[$item->product_variant_id] = (int) $item->id;
                $total += (float) $item->quantity * (float) ($item->variant?->sale_price ?? $item->variant?->price ?? 0);
            });
        }

        return response()->json([
            'ok' => true,
            'count' => $this->carts->countForHeader(),
            'total' => round($total, 2),
            'qtys' => $qtys,
            'items' => $items,
        ]);
    }

    public function add(): \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
    {
        $data = request()->validate([
            'variant_id' => ['required', 'integer', 'exists:product_variants,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        try {
            $variant = ProductVariant::with('product')->findOrFail($data['variant_id']);
            abort_if($variant->product->status !== 'active', 404, 'Product unavailable.');

            $this->carts->add($variant, (int) ($data['quantity'] ?? 1));

            if (request()->wantsJson()) {
                return response()->json([
                    'ok' => true,
                    'count' => $this->carts->countForHeader(),
                ]);
            }

            return back()->with('success', 'Added to your basket.');
        } catch (\DomainException $e) {
            if (request()->wantsJson()) {
                return response()->json(['message' => $e->getMessage()], 422);
            }

            return back()->with('error', $e->getMessage());
        }
    }

    public function update(\App\Models\CartItem $item)
    {
        $qty = (int) (request()->input('quantity') ?? request()->json('quantity', 0));

        try {
            $this->authorizeCartItem($item);

            if ($qty <= 0) {
                $item->delete();

                if (request()->wantsJson()) {
                    return $this->jsonCartResponse('Item removed.');
                }

                return back()->with('success', 'Item removed.');
            }

            $this->carts->updateQuantity($item, $qty);

            if (request()->wantsJson()) {
                return $this->jsonCartResponse();
            }

            return back();
        } catch (\DomainException $e) {
            if (request()->wantsJson()) {
                return response()->json(['error' => $e->getMessage()], 422);
            }

            return back()->with('error', $e->getMessage());
        } catch (\AuthorizationException $e) {
            if (request()->wantsJson()) {
                return response()->json(['error' => 'Not allowed.'], 403);
            }

            return back()->with('error', 'Not allowed.');
        }
    }

    public function remove(\App\Models\CartItem $item)
    {
        try {
            $this->authorizeCartItem($item);
            $item->delete();

            if (request()->wantsJson()) {
                return $this->jsonCartResponse('Item removed.');
            }

            return back()->with('success', 'Item removed.');
        } catch (\AuthorizationException $e) {
            if (request()->wantsJson()) {
                return response()->json(['error' => 'Not allowed.'], 403);
            }

            return back()->with('error', 'Not allowed.');
        }
    }

    public function applyCoupon(): RedirectResponse
    {
        $code = trim((string) request('coupon_code', request('code', '')));
        $coupon = \App\Models\Coupon::whereRaw('BINARY code = ?', [$code])->first();

        $discountService = app(\App\Services\DiscountService::class);

        if (! $coupon) {
            return back()->with('error', 'Invalid coupon code.');
        }

        $cart = $this->carts->resolve();
        $breakdown = $this->pricing->forCart($cart);

        $error = $discountService->validateCoupon($coupon, $breakdown['subtotal'], auth()->user(), $breakdown['lines']);

        if ($error) {
            return back()->with('error', $error);
        }

        $cart->update(['coupon_id' => $coupon->id]);

        return back()->with('success', "Coupon {$coupon->code} applied!");
    }

    public function removeCoupon(): RedirectResponse
    {
        $this->carts->resolve()?->update(['coupon_id' => null]);

        return back()->with('success', 'Coupon removed.');
    }

    protected function authorizeCartItem(\App\Models\CartItem $item): void
    {
        $cart = $this->carts->resolve(false);
        abort_unless($cart && $item->cart_id === $cart->id, 403);
    }

    protected function jsonCartResponse(?string $message = null): \Illuminate\Http\JsonResponse
    {
        $cart = $this->carts->resolve(false);
        $breakdown = $cart ? $this->pricing->forCart($cart->refresh()) : null;

        $response = [
            'ok' => true,
            'count' => $this->carts->countForHeader(),
        ];

        if ($breakdown) {
            $response['subtotal'] = $breakdown['subtotal'];
            $response['product_discount'] = $breakdown['product_discount'];
            $response['coupon_discount'] = $breakdown['coupon_discount'];
            $response['delivery_charge'] = $breakdown['delivery_charge'];
            $response['grand_total'] = $breakdown['grand_total'];
            $response['lines'] = $breakdown['lines']->map(fn ($l) => [
                'id' => $l['item']->id,
                'quantity' => $l['item']->quantity,
                'unit_price' => $l['unit_effective'],
                'unit_list' => $l['unit_list'],
                'line_total' => $l['line_total'],
                'discount_per_unit' => $l['discount_per_unit'],
                'in_stock' => $l['in_stock'],
                'product_name' => $l['item']->product->name ?? '',
                'variant_name' => $l['item']->variant?->name ?? '',
            ])->values();
            $response['item_count'] = $breakdown['lines']->sum(fn ($l) => $l['item']->quantity);
            $response['product_count'] = $breakdown['lines']->count();
        }

        if ($message) {
            $response['message'] = $message;
        }

        return response()->json($response);
    }
}
