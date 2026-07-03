<?php

use Illuminate\Support\Facades\Route;
use Modules\AI\Http\Controllers\AIGenerationController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->group(function (): void {
    Route::get('ai-generation-requests', [AIGenerationController::class, 'index'])->middleware('identity.permission:ai.request.view');
    Route::post('ai-generation-requests', [AIGenerationController::class, 'store'])->middleware('identity.permission:ai.request.create');
    Route::get('ai-generation-requests/{id}', [AIGenerationController::class, 'show'])->middleware('identity.permission:ai.request.view');
    Route::delete('ai-generation-requests/{id}', [AIGenerationController::class, 'destroy'])->middleware('identity.permission:ai.request.delete');
});
