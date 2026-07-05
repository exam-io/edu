<?php

namespace Modules\WhiteLabel\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Tenant\Application\Services\TenantContextService;
use Modules\WhiteLabel\Application\Contracts\DomainServiceInterface;
use Modules\WhiteLabel\Application\Contracts\NavigationServiceInterface;
use Modules\WhiteLabel\Application\DTOs\DomainMutationData;
use Modules\WhiteLabel\Application\DTOs\NavigationMutationData;
use Modules\WhiteLabel\Http\Requests\StoreDomainRequest;
use Modules\WhiteLabel\Http\Requests\UpdateDomainRequest;
use Modules\WhiteLabel\Http\Requests\UpsertNavigationRequest;
use Modules\WhiteLabel\Http\Resources\DomainResource;
use Modules\WhiteLabel\Http\Resources\NavigationConfigResource;

class WhiteLabelController extends Controller
{
    public function domains(TenantContextService $tenantContext, DomainServiceInterface $domainService): JsonResponse
    {
        $domains = $domainService->list($tenantContext->requiredTenantId());

        return response()->json([
            'success' => true,
            'data' => DomainResource::collection($domains),
        ]);
    }

    public function storeDomain(
        StoreDomainRequest $request,
        TenantContextService $tenantContext,
        DomainServiceInterface $domainService,
    ): JsonResponse {
        $domain = $domainService->create(
            $tenantContext->requiredTenantId(),
            DomainMutationData::fromArray($request->validated()),
        );

        return response()->json([
            'success' => true,
            'message' => 'Domain mapped successfully.',
            'data' => new DomainResource($domain),
        ], 201);
    }

    public function updateDomain(
        int $id,
        UpdateDomainRequest $request,
        TenantContextService $tenantContext,
        DomainServiceInterface $domainService,
    ): JsonResponse {
        $domain = $domainService->update(
            $tenantContext->requiredTenantId(),
            $id,
            DomainMutationData::fromArray($request->validated()),
        );

        return response()->json([
            'success' => true,
            'message' => 'Domain updated successfully.',
            'data' => new DomainResource($domain),
        ]);
    }

    public function navigation(TenantContextService $tenantContext, NavigationServiceInterface $navigationService): JsonResponse
    {
        $config = $navigationService->current($tenantContext->requiredTenantId());

        return response()->json([
            'success' => true,
            'data' => new NavigationConfigResource($config),
        ]);
    }

    public function upsertNavigation(
        UpsertNavigationRequest $request,
        TenantContextService $tenantContext,
        NavigationServiceInterface $navigationService,
    ): JsonResponse {
        $config = $navigationService->upsert(
            $tenantContext->requiredTenantId(),
            NavigationMutationData::fromArray($request->validated()),
        );

        return response()->json([
            'success' => true,
            'message' => 'Navigation updated successfully.',
            'data' => new NavigationConfigResource($config),
        ]);
    }
}
