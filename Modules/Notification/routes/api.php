<?php

use Illuminate\Support\Facades\Route;
use Modules\Notification\Http\Controllers\NotificationController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->group(function (): void {
    Route::get('notifications', [NotificationController::class, 'index'])->middleware('identity.permission:notification.view');
    Route::post('notifications/device-tokens', [NotificationController::class, 'registerDeviceToken'])->middleware('identity.permission:notification.device_token.manage');
    Route::post('notifications/{id}/read', [NotificationController::class, 'markRead'])->middleware('identity.permission:notification.read');
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->middleware('identity.permission:notification.view');
});
