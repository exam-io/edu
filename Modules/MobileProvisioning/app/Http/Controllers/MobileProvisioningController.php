<?php

namespace Modules\MobileProvisioning\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\MobileProvisioning\Application\Contracts\MobileConfigServiceInterface;
use Modules\MobileProvisioning\Application\DTOs\MobileConfigMutationData;
use Modules\MobileProvisioning\Http\Requests\RequestProvisioningBuildRequest;
use Modules\MobileProvisioning\Http\Requests\UpsertMobileConfigRequest;
use Modules\MobileProvisioning\Http\Resources\MobileConfigResource;
use Modules\MobileProvisioning\Http\Resources\MobileProvisioningRequestResource;
use Modules\Tenant\Application\Services\TenantContextService;

class MobileProvisioningController extends Controller
{
    public function show(TenantContextService $tenantContext, MobileConfigServiceInterface $service): JsonResponse
    {
        $config = $service->current($tenantContext->requiredTenantId());

        return response()->json([
            'success' => true,
            'data' => new MobileConfigResource($config),
        ]);
    }

    public function upsert(
        UpsertMobileConfigRequest $request,
        TenantContextService $tenantContext,
        MobileConfigServiceInterface $service,
    ): JsonResponse {
        $config = $service->upsert(
            $tenantContext->requiredTenantId(),
            MobileConfigMutationData::fromArray($request->validated()),
        );

        return response()->json([
            'success' => true,
            'message' => 'Mobile configuration updated successfully.',
            'data' => new MobileConfigResource($config),
        ]);
    }

    public function publish(TenantContextService $tenantContext, MobileConfigServiceInterface $service): JsonResponse
    {
        $userId = (int) auth()->id();
        $config = $service->publish($tenantContext->requiredTenantId(), $userId);

        return response()->json([
            'success' => true,
            'message' => 'Mobile configuration published successfully.',
            'data' => new MobileConfigResource($config),
        ]);
    }

    public function requestBuild(
        RequestProvisioningBuildRequest $request,
        TenantContextService $tenantContext,
        MobileConfigServiceInterface $service,
    ): JsonResponse {
        $requestId = $service->requestBuild(
            $tenantContext->requiredTenantId(),
            (string) $request->validated('platform'),
            (int) auth()->id(),
            $request->validated('notes'),
        );

        return response()->json([
            'success' => true,
            'message' => 'Build request queued successfully.',
            'data' => ['request_id' => $requestId],
        ], 201);
    }

    public function requests(TenantContextService $tenantContext, MobileConfigServiceInterface $service): JsonResponse
    {
        $requests = $service->requests($tenantContext->requiredTenantId());

        return response()->json([
            'success' => true,
            'data' => MobileProvisioningRequestResource::collection($requests),
        ]);
    }
}
