<?php

use Illuminate\Support\Facades\Route;
use Modules\MobileProvisioning\Http\Controllers\MobileProvisioningController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->prefix('v1')->group(function (): void {
    Route::get('mobile-provisioning/config', [MobileProvisioningController::class, 'show'])->middleware('identity.permission:mobile-provisioning.view');
    Route::put('mobile-provisioning/config', [MobileProvisioningController::class, 'upsert'])->middleware('identity.permission:mobile-provisioning.manage');
    Route::post('mobile-provisioning/publish', [MobileProvisioningController::class, 'publish'])->middleware('identity.permission:mobile-provisioning.manage');
    Route::post('mobile-provisioning/request-build', [MobileProvisioningController::class, 'requestBuild'])->middleware('identity.permission:mobile-provisioning.manage');
    Route::get('mobile-provisioning/requests', [MobileProvisioningController::class, 'requests'])->middleware('identity.permission:mobile-provisioning.view');
});
