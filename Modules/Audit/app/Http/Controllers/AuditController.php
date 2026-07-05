<?php

namespace Modules\Audit\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Audit\Application\Contracts\AuditServiceInterface;
use Modules\Audit\Application\DTOs\AuditQueryData;
use Modules\Audit\Application\DTOs\AuditRecordData;
use Modules\Audit\Http\Requests\AuditIndexRequest;
use Modules\Audit\Http\Requests\StoreAuditLogRequest;
use Modules\Audit\Http\Resources\AuditLogResource;
use Modules\Tenant\Application\Services\TenantContextService;

class AuditController extends Controller
{
    public function logs(
        AuditIndexRequest $request,
        TenantContextService $tenantContext,
        AuditServiceInterface $service,
    ): JsonResponse {
        $items = $service->logs(
            $tenantContext->requiredTenantId(),
            AuditQueryData::fromArray($request->validated()),
        );

        return response()->json([
            'success' => true,
            'data' => AuditLogResource::collection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    public function store(
        StoreAuditLogRequest $request,
        TenantContextService $tenantContext,
        AuditServiceInterface $service,
    ): JsonResponse {
        $record = $service->record(
            $tenantContext->requiredTenantId(),
            AuditRecordData::fromArray($request->validated()),
        );

        return response()->json([
            'success' => true,
            'message' => 'Audit record created successfully.',
            'data' => new AuditLogResource($record),
        ], 201);
    }
}
