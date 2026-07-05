<?php

namespace Modules\Monitoring\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Monitoring\Application\Contracts\MetricsServiceInterface;
use Modules\Monitoring\Application\DTOs\MetricsQueryData;
use Modules\Monitoring\Http\Requests\MetricsIndexRequest;
use Modules\Monitoring\Http\Requests\UpsertAlertRuleRequest;
use Modules\Monitoring\Http\Resources\AlertRuleResource;
use Modules\Monitoring\Http\Resources\MetricSnapshotResource;
use Modules\Monitoring\Jobs\AggregateMetricsJob;
use Modules\Tenant\Application\Services\TenantContextService;

class MonitoringController extends Controller
{
    public function metrics(
        MetricsIndexRequest $request,
        TenantContextService $tenantContext,
        MetricsServiceInterface $service,
    ): JsonResponse {
        $items = $service->metrics(
            $tenantContext->requiredTenantId(),
            MetricsQueryData::fromArray($request->validated()),
        );

        return response()->json([
            'success' => true,
            'data' => MetricSnapshotResource::collection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    public function aggregate(
        Request $request,
        TenantContextService $tenantContext,
    ): JsonResponse {
        AggregateMetricsJob::dispatch($tenantContext->requiredTenantId(), $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Metric aggregation queued.',
        ], 202);
    }

    public function upsertAlertRule(
        UpsertAlertRuleRequest $request,
        TenantContextService $tenantContext,
        MetricsServiceInterface $service,
    ): JsonResponse {
        $rule = $service->upsertAlertRule($tenantContext->requiredTenantId(), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Alert rule saved successfully.',
            'data' => new AlertRuleResource($rule),
        ]);
    }
}
