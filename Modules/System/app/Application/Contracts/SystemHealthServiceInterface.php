<?php

namespace Modules\System\Application\Contracts;

use Modules\System\Domain\Models\SystemHealthCheck;

interface SystemHealthServiceInterface
{
    public function latest(int $tenantId): array;

    public function runChecks(int $tenantId): array;

    public function persistResult(int $tenantId, string $check, string $status, array $meta = []): SystemHealthCheck;
}
