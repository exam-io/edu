<?php

use Illuminate\Support\Facades\Route;
use Modules\Reporting\Http\Controllers\ReportingController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->group(function (): void {
    Route::get('reports/templates', [ReportingController::class, 'index'])->middleware('identity.permission:reporting.view');
    Route::post('reports/templates', [ReportingController::class, 'store'])->middleware('identity.permission:reporting.create');
    Route::post('reports/templates/{id}/run', [ReportingController::class, 'run'])->middleware('identity.permission:reporting.run');
    Route::post('reports/schedules', [ReportingController::class, 'schedule'])->middleware('identity.permission:reporting.schedule');
});
