<?php

use Illuminate\Support\Facades\Route;
use Modules\CRM\Http\Controllers\LeadController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->group(function (): void {
    Route::get('crm/leads', [LeadController::class, 'index'])->middleware('identity.permission:crm.lead.view');
    Route::post('crm/leads', [LeadController::class, 'store'])->middleware('identity.permission:crm.lead.create');
    Route::get('crm/leads/{id}', [LeadController::class, 'show'])->middleware('identity.permission:crm.lead.view');
    Route::put('crm/leads/{id}', [LeadController::class, 'update'])->middleware('identity.permission:crm.lead.update');
    Route::delete('crm/leads/{id}', [LeadController::class, 'destroy'])->middleware('identity.permission:crm.lead.delete');
});
