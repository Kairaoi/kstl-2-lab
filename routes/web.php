<?php

use Illuminate\Support\Facades\Route;

// Public welcome page
Route::get('/', fn() => view('welcome'));

// ------------------------------------------------------------------
// DO NOT declare /reset-password here manually.
// Fortify owns this route. We register the view via
// FortifyServiceProvider::boot() using Fortify::resetPasswordView().
// Declaring it here AND in Fortify causes a conflict where the
// closure runs first without the $request variable Jetstream expects.
// ------------------------------------------------------------------

// KSTL role-specific routes
require __DIR__ . '/web/client.php';
require __DIR__ . '/web/auditor.php';
require __DIR__ . '/web/reception.php';
require __DIR__ . '/web/analyst.php';
require __DIR__ . '/web/director.php';

// ------------------------------------------------------------------
// Forced password change (temp password issued by Director/admin)
// Client must change temporary password on first login
// ------------------------------------------------------------------
// Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
//     ->group(function () {
//         Route::get('/password/change', [ChangePasswordController::class, 'show'])
//             ->name('password.change');
//         Route::post('/password/change/update', [ChangePasswordController::class, 'update'])
//             ->name('password.change.update');
//     });

// ------------------------------------------------------------------
// Main authenticated dashboard redirect (role-based)
// Fortify redirects here after login, then we redirect to role dashboard
// ------------------------------------------------------------------
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->get('/dashboard', function () {
        $user = auth()->user();

        return match (true) {
            $user->hasRole('client')         => redirect()->route('client.dashboard'),
            $user->hasRole('reception')      => redirect()->route('reception.dashboard'),
            $user->hasRole('analyst')        => redirect()->route('analyst.dashboard'),
            $user->hasRole('director')       => redirect()->route('director.dashboard'),
            $user->hasRole('auditor')        => redirect()->route('auditor.audit.index'),
            $user->hasRole('client_manager') => redirect('/admin'),
            $user->hasRole('admin')          => redirect('/admin'),
            $user->hasRole('super_admin')    => redirect('/admin'),
            default                          => view('dashboard'),
        };
    })->name('dashboard');