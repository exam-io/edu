<?php

namespace Modules\FeatureManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\FeatureManagement\Application\Contracts\FeatureFlagServiceInterface;
use Modules\FeatureManagement\Application\DTOs\FeatureFlagMutationData;
use Modules\FeatureManagement\Http\Requests\UpsertFeatureFlagsRequest;
use Modules\FeatureManagement\Http\Resources\FeatureFlagResource;
use Modules\Tenant\Application\Services\TenantContextService;

class FeatureManagementController extends Controller
{
    public function catalog(FeatureFlagServiceInterface $service): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $service->catalog(),
        ]);
    }

    public function flags(TenantContextService $tenantContext, FeatureFlagServiceInterface $service): JsonResponse
    {
        $flags = $service->flags($tenantContext->requiredTenantId());

        return response()->json([
            'success' => true,
            'data' => FeatureFlagResource::collection($flags),
        ]);
    }

    public function upsertFlags(
        UpsertFeatureFlagsRequest $request,
        TenantContextService $tenantContext,
        FeatureFlagServiceInterface $service,
    ): JsonResponse {
        $flags = $service->upsert(
            $tenantContext->requiredTenantId(),
            FeatureFlagMutationData::fromArray($request->validated()),
        );

        return response()->json([
            'success' => true,
            'message' => 'Feature flags updated successfully.',
            'data' => FeatureFlagResource::collection($flags),
        ]);
    }

    public function evaluate(Request $request, TenantContextService $tenantContext, FeatureFlagServiceInterface $service): JsonResponse
    {
        $request->validate(['feature_key' => ['required', 'string', 'max:120']]);

        $enabled = $service->evaluate($tenantContext->requiredTenantId(), (string) $request->query('feature_key'));

        return response()->json([
            'success' => true,
            'data' => [
                'feature_key' => (string) $request->query('feature_key'),
                'enabled' => $enabled,
            ],
        ]);
    }
}
