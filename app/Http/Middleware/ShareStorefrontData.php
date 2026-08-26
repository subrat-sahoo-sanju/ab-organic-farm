<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Services\CartService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareStorefrontData
{
    public function __construct(protected CartService $carts) {}

    public function handle(Request $request, Closure $next): Response
    {
        // Resolve cart early for guests so the tracking cookie is queued on this response
        if (! app()->runningInConsole()) {
            $this->carts->resolve();
        }

        View::share('categories', Cache::remember(
            'nav.categories',
            now()->addMinutes(30),
            fn () => Category::whereNull('parent_id')->where('is_active', true)->orderBy('sort_order')->get()
        ));

        return $next($request);
    }
}
