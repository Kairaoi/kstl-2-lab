<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsDirector
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()?->hasRole('director')) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}