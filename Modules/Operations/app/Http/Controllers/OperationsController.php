<?php

namespace Modules\Operations\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Operations\Application\Contracts\BackupServiceInterface;
use Modules\Operations\Application\Contracts\QueueOpsServiceInterface;
use Modules\Operations\Http\Requests\QueueOpsRequest;
use Modules\Operations\Http\Requests\TriggerBackupRequest;
use Modules\Operations\Http\Resources\BackupExecutionResource;
use Modules\Operations\Http\Resources\QueueOperationLogResource;
use Modules\Operations\Jobs\RunBackupJob;
use Modules\Tenant\Application\Services\TenantContextService;

class OperationsController extends Controller
{
    public function latestBackup(
        TenantContextService $tenantContext,
        BackupServiceInterface $backupService,
    ): JsonResponse {
        $backup = $backupService->latest($tenantContext->requiredTenantId());

        return response()->json([
            'success' => true,
            'data' => $backup ? new BackupExecutionResource($backup) : null,
        ]);
    }

    public function triggerBackup(
        TriggerBackupRequest $request,
        TenantContextService $tenantContext,
    ): JsonResponse {
        RunBackupJob::dispatch($tenantContext->requiredTenantId(), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Backup queued successfully.',
        ], 202);
    }

    public function queueLogs(
        TenantContextService $tenantContext,
        QueueOpsServiceInterface $queueOps,
    ): JsonResponse {
        $items = $queueOps->logs($tenantContext->requiredTenantId());

        return response()->json([
            'success' => true,
            'data' => QueueOperationLogResource::collection($items),
            'meta' => [
                'current_page' => $items->currentPage(),
                'last_page' => $items->lastPage(),
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ],
        ]);
    }

    public function recordQueueOperation(
        QueueOpsRequest $request,
        TenantContextService $tenantContext,
        QueueOpsServiceInterface $queueOps,
    ): JsonResponse {
        $payload = $request->validated();
        $queueOps->record(
            $tenantContext->requiredTenantId(),
            (string) $payload['operation'],
            is_array($payload['meta'] ?? null) ? $payload['meta'] : [],
        );

        return response()->json([
            'success' => true,
            'message' => 'Queue operation recorded.',
        ]);
    }
}
