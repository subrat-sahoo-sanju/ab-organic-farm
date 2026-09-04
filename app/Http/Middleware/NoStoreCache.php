<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Prevent browsers from serving a stale copy of a page after a
 * POST / redirect / GET cycle. The framework only emits "no-cache"
 * (which still allows back-forward cache reuse for the same URL),
 * so admins would land back on an outdated list after creating or
 * updating a record. "no-store" forces a fresh fetch every time.
 */
class NoStoreCache
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response instanceof Response) {
            $response->header('Cache-Control', 'no-store, private');
        }

        return $response;
    }
}