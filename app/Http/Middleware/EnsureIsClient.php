<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Repositories\Kstl\ClientRepository;

class EnsureIsClient
{
    public function __construct(protected ClientRepository $clients) {}

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user?->hasRole('client')) {
            abort(403, 'Access denied.');
        }

        // Skip checks on profile and agreement routes themselves
        if ($request->routeIs('client.profile.company.*', 'client.agreement.*', 'client.dashboard')) {
            return $next($request);
        }

        $client = $this->clients->findByUserId($user->id);

        // Step 1 — No client profile yet → fill company details first
        if (! $client) {
            return redirect()->route('client.profile.company.show')
                ->with('info', 'Please complete your company profile before continuing.');
        }

        // Step 2 — Profile exists but agreement not signed → sign agreement
        if (! $client->service_agreement_signed_at) {
            return redirect()->route('client.agreement.show')
                ->with('info', 'Please read and sign the service agreement to continue.');
        }

        return $next($request);
    }
}