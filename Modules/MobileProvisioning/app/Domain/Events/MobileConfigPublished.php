<?php

namespace Modules\MobileProvisioning\Domain\Events;

readonly class MobileConfigPublished
{
    public function __construct(public int $tenantId) {}
}
