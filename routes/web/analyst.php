<?php

use App\Http\Controllers\Kstl\AnalystController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:analyst|reception|admin|super_admin',
])->prefix('analyst')->name('analyst.')->group(function () {

    Route::get('/dashboard', [AnalystController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/tests', [AnalystController::class, 'index'])
        ->name('tests.index');

    Route::get('/tests/{id}', [AnalystController::class, 'show'])
        ->name('tests.show');

    Route::post('/tests/{id}/result', [AnalystController::class, 'saveResult'])
        ->name('tests.result');

    // ── Supporting files / documents on a test result ──────────────
    Route::post('/tests/{id}/attachments', [AnalystController::class, 'uploadAttachment'])
        ->name('tests.attachments.store');

    Route::get('/attachments/{attachment}/download', [AnalystController::class, 'downloadAttachment'])
        ->name('tests.attachments.download');

    Route::delete('/attachments/{attachment}', [AnalystController::class, 'deleteAttachment'])
        ->name('tests.attachments.destroy');

    // ── Authorised results (read-only) ─────────────────────────────
    Route::get('/results', [AnalystController::class, 'resultsIndex'])
        ->name('results.index');

    Route::get('/results/{submission}', [AnalystController::class, 'resultsShow'])
        ->name('results.show');

});