<?php

use Illuminate\Support\Facades\Route;
use Modules\Tenant\Http\Controllers\TenantController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope'])->prefix('v1')->group(function () {
    // Public endpoints (tenant resolved by middleware, no auth required)
    Route::get('/tenants/current', [TenantController::class, 'current'])->name('current');

    // Protected endpoints
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/tenants/provision', [TenantController::class, 'provision'])->name('provision');
        Route::get('/tenants/{id}', [TenantController::class, 'show'])->name('show');
        Route::put('/tenants/{id}/settings', [TenantController::class, 'updateSettings'])->name('updateSettings');
    });
});
