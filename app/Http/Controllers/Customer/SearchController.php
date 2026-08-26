<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function results(): View
    {
        $term = trim((string) request('q', ''));

        $query = Product::query()->published()
            ->when($term !== '', fn (Builder $q) => $q
                ->where(fn (Builder $w) => $w
                    ->where('name', 'like', "%{$term}%")
                    ->orWhere('short_description', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%")))
            ->with(['primaryImage', 'defaultVariant.inventory']);

        $products = $term !== ''
            ? $query->orderByDesc('sold_count')->paginate(12)->withQueryString()
            : new \Illuminate\Pagination\LengthAwarePaginator(collect(), 0, 12);

        return view('customer.search-results', [
            'query' => $term,
            'products' => $products,
        ]);
    }

    public function page(): View
    {
        return view('customer.search-page', [
            'popularCategories' => Category::where('is_active', true)->withCount('products')->orderByDesc('products_count')->take(8)->get(),
            'trending' => $this->trending(),
        ]);
    }

    public function suggest()
    {
        $term = trim((string) request('q', ''));

        if (mb_strlen($term) < 2) {
            return response()->json(['products' => [], 'categories' => []]);
        }

        $products = Product::query()
            ->published()
            ->where(fn (Builder $q) => $q->where('name', 'like', "%{$term}%")->orWhere('sku', 'like', "%{$term}%"))
            ->take(6)
            ->get(['id', 'name', 'slug'])
            ->map(fn ($p) => ['name' => $p->name, 'url' => route('shop.product', $p->slug)]);

        $categories = Category::where('name', 'like', "%{$term}%")
            ->take(3)
            ->get(['id', 'name', 'slug'])
            ->map(fn ($c) => ['name' => $c->name.' (category)', 'url' => route('shop.category', $c->slug)]);

        return response()->json(['products' => $products, 'categories' => $categories]);
    }

    protected function trending(): array
    {
        return ['organic turmeric', 'cold pressed oil', 'jaggery', 'almonds', 'honey', 'basmati rice'];
    }
}
