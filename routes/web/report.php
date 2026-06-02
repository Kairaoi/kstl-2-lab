<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/**
 * Reports routes.
 *
 * Gated to oversight roles (director, auditor, admin, super_admin) — the same
 * roles the audit reports are seeded with. Per-report visibility is further
 * enforced inside the service via allowed_roles, so even within this group a
 * user only sees/runs reports their roles permit.
 *
 * Add to routes/web.php alongside the others:
 *     require __DIR__ . '/web/reports.php';
 * (adjust the path to wherever your role route files live)
 */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:director|auditor|admin|super_admin',
])->prefix('reports')->name('reports.')->group(function () {

    // List available reports (role-scoped)
    Route::get('/', [ReportController::class, 'index'])
        ->name('index');

    // Run a report and show results
    Route::get('/{code}', [ReportController::class, 'execute'])
        ->name('execute');

    // Export a report (csv default, or json)
    Route::get('/{code}/export/{format?}', [ReportController::class, 'export'])
        ->name('export');
});