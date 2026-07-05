<?php

namespace Modules\WhiteLabel\Application\Contracts;

use Illuminate\Support\Collection;
use Modules\WhiteLabel\Application\DTOs\DomainMutationData;
use Modules\WhiteLabel\Domain\Models\TenantDomain;

interface DomainServiceInterface
{
    public function list(int $tenantId): Collection;

    public function create(int $tenantId, DomainMutationData $data): TenantDomain;

    public function update(int $tenantId, int $id, DomainMutationData $data): TenantDomain;
}
