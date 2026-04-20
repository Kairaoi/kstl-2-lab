<?php

use App\Http\Controllers\Kstl\AnalystController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:analyst|admin|super_admin',
])->prefix('analyst')->name('analyst.')->group(function () {

    Route::get('/dashboard', [AnalystController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/tests', [AnalystController::class, 'index'])
        ->name('tests.index');

    Route::get('/tests/{id}', [AnalystController::class, 'show'])
        ->name('tests.show');

    Route::post('/tests/{id}/result', [AnalystController::class, 'saveResult'])
        ->name('tests.result');

});