<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\PaymentController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->prefix('v1')->group(function (): void {
    Route::get('payment/transactions', [PaymentController::class, 'transactions'])->middleware('identity.permission:payment.transaction.view');
    Route::post('payment/intents', [PaymentController::class, 'createIntent'])->middleware('identity.permission:payment.intent.manage');
    Route::post('payment/intents/{id}/capture', [PaymentController::class, 'captureIntent'])->middleware('identity.permission:payment.intent.manage');
    Route::post('payment/webhooks/{provider}', [PaymentController::class, 'receiveWebhook'])->middleware('identity.permission:payment.webhook.manage');
});
