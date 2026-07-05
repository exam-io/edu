<?php

use Illuminate\Support\Facades\Route;
use Modules\Monitoring\Http\Controllers\MonitoringController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->prefix('v1')->group(function (): void {
    Route::get('monitoring/metrics', [MonitoringController::class, 'metrics'])->middleware('identity.permission:monitoring.view');
    Route::post('monitoring/metrics/aggregate', [MonitoringController::class, 'aggregate'])->middleware('identity.permission:monitoring.manage');
    Route::post('monitoring/alert-rules', [MonitoringController::class, 'upsertAlertRule'])->middleware('identity.permission:monitoring.alert.manage');
});
