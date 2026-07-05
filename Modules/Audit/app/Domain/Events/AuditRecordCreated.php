<?php

namespace Modules\Audit\Domain\Events;

readonly class AuditRecordCreated
{
    public function __construct(
        public int $tenantId,
        public int $auditLogId,
    ) {}
}
