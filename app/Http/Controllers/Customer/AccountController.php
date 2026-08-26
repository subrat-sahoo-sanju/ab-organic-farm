<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RecentlyViewed;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function dashboard(): View
    {
        $user = auth()->user()->loadCount(['orders', 'wishlistItems']);

        $recentlyViewed = Product::query()
            ->published()
            ->whereIn('id', RecentlyViewed::where('user_id', $user->id)
                ->latest('viewed_at')->limit(8)->pluck('product_id'))
            ->with('primaryImage', 'defaultVariant')
            ->get();

        return view('customer.account.dashboard', [
            'user' => $user,
            'orderStats' => [
                'total' => $user->orders_count,
                'active' => $user->orders()->whereIn('status', ['pending', 'confirmed', 'preparing', 'packed', 'assigned', 'out_for_delivery'])->count(),
                'delivered' => $user->orders()->where('status', 'delivered')->count(),
                'spent' => (float) $user->orders()->whereIn('status', ['delivered', 'out_for_delivery'])->sum('grand_total'),
            ],
            'recentOrders' => $user->orders()->with('items')->latest('placed_at')->take(3)->get(),
            'recentlyViewed' => $recentlyViewed,
        ]);
    }

    public function updateProfile(\Illuminate\Http\Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'digits:10', 'unique:users,phone,'.auth()->id()],
            'email' => ['required', 'email', 'max:190', 'unique:users,email,'.auth()->id()],
            'dob' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female,other'],
        ]);

        auth()->user()->update($data);

        return back()->with('success', 'Profile updated.');
    }

    public function updatePassword(\Illuminate\Http\Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)],
        ]);

        auth()->user()->update(['password' => bcrypt($request->password)]);

        return back()->with('success', 'Password changed.');
    }
}
