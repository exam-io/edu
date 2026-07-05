<?php

use Illuminate\Support\Facades\Route;
use Modules\Communication\Http\Controllers\AnnouncementController;
use Modules\Communication\Http\Controllers\CommunicationHistoryController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->group(function (): void {
    Route::get('communications/announcements', [AnnouncementController::class, 'index'])->middleware('identity.permission:communication.announcement.view');
    Route::post('communications/announcements', [AnnouncementController::class, 'store'])->middleware('identity.permission:communication.announcement.create');
    Route::post('communications/announcements/{id}/publish', [AnnouncementController::class, 'publish'])->middleware('identity.permission:communication.announcement.publish');

    Route::get('communications/history', [CommunicationHistoryController::class, 'index'])->middleware('identity.permission:communication.history.view');
});
