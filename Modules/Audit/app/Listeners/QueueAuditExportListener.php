<?php

namespace Modules\Audit\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Audit\Domain\Events\AuditRecordCreated;
use Modules\Audit\Jobs\ExportAuditLogsJob;

class QueueAuditExportListener implements ShouldQueue
{
    public function handle(AuditRecordCreated $event): void
    {
        ExportAuditLogsJob::dispatch($event->tenantId);
    }
}
