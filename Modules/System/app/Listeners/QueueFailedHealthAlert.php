<?php

namespace Modules\System\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Modules\System\Domain\Events\SystemHealthCheckFailed;

class QueueFailedHealthAlert implements ShouldQueue
{
    public function handle(SystemHealthCheckFailed $event): void
    {
        Log::warning('System health check failed.', [
            'tenant_id' => $event->tenantId,
            'check' => $event->checkName,
            'meta' => $event->meta,
        ]);
    }
}
