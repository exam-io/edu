<?php

use Illuminate\Support\Facades\Route;
use Modules\System\Http\Controllers\SystemController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->prefix('v1')->group(function (): void {
    Route::get('system/security-policy', [SystemController::class, 'securityPolicy'])->middleware('identity.permission:system.security.view');
    Route::put('system/security-policy', [SystemController::class, 'updateSecurityPolicy'])->middleware('identity.permission:system.security.manage');
    Route::get('system/health-checks', [SystemController::class, 'healthChecks'])->middleware('identity.permission:system.health.view');
});
