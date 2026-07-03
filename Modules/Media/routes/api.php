<?php

use Illuminate\Support\Facades\Route;
use Modules\Media\Http\Controllers\MediaController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->group(function (): void {
    Route::get('media', [MediaController::class, 'index'])->middleware('identity.permission:media.view');
    Route::post('media', [MediaController::class, 'store'])->middleware('identity.permission:media.create');
    Route::get('media/{id}', [MediaController::class, 'show'])->middleware('identity.permission:media.view');
    Route::put('media/{id}', [MediaController::class, 'update'])->middleware('identity.permission:media.update');
    Route::delete('media/{id}', [MediaController::class, 'destroy'])->middleware('identity.permission:media.delete');
});
