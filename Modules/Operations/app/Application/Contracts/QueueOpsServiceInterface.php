<?php

namespace Modules\Operations\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface QueueOpsServiceInterface
{
    public function logs(int $tenantId, int $perPage = 15): LengthAwarePaginator;

    public function record(int $tenantId, string $operation, array $meta = []): void;
}
