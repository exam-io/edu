<?php

use Illuminate\Support\Facades\Route;
use Modules\Billing\Http\Controllers\BillingController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->prefix('v1')->group(function (): void {
    Route::get('billing/center', [BillingController::class, 'center'])->middleware('identity.permission:billing.view');
    Route::get('billing/invoices', [BillingController::class, 'invoices'])->middleware('identity.permission:billing.invoice.view');
    Route::get('billing/invoices/{id}', [BillingController::class, 'showInvoice'])->middleware('identity.permission:billing.invoice.view');
    Route::post('billing/invoices/generate', [BillingController::class, 'generateInvoice'])->middleware('identity.permission:billing.invoice.manage');
    Route::put('billing/profile', [BillingController::class, 'upsertProfile'])->middleware('identity.permission:billing.manage');
});
