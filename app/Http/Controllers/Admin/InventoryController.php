<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\InventoryTxnType;
use App\Models\Inventory;
use App\Models\User;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function __construct(protected InventoryService $inventory) {}

    public function index(): View
    {
        $inventories = Inventory::query()
            ->with(['variant.product:id,name,slug,status'])
            ->when(request('filter') === 'low', fn ($q) => $q->whereColumn('stock', '>', 'reserved')->whereRaw('stock - reserved <= low_stock_threshold'))
            ->when(request('filter') === 'out', fn ($q) => $q->whereColumn('stock', '<=', 'reserved'))
            ->when(request('q'), function ($q, $v) {
                $q->whereHas('variant.product', fn ($w) => $w->where('name', 'like', "%{$v}%")
                    ->orWhereHas('variants', fn ($s) => $s->where('sku', 'like', "%{$v}%")));
            })
            ->paginate(20)
            ->withQueryString();

        return view('admin.catalog.inventory', [
            'inventories' => $inventories,
            'stats' => [
                'total_stock' => (int) Inventory::sum('stock'),
                'reserved' => (int) Inventory::sum('reserved'),
                'low' => Inventory::all()->filter->isLow()->count(),
                'out' => Inventory::all()->filter->isOutOfStock()->count(),
            ],
        ]);
    }

    public function transactions(): View
    {
        return view('admin.catalog.inventory-transactions', [
            'transactions' => \App\Models\InventoryTransaction::with([
                'inventory.variant.product:id,name',
                'user:id,name',
            ])->latest('created_at')->paginate(25),
        ]);
    }

    public function adjust(Inventory $inventory): RedirectResponse
    {
        $data = request()->validate([
            'quantity' => ['required', 'integer', 'not_in:0'],
            'reason' => ['required', 'string', 'max:190'],
            'type' => ['nullable', 'in:purchase,adjustment,damage'],
        ]);

        try {
            $type = InventoryTxnType::from($data['type'] ?? ($data['quantity'] > 0 ? 'adjustment' : 'damage'));
            $this->inventory->adjust($inventory, (int) $data['quantity'], $type, $data['reason']);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        $this->checkLowStock($inventory);

        return back()->with('success', 'Stock adjusted.');
    }

    protected function checkLowStock(Inventory $inventory): void
    {
        if ($inventory->available() > 0 && $inventory->available() <= $inventory->low_stock_threshold) {
            User::whereHas('roles', fn ($q) => $q->whereIn('name', ['super_admin', 'admin']))
                ->each(fn ($u) => $u->notify(new \App\Notifications\Admin\LowStockAlert($inventory)));
        }
    }
}
