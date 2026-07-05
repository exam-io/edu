<?php

use Illuminate\Support\Facades\Route;
use Modules\Insights\Http\Controllers\InsightsController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->group(function (): void {
    Route::get('insights', [InsightsController::class, 'index'])->middleware('identity.permission:insights.view');
    Route::post('insights/generate', [InsightsController::class, 'generate'])->middleware('identity.permission:insights.generate');
});
