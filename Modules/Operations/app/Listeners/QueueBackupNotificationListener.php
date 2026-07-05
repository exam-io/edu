<?php

namespace Modules\Operations\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Operations\Domain\Events\BackupCompleted;

class QueueBackupNotificationListener
{
    public function handle(BackupCompleted $event): void
    {
        Log::info('operations.backup.completed', [
            'tenant_id' => $event->tenantId,
            'backup_execution_id' => $event->backupExecutionId,
        ]);
    }
}
