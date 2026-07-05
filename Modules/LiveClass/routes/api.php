<?php

use Illuminate\Support\Facades\Route;
use Modules\LiveClass\Http\Controllers\LiveClassController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->group(function (): void {
    Route::get('live-classes', [LiveClassController::class, 'index'])->middleware('identity.permission:live_class.view');
    Route::post('live-classes', [LiveClassController::class, 'store'])->middleware('identity.permission:live_class.create');
    Route::get('live-classes/{id}', [LiveClassController::class, 'show'])->middleware('identity.permission:live_class.view');
    Route::put('live-classes/{id}', [LiveClassController::class, 'update'])->middleware('identity.permission:live_class.update');
    Route::delete('live-classes/{id}', [LiveClassController::class, 'destroy'])->middleware('identity.permission:live_class.delete');
    Route::post('live-classes/{id}/start', [LiveClassController::class, 'start'])->middleware('identity.permission:live_class.start');
    Route::post('live-classes/{id}/join', [LiveClassController::class, 'join'])->middleware('identity.permission:live_class.join');
    Route::post('live-classes/{id}/end', [LiveClassController::class, 'end'])->middleware('identity.permission:live_class.start');
    Route::get('live-classes/{id}/attendance', [LiveClassController::class, 'attendance'])->middleware('identity.permission:live_class.attendance.view');
});
