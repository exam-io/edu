<?php

namespace Modules\Operations\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Operations\Application\Contracts\QueueOpsServiceInterface;
use Modules\Operations\Domain\Events\QueueOperationExecuted;
use Modules\Operations\Domain\Models\QueueOperationLog;

class QueueOpsService implements QueueOpsServiceInterface
{
    public function logs(int $tenantId, int $perPage = 15): LengthAwarePaginator
    {
        return QueueOperationLog::query()->where('tenant_id', $tenantId)->latest('id')->paginate($perPage);
    }

    public function record(int $tenantId, string $operation, array $meta = []): void
    {
        QueueOperationLog::query()->create([
            'tenant_id' => $tenantId,
            'operation' => $operation,
            'status' => 'completed',
            'meta' => $meta,
            'executed_at' => now(),
        ]);

        event(new QueueOperationExecuted($tenantId, $operation, $meta));
    }
}
