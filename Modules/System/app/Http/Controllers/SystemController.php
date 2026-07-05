<?php

namespace Modules\System\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\System\Application\Contracts\SystemHealthServiceInterface;
use Modules\System\Application\Contracts\SystemSecurityServiceInterface;
use Modules\System\Application\DTOs\SecurityPolicyData;
use Modules\System\Http\Requests\HealthChecksRequest;
use Modules\System\Http\Requests\UpdateSecurityPolicyRequest;
use Modules\System\Http\Resources\SystemHealthCheckResource;
use Modules\System\Http\Resources\SystemSecurityPolicyResource;
use Modules\System\Jobs\RunSystemHealthChecksJob;
use Modules\Tenant\Application\Services\TenantContextService;

class SystemController extends Controller
{
    public function securityPolicy(TenantContextService $tenantContext, SystemSecurityServiceInterface $service): JsonResponse
    {
        $policy = $service->current($tenantContext->requiredTenantId());

        return response()->json([
            'success' => true,
            'data' => $policy ? new SystemSecurityPolicyResource($policy) : null,
        ]);
    }

    public function updateSecurityPolicy(
        UpdateSecurityPolicyRequest $request,
        TenantContextService $tenantContext,
        SystemSecurityServiceInterface $service,
    ): JsonResponse {
        $policy = $service->update(
            $tenantContext->requiredTenantId(),
            SecurityPolicyData::fromArray($request->validated()),
        );

        return response()->json([
            'success' => true,
            'message' => 'Security policy updated successfully.',
            'data' => new SystemSecurityPolicyResource($policy),
        ]);
    }

    public function healthChecks(
        HealthChecksRequest $request,
        TenantContextService $tenantContext,
        SystemHealthServiceInterface $service,
    ): JsonResponse {
        $tenantId = $tenantContext->requiredTenantId();
        $refresh = (bool) $request->validated('refresh', false);

        if ($refresh) {
            RunSystemHealthChecksJob::dispatch($tenantId);
        }

        return response()->json([
            'success' => true,
            'data' => SystemHealthCheckResource::collection($service->latest($tenantId)),
        ]);
    }
}
