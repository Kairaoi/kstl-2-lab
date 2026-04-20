<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsReception
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user?->hasAnyRole(['reception', 'admin', 'super_admin'])) {
            abort(403, 'Access denied. Reception staff only.');
        }

        return $next($request);
    }
}