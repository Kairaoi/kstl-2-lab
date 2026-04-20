<?php

use App\Http\Controllers\Kstl\ReceptionController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'is.reception',
])->prefix('reception')->name('reception.')->group(function () {

    Route::get('/dashboard', [ReceptionController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/submissions/{id}', [ReceptionController::class, 'show'])
        ->name('submissions.show');

    Route::post('/submissions/{id}/receive', [ReceptionController::class, 'markReceived'])
        ->name('submissions.receive');

    Route::get('/submissions/{id}/assess', [ReceptionController::class, 'assessForm'])
        ->name('submissions.assess');

    Route::post('/submissions/{id}/assess', [ReceptionController::class, 'assessStore'])
        ->name('submissions.assess.store');

    Route::get('/submissions/{id}/consent', [ReceptionController::class, 'consentForm'])
        ->name('submissions.consent');

    Route::post('/assessments/{id}/notify', [ReceptionController::class, 'notifyClient'])
        ->name('assessments.notify');

    Route::post('/assessments/{id}/consent', [ReceptionController::class, 'recordConsent'])
        ->name('assessments.consent');

    Route::post('/submissions/{id}/send-to-testing', [ReceptionController::class, 'sendToTesting'])
        ->name('submissions.send-to-testing');

});