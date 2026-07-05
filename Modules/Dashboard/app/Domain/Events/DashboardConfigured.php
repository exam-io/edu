<?php

namespace Modules\Dashboard\Domain\Events;

readonly class DashboardConfigured
{
    public function __construct(
        public int $tenantId,
        public int $userId,
        public int $preferenceId,
    ) {}
}
