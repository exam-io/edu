<?php

use Illuminate\Support\Facades\Route;
use Modules\WhiteLabel\Http\Controllers\WhiteLabelController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->prefix('v1')->group(function (): void {
    Route::get('white-label/domains', [WhiteLabelController::class, 'domains'])->middleware('identity.permission:white-label.domain.view');
    Route::post('white-label/domains', [WhiteLabelController::class, 'storeDomain'])->middleware('identity.permission:white-label.domain.manage');
    Route::put('white-label/domains/{id}', [WhiteLabelController::class, 'updateDomain'])->middleware('identity.permission:white-label.domain.manage');

    Route::get('white-label/navigation', [WhiteLabelController::class, 'navigation'])->middleware('identity.permission:white-label.navigation.view');
    Route::put('white-label/navigation', [WhiteLabelController::class, 'upsertNavigation'])->middleware('identity.permission:white-label.navigation.manage');
});
