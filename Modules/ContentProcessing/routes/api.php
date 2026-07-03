<?php

use Illuminate\Support\Facades\Route;
use Modules\ContentProcessing\Http\Controllers\ContentSourceController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->group(function (): void {
    Route::get('content-sources', [ContentSourceController::class, 'index'])->middleware('identity.permission:content.processing.view');
    Route::post('content-sources', [ContentSourceController::class, 'store'])->middleware('identity.permission:content.processing.create');
    Route::get('content-sources/{id}', [ContentSourceController::class, 'show'])->middleware('identity.permission:content.processing.view');
    Route::delete('content-sources/{id}', [ContentSourceController::class, 'destroy'])->middleware('identity.permission:content.processing.delete');
    Route::post('content-sources/{id}/retry', [ContentSourceController::class, 'retry'])->middleware('identity.permission:content.processing.update');
});
