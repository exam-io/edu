<?php

use Illuminate\Support\Facades\Route;
use Modules\Audit\Http\Controllers\AuditController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->prefix('v1')->group(function (): void {
    Route::get('audit/logs', [AuditController::class, 'logs'])->middleware('identity.permission:audit.view');
    Route::post('audit/logs', [AuditController::class, 'store'])->middleware('identity.permission:audit.create');
});
