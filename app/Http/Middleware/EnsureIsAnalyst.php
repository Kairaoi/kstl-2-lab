<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAnalyst
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()?->hasRole('analyst')) {
            abort(403, 'Access denied.');
        }

        return $next($request);
    }
}