<?php

use Illuminate\Support\Facades\Route;
use Modules\SaaS\Http\Controllers\SaaSController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->prefix('v1')->group(function (): void {
    Route::get('saas/dashboard', [SaaSController::class, 'dashboard'])->middleware('identity.permission:saas.dashboard.view');
    Route::get('saas/usage', [SaaSController::class, 'usage'])->middleware('identity.permission:saas.usage.view');
    Route::post('saas/usage/track', [SaaSController::class, 'track'])->middleware('identity.permission:saas.usage.manage');
    Route::post('saas/snapshots', [SaaSController::class, 'snapshot'])->middleware('identity.permission:saas.dashboard.manage');
});
