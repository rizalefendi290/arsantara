<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MarketingMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'marketing' || ! auth()->user()->is_active) {
            abort(403);
        }

        return $next($request);
    }
}
