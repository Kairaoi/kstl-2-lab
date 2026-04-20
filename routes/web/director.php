<?php

use App\Http\Controllers\Kstl\DirectorController;
use App\Http\Controllers\Kstl\ComplaintController;
use App\Http\Controllers\Kstl\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:director|admin|super_admin',
])->prefix('director')->name('director.')->group(function () {

    Route::get('/dashboard', [DirectorController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/submissions/{id}', [DirectorController::class, 'show'])
        ->name('submissions.show');

    Route::post('/submissions/{id}/authorise', [DirectorController::class, 'authorise'])
        ->name('submissions.authorise');

    Route::post('/submissions/{id}/query', [DirectorController::class, 'queryAnalyst'])
        ->name('submissions.query');

    // ── Invoices ──────────────────────────────────────────────────
    Route::get('/invoices', [InvoiceController::class, 'index'])
        ->name('invoices.index');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])
        ->name('invoices.show');
    Route::post('/invoices/generate/{submissionId}', [InvoiceController::class, 'generate'])
        ->name('invoices.generate');
    Route::post('/invoices/{id}/paid', [InvoiceController::class, 'markPaid'])
        ->name('invoices.paid');

    // ── Agreements ────────────────────────────────────────────────
    Route::get('/agreements', [DirectorController::class, 'agreementsIndex'])
        ->name('agreements.index');
    Route::get('/agreements/{clientId}', [DirectorController::class, 'agreementShow'])
        ->name('agreements.show');
    Route::post('/agreements/{clientId}/countersign', [DirectorController::class, 'agreementCountersign'])
        ->name('agreements.countersign');
    Route::get('/agreements/{clientId}/download', [DirectorController::class, 'agreementDownload'])
        ->name('agreements.download');


    // ── Audit Log ─────────────────────────────────────────────────────────
    Route::get('/audit', [DirectorController::class, 'auditIndex'])
        ->name('audit.index');

    // ── Complaints ─────────────────────────────────────────────────────────
    Route::get('/complaints', [ComplaintController::class, 'staffIndex'])
        ->name('complaints.index');
    Route::get('/complaints/{id}', [ComplaintController::class, 'staffShow'])
        ->name('complaints.show');
    Route::post('/complaints/{id}/respond', [ComplaintController::class, 'respond'])
        ->name('complaints.respond');
});