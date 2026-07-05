<?php

use Illuminate\Support\Facades\Route;
use Modules\Analytics\Http\Controllers\AnalyticsController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->group(function (): void {
    Route::get('analytics/events', [AnalyticsController::class, 'index'])->middleware('identity.permission:analytics.view');
    Route::post('analytics/events', [AnalyticsController::class, 'track'])->middleware('identity.permission:analytics.track');
    Route::get('analytics/metrics', [AnalyticsController::class, 'metrics'])->middleware('identity.permission:analytics.view');
});
