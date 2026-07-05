<?php

namespace Modules\Operations\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Operations\Domain\Events\QueueOperationExecuted;

class QueueOpsAuditListener
{
    public function handle(QueueOperationExecuted $event): void
    {
        Log::info('operations.queue.executed', [
            'tenant_id' => $event->tenantId,
            'operation' => $event->operation,
            'meta' => $event->meta,
        ]);
    }
}
