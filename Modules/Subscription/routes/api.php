<?php

use Illuminate\Support\Facades\Route;
use Modules\Subscription\Http\Controllers\SubscriptionController;

Route::middleware(['tenant', 'tenant.context', 'tenant.active', 'tenant.scope', 'auth:sanctum'])->prefix('v1')->group(function (): void {
    Route::get('subscription/plans', [SubscriptionController::class, 'plans'])->middleware('identity.permission:subscription.plan.view');
    Route::post('subscription/plans', [SubscriptionController::class, 'upsertPlan'])->middleware('identity.permission:subscription.plan.manage');

    Route::get('subscription/current', [SubscriptionController::class, 'current'])->middleware('identity.permission:subscription.view');
    Route::get('subscription/tenants', [SubscriptionController::class, 'tenantSubscriptions'])->middleware('identity.permission:subscription.view');
    Route::put('subscription/tenant', [SubscriptionController::class, 'upsertTenantSubscription'])->middleware('identity.permission:subscription.manage');
    Route::post('subscription/renew', [SubscriptionController::class, 'requestRenewal'])->middleware('identity.permission:subscription.manage');
});
