<?php

namespace Modules\WhiteLabel\Domain\Events;

readonly class NavigationConfigurationUpdated
{
    public function __construct(public int $tenantId) {}
}
