<?php

namespace App\Http\Middleware;

use App\Models\SiteVisit;
use Closure;
use Illuminate\Http\Request;

class TrackSiteVisit
{
    public function handle(Request $request, Closure $next)
    {
        if ($this->shouldTrack($request)) {
            SiteVisit::create([
                'user_id' => optional($request->user())->id,
                'session_id' => optional($request->session())->getId(),
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 1000),
                'method' => $request->method(),
                'path' => $request->path(),
                'url' => $request->fullUrl(),
                'referer' => $request->headers->get('referer'),
                'visited_at' => now(),
            ]);
        }

        return $next($request);
    }

    private function shouldTrack(Request $request): bool
    {
        return $request->isMethod('GET')
            && !$request->is('admin*')
            && !$request->is('storage*')
            && !$request->is('build*')
            && !$request->is('up')
            && !$request->expectsJson();
    }
}
