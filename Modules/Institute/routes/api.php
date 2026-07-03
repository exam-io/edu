<?php

use Illuminate\Support\Facades\Route;
use Modules\Institute\Http\Controllers\AcademicSessionController;
use Modules\Institute\Http\Controllers\InstituteController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->prefix('v1')->group(function (): void {
    Route::post('/institutes/register', [InstituteController::class, 'register'])
        ->middleware('identity.permission:manage-institutes');

    Route::get('/institutes/current', [InstituteController::class, 'current']);
    Route::get('/institutes/{institute}', [InstituteController::class, 'show']);

    Route::put('/institutes/{institute}', [InstituteController::class, 'update'])
        ->middleware('identity.permission:manage-institutes');

    Route::patch('/institutes/{institute}/branding', [InstituteController::class, 'updateBranding'])
        ->middleware('identity.permission:manage-institutes');

    Route::get('/institutes/{institute}/onboarding', [InstituteController::class, 'onboarding']);

    Route::get('/institutes/{institute}/academic-sessions', [AcademicSessionController::class, 'index']);
    Route::post('/institutes/{institute}/academic-sessions', [AcademicSessionController::class, 'store'])
        ->middleware('identity.permission:manage-institutes');
    Route::get('/institutes/{institute}/academic-sessions/{academicSession}', [AcademicSessionController::class, 'show']);
    Route::match(['put', 'patch'], '/institutes/{institute}/academic-sessions/{academicSession}', [AcademicSessionController::class, 'update'])
        ->middleware('identity.permission:manage-institutes');
    Route::delete('/institutes/{institute}/academic-sessions/{academicSession}', [AcademicSessionController::class, 'destroy'])
        ->middleware('identity.permission:manage-institutes');
});
