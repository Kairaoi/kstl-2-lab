<?php

use App\Http\Controllers\Kstl\ClientController;
use App\Http\Controllers\Kstl\ComplaintController;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Kstl\ClientConsentController;
use App\Http\Controllers\Kstl\ClientResultController;
use App\Http\Controllers\Kstl\ClientInvoiceController;

// ── Public consent routes (no auth — token based) ────────────────────────────
Route::get('/consent/{token}', [ClientConsentController::class, 'show'])
    ->name('client.consent.show');

Route::post('/consent/{token}', [ClientConsentController::class, 'store'])
    ->name('client.consent.store');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'is.client',
])->prefix('client')->name('client.')->group(function () {

    Route::get('/dashboard', [ClientController::class, 'dashboard'])
        ->name('dashboard');

    // ── Company Profile (step 1 of onboarding) ────────────────────────────
    Route::get('/profile/company', [ClientController::class, 'companyProfileShow'])
        ->name('profile.company.show');
    Route::post('/profile/company', [ClientController::class, 'companyProfileStore'])
        ->name('profile.company.store');
    Route::put('/profile/company', [ClientController::class, 'companyProfileUpdate'])
        ->name('profile.company.update');

    // ── Service Agreement (step 2 of onboarding) ──────────────────────────
    Route::get('/agreement', [ClientController::class, 'agreementShow'])
        ->name('agreement.show');
    Route::post('/agreement/sign', [ClientController::class, 'agreementSign'])
        ->name('agreement.sign');
    Route::get('/agreement/download', [ClientController::class, 'agreementDownload'])
        ->name('agreement.download');

    // ── Submissions ────────────────────────────────────────────────────────
    Route::get('/submissions', [ClientController::class, 'submissionsIndex'])
        ->name('submissions.index');
    Route::get('/submissions/create', [ClientController::class, 'submissionsCreate'])
        ->name('submissions.create');
    Route::post('/submissions', [ClientController::class, 'submissionsStore'])
        ->name('submissions.store');
    Route::get('/submissions/{submission}', [ClientController::class, 'submissionsShow'])
        ->name('submissions.show');
    Route::get('/submissions/{submission}/edit', [ClientController::class, 'submissionsEdit'])
        ->name('submissions.edit');
    Route::put('/submissions/{submission}', [ClientController::class, 'submissionsUpdate'])
        ->name('submissions.update');
    Route::delete('/submissions/{submission}', [ClientController::class, 'submissionsDestroy'])
        ->name('submissions.destroy');

    // ── Notifications ─────────────────────────────────────────────────
    Route::get('/notifications', [ClientController::class, 'notificationsIndex'])
        ->name('notifications.index');
    Route::post('/notifications/{id}/read', [ClientController::class, 'notificationMarkRead'])
        ->name('notifications.read');
    Route::post('/notifications/read-all', [ClientController::class, 'notificationMarkAllRead'])
        ->name('notifications.read-all');

    // ── Invoices ──────────────────────────────────────────────────────
    Route::get('/invoices', [ClientInvoiceController::class, 'index'])
        ->name('invoices.index');
    Route::get('/invoices/{id}', [ClientInvoiceController::class, 'show'])
        ->name('invoices.show');

    // ── Results ────────────────────────────────────────────────────────────
    Route::get('/results', [ClientResultController::class, 'index'])
        ->name('results.index');
    Route::get('/results/{submission}', [ClientResultController::class, 'show'])
        ->name('results.show');
    Route::get('/results/{result}/download', [ClientController::class, 'resultsDownload'])
        ->name('results.download');

    // ── Notifications ─────────────────────────────────────────────────
    Route::get('/notifications', [ClientController::class, 'notificationsIndex'])
        ->name('notifications.index');
    Route::post('/notifications/{id}/read', [ClientController::class, 'notificationMarkRead'])
        ->name('notifications.read');
    Route::post('/notifications/read-all', [ClientController::class, 'notificationMarkAllRead'])
        ->name('notifications.read-all');

    // ── Invoices ───────────────────────────────────────────────────────────
    Route::get('/invoices', [ClientController::class, 'invoicesIndex'])
        ->name('invoices.index');
    Route::get('/invoices/{invoice}', [ClientController::class, 'invoicesShow'])
        ->name('invoices.show');
    Route::get('/invoices/{invoice}/download', [ClientController::class, 'invoicesDownload'])
        ->name('invoices.download');

    // ── Payments ───────────────────────────────────────────────────────────
    Route::get('/payments/{invoice}/proof', [ClientController::class, 'paymentProofShow'])
        ->name('payments.proof.show');
    Route::post('/payments/{invoice}/proof', [ClientController::class, 'paymentProofStore'])
        ->name('payments.proof.store');
    Route::get('/payments/{invoice}/proof/download', [ClientController::class, 'paymentProofDownload'])
        ->name('payments.proof.download');

    // ── Notifications ──────────────────────────────────────────────────────
    Route::get('/notifications', [ClientController::class, 'notificationsIndex'])
        ->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [ClientController::class, 'notificationMarkRead'])
        ->name('notifications.read');
    Route::patch('/notifications/read-all', [ClientController::class, 'notificationMarkAllRead'])
        ->name('notifications.read-all');

    // ── Complaints ─────────────────────────────────────────────────────────
    Route::get('/complaints', [ComplaintController::class, 'index'])
        ->name('complaints.index');
    Route::get('/complaints/create', [ComplaintController::class, 'create'])
        ->name('complaints.create');
    Route::post('/complaints', [ComplaintController::class, 'store'])
        ->name('complaints.store');
    Route::get('/complaints/{id}', [ComplaintController::class, 'show'])
        ->name('complaints.show');

    // ── Profile ────────────────────────────────────────────────────────────
    Route::get('/profile', [ClientController::class, 'profileShow'])
        ->name('profile.show');
    Route::put('/profile', [ClientController::class, 'profileUpdate'])
        ->name('profile.update');
    Route::put('/profile/password', [ClientController::class, 'profilePasswordUpdate'])
        ->name('profile.password.update');

    // ── Document Downloads ─────────────────────────────────────────────────
    Route::get('/submissions/{submission}/documents/{document}/download', [ClientController::class, 'documentDownload'])
        ->name('submissions.document.download');
});