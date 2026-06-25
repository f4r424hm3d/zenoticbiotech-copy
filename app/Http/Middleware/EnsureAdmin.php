<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->role !== 'admin') {
            if (! $request->is('api/*')) {
                abort(403, 'Admin access required');
            }

            return response()->json(['error' => 'Admin access required'], 403);
        }

        return $next($request);
    }
}
