<?php

namespace Modules\SaaS\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\SaaS\Application\Contracts\SaaSServiceInterface;
use Modules\SaaS\Application\DTOs\TrackUsageData;
use Modules\SaaS\Application\DTOs\UsageQueryData;
use Modules\SaaS\Http\Requests\TrackUsageRequest;
use Modules\SaaS\Http\Requests\UsageIndexRequest;
use Modules\SaaS\Http\Resources\UsageCounterResource;
use Modules\SaaS\Http\Resources\UsageSnapshotResource;
use Modules\Tenant\Application\Services\TenantContextService;

class SaaSController extends Controller
{
    public function dashboard(TenantContextService $tenantContext, SaaSServiceInterface $service): JsonResponse
    {
        $data = $service->dashboard($tenantContext->requiredTenantId());

        return response()->json([
            'success' => true,
            'data' => [
                'mrr' => $data['mrr'],
                'arr' => $data['arr'],
                'active_subscribers' => $data['active_subscribers'],
                'latest_snapshot' => $data['latest_snapshot']
                    ? new UsageSnapshotResource($data['latest_snapshot'])
                    : null,
            ],
        ]);
    }

    public function usage(
        UsageIndexRequest $request,
        TenantContextService $tenantContext,
        SaaSServiceInterface $service,
    ): JsonResponse {
        $items = $service->usage(
            $tenantContext->requiredTenantId(),
            UsageQueryData::fromArray($request->validated()),
        );

        return response()->json([
            'success' => true,
            'data' => UsageCounterResource::collection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    public function track(
        TrackUsageRequest $request,
        TenantContextService $tenantContext,
        SaaSServiceInterface $service,
    ): JsonResponse {
        $counter = $service->trackUsage(
            $tenantContext->requiredTenantId(),
            TrackUsageData::fromArray($request->validated()),
        );

        return response()->json([
            'success' => true,
            'message' => 'Usage tracked successfully.',
            'data' => new UsageCounterResource($counter),
        ]);
    }

    public function snapshot(
        Request $request,
        TenantContextService $tenantContext,
        SaaSServiceInterface $service,
    ): JsonResponse {
        $snapshot = $service->requestSnapshot(
            $tenantContext->requiredTenantId(),
            $request->input('snapshot_date'),
        );

        return response()->json([
            'success' => true,
            'message' => 'Usage snapshot queued.',
            'data' => new UsageSnapshotResource($snapshot),
        ], 202);
    }
}
