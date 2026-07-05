<?php

use Illuminate\Support\Facades\Route;
use Modules\FeatureManagement\Http\Controllers\FeatureManagementController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->prefix('v1')->group(function (): void {
    Route::get('feature-management/catalog', [FeatureManagementController::class, 'catalog'])->middleware('identity.permission:feature-management.view');
    Route::get('feature-management/flags', [FeatureManagementController::class, 'flags'])->middleware('identity.permission:feature-management.view');
    Route::put('feature-management/flags', [FeatureManagementController::class, 'upsertFlags'])->middleware('identity.permission:feature-management.manage');
    Route::get('feature-management/evaluate', [FeatureManagementController::class, 'evaluate'])->middleware('identity.permission:feature-management.view');
});
