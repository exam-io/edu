<?php

namespace Modules\Operations\Domain\Events;

readonly class BackupCompleted
{
    public function __construct(
        public int $tenantId,
        public int $backupExecutionId,
    ) {}
}
