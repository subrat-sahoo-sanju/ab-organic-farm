<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\DeliveryArea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddressController extends Controller
{
    public function index(): View
    {
        return view('customer.account.addresses', [
            'addresses' => auth()->user()->addresses()->latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['user_id'] = auth()->id();

        $address = Address::create($data);

        if ($address->is_default || auth()->user()->addresses()->count() === 1) {
            $this->makeDefault($address);
        }

        return back()->with('success', 'Address saved.');
    }

    public function update(Request $request, Address $address): RedirectResponse
    {
        abort_unless($address->user_id === auth()->id(), 403);

        $address->update($this->validated($request));

        if ($request->boolean('is_default')) {
            $this->makeDefault($address);
        }

        return back()->with('success', 'Address updated.');
    }

    public function destroy(Address $address): RedirectResponse
    {
        abort_unless($address->user_id === auth()->id(), 403);
        $address->delete();

        return back()->with('success', 'Address removed.');
    }

    public function setDefault(Address $address): RedirectResponse
    {
        abort_unless($address->user_id === auth()->id(), 403);
        $this->makeDefault($address);

        return back()->with('success', 'Default address updated.');
    }

    protected function makeDefault(Address $address): void
    {
        auth()->user()->addresses()->whereKeyNot($address->id)->update(['is_default' => false]);
        $address->forceFill(['is_default' => true])->save();
    }

    protected function validated(Request $request): array
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
            'is_default' => ['nullable', 'boolean'],
        ]);

        // Warn (don't block) if area isn't serviceable — checkout will re-check.
        if (! DeliveryArea::where('pincode', $data['pincode'])->where('is_serviceable', true)->exists()) {
            $request->session()->flash('warning', "Heads up — we may not deliver to {$data['pincode']} yet.");
        }

        return $data;
    }
}
