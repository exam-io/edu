<?php

namespace Modules\Dashboard\Domain\Events;

readonly class DashboardViewed
{
    public function __construct(
        public int $dashboardId,
        public int $tenantId,
        public string $roleKey,
    ) {}
}
