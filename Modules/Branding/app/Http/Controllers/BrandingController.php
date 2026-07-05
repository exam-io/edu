<?php

namespace Modules\Branding\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Branding\Application\Contracts\BrandingServiceInterface;
use Modules\Branding\Application\DTOs\BrandingMutationData;
use Modules\Branding\Http\Requests\UpdateBrandingRequest;
use Modules\Branding\Http\Resources\BrandingResource;
use Modules\Branding\Http\Resources\ThemeResource;
use Modules\Tenant\Application\Services\TenantContextService;

class BrandingController extends Controller
{
    public function show(TenantContextService $tenantContext, BrandingServiceInterface $service): JsonResponse
    {
        $profile = $service->current($tenantContext->requiredTenantId());

        return response()->json([
            'success' => true,
            'data' => new BrandingResource($profile),
        ]);
    }

    public function update(
        UpdateBrandingRequest $request,
        TenantContextService $tenantContext,
        BrandingServiceInterface $service,
    ): JsonResponse {
        $profile = $service->upsert(
            $tenantContext->requiredTenantId(),
            BrandingMutationData::fromArray($request->validated()),
        );

        return response()->json([
            'success' => true,
            'message' => 'Branding updated successfully.',
            'data' => new BrandingResource($profile),
        ]);
    }

    public function theme(TenantContextService $tenantContext, BrandingServiceInterface $service): JsonResponse
    {
        $theme = $service->resolveTheme($tenantContext->requiredTenantId());

        return response()->json([
            'success' => true,
            'data' => new ThemeResource($theme),
        ]);
    }
}
