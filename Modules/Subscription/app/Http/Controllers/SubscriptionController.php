<?php

namespace Modules\Subscription\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Subscription\Application\Contracts\SubscriptionServiceInterface;
use Modules\Subscription\Application\DTOs\PlanMutationData;
use Modules\Subscription\Application\DTOs\PlanQueryData;
use Modules\Subscription\Application\DTOs\TenantSubscriptionMutationData;
use Modules\Subscription\Http\Requests\PlanIndexRequest;
use Modules\Subscription\Http\Requests\UpsertPlanRequest;
use Modules\Subscription\Http\Requests\UpsertTenantSubscriptionRequest;
use Modules\Subscription\Http\Resources\SubscriptionPlanResource;
use Modules\Subscription\Http\Resources\TenantSubscriptionResource;
use Modules\Tenant\Application\Services\TenantContextService;

class SubscriptionController extends Controller
{
    public function plans(PlanIndexRequest $request, SubscriptionServiceInterface $service): JsonResponse
    {
        $items = $service->plans(PlanQueryData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'data' => SubscriptionPlanResource::collection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    public function upsertPlan(UpsertPlanRequest $request, SubscriptionServiceInterface $service): JsonResponse
    {
        $plan = $service->upsertPlan(PlanMutationData::fromArray($request->validated()));

        return response()->json([
            'success' => true,
            'message' => 'Subscription plan saved successfully.',
            'data' => new SubscriptionPlanResource($plan),
        ]);
    }

    public function current(TenantContextService $tenantContext, SubscriptionServiceInterface $service): JsonResponse
    {
        $subscription = $service->currentSubscription($tenantContext->requiredTenantId());

        return response()->json([
            'success' => true,
            'data' => $subscription ? new TenantSubscriptionResource($subscription->load('plan')) : null,
        ]);
    }

    public function tenantSubscriptions(
        PlanIndexRequest $request,
        TenantContextService $tenantContext,
        SubscriptionServiceInterface $service,
    ): JsonResponse {
        $items = $service->tenantSubscriptions(
            $tenantContext->requiredTenantId(),
            PlanQueryData::fromArray($request->validated()),
        );

        return response()->json([
            'success' => true,
            'data' => TenantSubscriptionResource::collection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    public function upsertTenantSubscription(
        UpsertTenantSubscriptionRequest $request,
        TenantContextService $tenantContext,
        SubscriptionServiceInterface $service,
    ): JsonResponse {
        $subscription = $service->upsertTenantSubscription(
            $tenantContext->requiredTenantId(),
            TenantSubscriptionMutationData::fromArray($request->validated()),
        );

        return response()->json([
            'success' => true,
            'message' => 'Tenant subscription updated successfully.',
            'data' => new TenantSubscriptionResource($subscription),
        ]);
    }

    public function requestRenewal(TenantContextService $tenantContext, SubscriptionServiceInterface $service): JsonResponse
    {
        $subscription = $service->requestRenewal($tenantContext->requiredTenantId());

        return response()->json([
            'success' => true,
            'message' => $subscription ? 'Subscription renewal queued.' : 'No active subscription found.',
            'data' => $subscription ? new TenantSubscriptionResource($subscription->load('plan')) : null,
        ], $subscription ? 202 : 200);
    }
}
