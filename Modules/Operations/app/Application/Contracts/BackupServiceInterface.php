<?php

namespace Modules\Operations\Application\Contracts;

use Modules\Operations\Domain\Models\BackupExecution;

interface BackupServiceInterface
{
    public function trigger(int $tenantId, array $options = []): BackupExecution;

    public function latest(int $tenantId): ?BackupExecution;
}
