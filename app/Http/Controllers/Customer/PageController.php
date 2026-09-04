<?php

namespace App\Http\Controllers\Customer;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Render an admin-managed policy / information page.
     *
     * Handles both the static routes (e.g. /privacy-policy, slug derived from the
     * path) and the generic bound route (/pages/{page}) where Laravel injects the
     * Page model directly. All content is editable in the admin "Pages & Policies".
     */
    public function show(Request $request, ?Page $page = null): View
    {
        if ($page) {
            abort_if(! $page->is_active, 404);
            return view('customer.pages.show', ['page' => $page]);
        }

        $slug = ltrim($request->path(), '/');

        $bound = Cache::remember('page.'.$slug, 300, function () use ($slug) {
            return Page::where('slug', $slug)->where('is_active', true)->first();
        });

        abort_if(is_null($bound), 404);

        return view('customer.pages.show', ['page' => $bound]);
    }
}