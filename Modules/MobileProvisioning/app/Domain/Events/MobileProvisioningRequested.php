<?php

namespace Modules\MobileProvisioning\Domain\Events;

readonly class MobileProvisioningRequested
{
    public function __construct(public int $tenantId, public int $requestId) {}
}
