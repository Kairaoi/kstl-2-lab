<?php

use App\Http\Controllers\Kstl\DocumentController;
use Illuminate\Support\Facades\Route;

/**
 * Lab-staff document repository.
 * All lab staff may view/download; create/upload-version/delete are further
 * restricted to director/admin/super_admin inside the controller.
 *
 * Add to routes/web.php alongside the others:
 *     require __DIR__ . '/web/staff.php';
 */
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:reception|analyst|director|admin|super_admin',
])->prefix('staff')->name('staff.')->group(function () {

    // Repository (all staff)
    Route::get('/documents', [DocumentController::class, 'index'])
        ->name('documents.index');

    Route::get('/documents/{id}', [DocumentController::class, 'show'])
        ->name('documents.show');

    // Download a specific version (all staff)
    Route::get('/documents/versions/{versionId}/download', [DocumentController::class, 'download'])
        ->name('documents.download');

    // Manage — controller restricts these to director/admin/super_admin
    Route::post('/documents', [DocumentController::class, 'store'])
        ->name('documents.store');

    Route::post('/documents/{id}/versions', [DocumentController::class, 'uploadVersion'])
        ->name('documents.versions.store');

    Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])
        ->name('documents.destroy');
});
