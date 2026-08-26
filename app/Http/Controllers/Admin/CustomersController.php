<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class CustomersController extends Controller
{
    public function index()
    {
        $query = User::whereHas('roles', fn ($q) => $q->where('name', 'customer'))
            ->withCount('orders')
            ->latest('created_at');

        if (request('q')) {
            $q = request('q');
            $query->where(fn ($w) => $w
                ->where('name', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('phone', 'like', "%{$q}%"));
        }

        return view('admin.customers.index', [
            'customers' => $query->paginate(20)->withQueryString(),
        ]);
    }

    public function show(User $customer)
    {
        abort_unless($customer->hasRole('customer'), 404);

        return view('admin.customers.show', [
            'customer' => $customer->load(['orders' => fn ($q) => $q->latest('placed_at')->limit(10), 'orders.items', 'addresses']),
            'orderStats' => [
                'total' => $customer->orders_count ?? $customer->orders()->count(),
                'delivered' => $customer->orders()->where('status', 'delivered')->count(),
                'cancelled' => $customer->orders()->where('status', 'cancelled')->count(),
                'total_spent' => (float) $customer->orders()->whereIn('status', ['delivered', 'out_for_delivery'])->sum('grand_total'),
            ],
        ]);
    }

    public function block(User $customer): RedirectResponse
    {
        abort_unless($customer->hasRole('customer'), 404);
        $customer->update(['is_active' => false]);

        return back()->with('success', 'Customer blocked.');
    }

    public function unblock(User $customer): RedirectResponse
    {
        abort_unless($customer->hasRole('customer'), 404);
        $customer->update(['is_active' => true]);

        return back()->with('success', 'Customer unblocked.');
    }
}
