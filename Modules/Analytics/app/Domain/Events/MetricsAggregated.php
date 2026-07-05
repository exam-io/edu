<?php

namespace Modules\Analytics\Domain\Events;

readonly class MetricsAggregated
{
    public function __construct(
        public int $tenantId,
        public string $periodStart,
        public string $periodEnd,
    ) {}
}
