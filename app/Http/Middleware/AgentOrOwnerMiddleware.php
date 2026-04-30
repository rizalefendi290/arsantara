<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AgentOrOwnerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['agen', 'pemilik'], true)) {
            abort(403);
        }

        return $next($request);
    }
}
