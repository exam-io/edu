<?php

use Illuminate\Support\Facades\Route;
use Modules\Operations\Http\Controllers\OperationsController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->prefix('v1')->group(function (): void {
    Route::get('operations/backups/latest', [OperationsController::class, 'latestBackup'])->middleware('identity.permission:operations.backup.view');
    Route::post('operations/backups/trigger', [OperationsController::class, 'triggerBackup'])->middleware('identity.permission:operations.backup.run');
    Route::get('operations/queue/logs', [OperationsController::class, 'queueLogs'])->middleware('identity.permission:operations.view');
    Route::post('operations/queue/logs', [OperationsController::class, 'recordQueueOperation'])->middleware('identity.permission:operations.queue.manage');
});
