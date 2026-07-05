<?php

namespace Modules\Audit\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Audit\Application\DTOs\AuditQueryData;
use Modules\Audit\Application\DTOs\AuditRecordData;
use Modules\Audit\Domain\Models\AuditLog;

interface AuditServiceInterface
{
    public function logs(int $tenantId, AuditQueryData $query): LengthAwarePaginator;

    public function record(int $tenantId, AuditRecordData $data): AuditLog;
}
