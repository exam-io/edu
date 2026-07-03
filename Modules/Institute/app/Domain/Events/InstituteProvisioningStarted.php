<?php

namespace Modules\Institute\Domain\Events;

class InstituteProvisioningStarted
{
    public function __construct(
        public readonly int $instituteId,
    ) {}
}
