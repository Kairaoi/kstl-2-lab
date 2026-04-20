<?php

use App\Http\Controllers\Kstl\AuditController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:auditor|super_admin|admin',
])->prefix('auditor')->name('auditor.')->group(function () {

    // ── Dashboard (redirects to audit log) ────────────────────────
    Route::get('/dashboard', function () {
        return redirect()->route('auditor.audit.index');
    })->name('dashboard');

    // ── Audit Log ──────────────────────────────────────────────────
    Route::get('/audit', [AuditController::class, 'index'])
        ->name('audit.index');

    Route::get('/audit/{id}', [AuditController::class, 'show'])
        ->name('audit.show');
});