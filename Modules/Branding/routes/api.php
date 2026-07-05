<?php

use Illuminate\Support\Facades\Route;
use Modules\Branding\Http\Controllers\BrandingController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope'])->prefix('v1')->group(function (): void {
    Route::get('branding/current', [BrandingController::class, 'show']);

    Route::middleware(['auth:sanctum'])->group(function (): void {
        Route::put('branding/current', [BrandingController::class, 'update'])->middleware('identity.permission:branding.manage');
        Route::get('branding/theme', [BrandingController::class, 'theme'])->middleware('identity.permission:branding.view');
    });
});
