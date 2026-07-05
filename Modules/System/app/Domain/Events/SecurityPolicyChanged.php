<?php

namespace Modules\System\Domain\Events;

readonly class SecurityPolicyChanged
{
    public function __construct(
        public int $tenantId,
        public int $policyId,
    ) {}
}
