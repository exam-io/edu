<?php

namespace Modules\WhiteLabel\Domain\Events;

readonly class DomainMapped
{
    public function __construct(public int $tenantId) {}
}
