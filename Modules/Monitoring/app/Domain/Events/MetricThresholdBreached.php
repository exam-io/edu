<?php

namespace Modules\Monitoring\Domain\Events;

readonly class MetricThresholdBreached
{
    public function __construct(
        public int $tenantId,
        public string $metricKey,
        public string $severity,
        public array $meta = [],
    ) {}
}
