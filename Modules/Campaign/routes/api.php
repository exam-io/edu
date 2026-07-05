<?php

use Illuminate\Support\Facades\Route;
use Modules\Campaign\Http\Controllers\CampaignController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->group(function (): void {
    Route::get('campaigns', [CampaignController::class, 'index'])->middleware('identity.permission:campaign.view');
    Route::post('campaigns', [CampaignController::class, 'store'])->middleware('identity.permission:campaign.create');
    Route::get('campaigns/{id}', [CampaignController::class, 'show'])->middleware('identity.permission:campaign.view');
    Route::put('campaigns/{id}', [CampaignController::class, 'update'])->middleware('identity.permission:campaign.update');
    Route::post('campaigns/{id}/launch', [CampaignController::class, 'launch'])->middleware('identity.permission:campaign.launch');
    Route::delete('campaigns/{id}', [CampaignController::class, 'destroy'])->middleware('identity.permission:campaign.delete');
});
