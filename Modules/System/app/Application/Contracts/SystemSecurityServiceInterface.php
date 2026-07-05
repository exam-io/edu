<?php

namespace Modules\System\Application\Contracts;

use Modules\System\Application\DTOs\SecurityPolicyData;
use Modules\System\Domain\Models\SystemSecurityPolicy;

interface SystemSecurityServiceInterface
{
    public function current(int $tenantId): ?SystemSecurityPolicy;

    public function update(int $tenantId, SecurityPolicyData $data): SystemSecurityPolicy;
}
