<?php

namespace App\Listeners;

use App\Services\AuditService;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public function __construct(protected AuditService $audit) {}

    public function handle(Login $event): void
    {
        $user = $event->user;

        $name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''))
            ?: ($user->email ?? 'User');

        // AuditService::log() captures IP + user agent automatically.
        $this->audit->logLogin((string) $user->getAuthIdentifier(), $name);
    }
}