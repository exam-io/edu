<?php

namespace Modules\Tenant\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Tenant\Application\Services\TenantBrandingService;
use Modules\Tenant\Application\Services\TenantConfigurationService;
use Modules\Tenant\Application\Services\TenantContextService;
use Modules\Tenant\Application\Services\ProvisionTenantDto;
use Modules\Tenant\Application\Services\TenantProvisioningService;
use Modules\Tenant\Http\Requests\ProvisionTenantRequest;
use Modules\Tenant\Http\Resources\TenantResource;
use Modules\Tenant\Http\Resources\TenantSettingResource;

class TenantController extends Controller
{
    /**
     * Get current tenant with full context.
     * Public endpoint - no auth required (tenant is resolved by middleware).
     */
    public function current(
        TenantContextService $contextService,
        TenantBrandingService $brandingService,
        TenantConfigurationService $configurationService,
    ): JsonResponse {
        $tenant = $contextService->tenant();

        if ($tenant === null) {
            return response()->json([
                'success' => false,
                'message' => 'No tenant resolved.',
            ], 404);
        }

        $branding = $brandingService->getBranding($tenant);
        $configuration = $configurationService->getConfiguration($tenant);

        return response()->json([
            'success' => true,
            'data' => [
                'tenant' => new TenantResource($tenant->load('settings')),
                'branding' => $branding->toArray(),
                'configuration' => $configuration->toArray(),
            ],
        ]);
    }

    /**
     * Get a specific tenant by ID.
     */
    public function show(
        int $id,
        TenantContextService $contextService,
    ): JsonResponse {
        $tenant = $contextService->tenant();

        if ($tenant === null || $tenant->id !== $id) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new TenantResource($tenant->load('settings')),
        ]);
    }

    /**
     * Provision a new tenant.
     */
    public function provision(
        ProvisionTenantRequest $request,
        TenantProvisioningService $provisioningService,
    ): JsonResponse {
        $dto = new ProvisionTenantDto(
            name: $request->validated('name'),
            slug: $request->validated('slug'),
            domain: $request->validated('domain'),
            customDomain: $request->validated('custom_domain'),
            plan: $request->validated('plan'),
            theme: $request->validated('theme'),
            language: $request->validated('language'),
            timezone: $request->validated('timezone'),
            primaryColor: $request->validated('primary_color'),
            secondaryColor: $request->validated('secondary_color'),
        );

        $tenant = $provisioningService->provision($dto);

        return response()->json([
            'success' => true,
            'message' => 'Tenant provisioned successfully.',
            'data' => new TenantResource($tenant),
        ], 201);
    }

    /**
     * Update tenant settings.
     */
    public function updateSettings(
        ProvisionTenantRequest $request,
        TenantContextService $contextService,
        TenantBrandingService $brandingService,
    ): JsonResponse {
        $tenant = $contextService->requiredTenant();

        $settings = $tenant->settings;
        if ($settings === null) {
            return response()->json([
                'success' => false,
                'message' => 'Tenant settings not found.',
            ], 404);
        }

        $settings->update(array_filter([
            'theme' => $request->validated('theme'),
            'language' => $request->validated('language'),
            'timezone' => $request->validated('timezone'),
            'primary_color' => $request->validated('primary_color'),
            'secondary_color' => $request->validated('secondary_color'),
            'logo' => $request->validated('logo'),
            'favicon' => $request->validated('favicon'),
        ], fn ($value) => $value !== null));

        $brandingService->invalidateBrandingCache($tenant);

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully.',
            'data' => new TenantSettingResource($settings),
        ]);
    }
}
