<?php

namespace App\Listeners;

use App\Services\AuditService;
use Illuminate\Auth\Events\Failed;

class LogFailedLogin
{
    public function __construct(protected AuditService $audit) {}

    public function handle(Failed $event): void
    {
        // $event->user is the matched user (wrong password) or null (unknown email).
        // $event->credentials holds what was submitted.
        $attemptedEmail = $event->credentials['email'] ?? null;
        $userId         = $event->user?->getAuthIdentifier();

        $this->audit->logFailedLogin($attemptedEmail, $userId ? (string) $userId : null);
    }
}