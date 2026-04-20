<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAuditor
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() ||
            ! $request->user()->hasAnyRole(['auditor', 'super_admin', 'admin'])) {
            abort(403, 'Access restricted to Auditors only.');
        }

        return $next($request);
    }
}