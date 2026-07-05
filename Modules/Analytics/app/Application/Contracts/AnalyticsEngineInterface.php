<?php

namespace Modules\Analytics\Application\Contracts;

interface AnalyticsEngineInterface
{
    public function aggregateForTenant(int $tenantId, \DateTimeInterface $start, \DateTimeInterface $end): void;
}
