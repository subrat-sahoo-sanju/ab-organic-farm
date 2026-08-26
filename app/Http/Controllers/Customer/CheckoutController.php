<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\DeliveryArea;
use App\Services\CartService;
use App\Services\PricingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $carts,
        protected PricingService $pricing,
    ) {}

    public function show(): View
    {
        $cart = $this->carts->resolve();
        $this->carts->purgeDeadItems($cart);
        $breakdown = $this->pricing->forCart($cart->refresh());

        abort_if($breakdown['lines']->isEmpty(), 302, '', ['Location' => route('cart.index')]);
        abort_if($breakdown['lines']->contains(fn ($l) => ! $l['in_stock']), 302, '', ['Location' => route('cart.index')]);

        $addresses = auth()->user()->addresses()->get();
        $defaultAddress = $addresses->firstWhere('is_default', true) ?? $addresses->first();

        $deliverySlots = $this->buildDeliverySlots();
        $cart = $cart->load('coupon');

        return view('customer.checkout', [
            'cart' => $cart,
            'addresses' => $addresses,
            'defaultAddress' => $defaultAddress,
            'breakdown' => $breakdown,
            'deliverySlots' => $deliverySlots,
            'codEnabled' => setting('cod.enabled', '1') === '1',
        ]);
    }

    /**
     * AJAX: Add a new address from checkout inline form.
     */
    public function addAddress(Request $request): JsonResponse
    {
        $data = $request->validate([
            'label' => ['required', 'in:home,office,other'],
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'digits:10'],
            'house_no' => ['required', 'string', 'max:120'],
            'street' => ['nullable', 'string', 'max:190'],
            'area' => ['nullable', 'string', 'max:190'],
            'landmark' => ['nullable', 'string', 'max:190'],
            'city' => ['required', 'string', 'max:120'],
            'state' => ['required', 'string', 'max:120'],
            'pincode' => ['required', 'digits:6'],
        ]);

        $data['user_id'] = auth()->id();
        $data['is_default'] = auth()->user()->addresses()->count() === 0;

        $address = Address::create($data);

        if ($data['is_default']) {
            auth()->user()->addresses()->whereKeyNot($address->id)->update(['is_default' => false]);
        }

        return response()->json([
            'ok' => true,
            'address' => [
                'id' => $address->id,
                'label' => $address->label,
                'name' => $address->name,
                'phone' => $address->phone,
                'house_no' => $address->house_no,
                'street' => $address->street,
                'area' => $address->area,
                'landmark' => $address->landmark,
                'city' => $address->city,
                'state' => $address->state,
                'pincode' => $address->pincode,
                'is_default' => $address->is_default,
            ],
        ]);
    }

    /**
     * Place the COD order. The idempotency token prevents double-submit duplicates.
     */
    public function placeOrder(\App\Http\Requests\Customer\PlaceOrderRequest $request, \App\Services\OrderService $orders)
    {
        $address = Address::where('user_id', auth()->id())->findOrFail($request->address_id);

        try {
            $order = $orders->placeFromCart(
                auth()->user(),
                $address,
                (string) $request->idempotency_token
            );
        } catch (\DomainException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        return redirect()->route('account.orders.show', $order)
            ->with('success', 'Order confirmed! Your organic products are on their way.');
    }

    protected function buildDeliverySlots(): array
    {
        $now = now();
        $slots = [];
        $startHour = (int) $now->format('H');

        $ranges = [
            ['label' => 'Morning', 'sub' => '8 AM - 11 AM', 'start' => 8, 'end' => 11, 'icon' => 'sunrise'],
            ['label' => 'Afternoon', 'sub' => '11 AM - 3 PM', 'start' => 11, 'end' => 15, 'icon' => 'sun'],
            ['label' => 'Evening', 'sub' => '3 PM - 7 PM', 'start' => 15, 'end' => 19, 'icon' => 'sunset'],
            ['label' => 'Night', 'sub' => '7 PM - 10 PM', 'start' => 19, 'end' => 22, 'icon' => 'moon'],
        ];

        foreach ($ranges as $r) {
            $available = $startHour < $r['end'];
            $slots[] = [
                'label' => $r['label'],
                'sub' => $r['sub'],
                'available' => $available,
                'value' => strtolower($r['label']),
            ];
        }

        return $slots;
    }
}
