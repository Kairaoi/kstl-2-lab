<?php

namespace App\Listeners;

use App\Services\AuditService;
use Illuminate\Auth\Events\Logout;

class LogSuccessfulLogout
{
    public function __construct(protected AuditService $audit) {}

    public function handle(Logout $event): void
    {
        $user = $event->user;

        // Some logout paths arrive with a null user — guard for it.
        if (! $user) {
            return;
        }

        $name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''))
            ?: ($user->email ?? 'User');

        $this->audit->logLogout((string) $user->getAuthIdentifier(), $name);
    }
}