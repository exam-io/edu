<?php

use Illuminate\Support\Facades\Route;
use Modules\Admissions\Http\Controllers\AdmissionApplicationController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->group(function (): void {
    Route::get('admissions/applications', [AdmissionApplicationController::class, 'index'])->middleware('identity.permission:admissions.application.view');
    Route::post('admissions/applications', [AdmissionApplicationController::class, 'store'])->middleware('identity.permission:admissions.application.create');
    Route::get('admissions/applications/{id}', [AdmissionApplicationController::class, 'show'])->middleware('identity.permission:admissions.application.view');
    Route::post('admissions/applications/{id}/status', [AdmissionApplicationController::class, 'changeStatus'])->middleware('identity.permission:admissions.application.update');
    Route::delete('admissions/applications/{id}', [AdmissionApplicationController::class, 'destroy'])->middleware('identity.permission:admissions.application.delete');
});
