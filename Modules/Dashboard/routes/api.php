<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Controllers\DashboardController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->group(function (): void {
    Route::get('dashboards/me', [DashboardController::class, 'me'])->middleware('identity.permission:dashboard.view');
    Route::put('dashboards/preferences', [DashboardController::class, 'updatePreferences'])->middleware('identity.permission:dashboard.configure');
});
