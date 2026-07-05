<?php

namespace Modules\Monitoring\Application\DTOs;

readonly class MetricsQueryData
{
    public function __construct(
        public int $perPage,
        public ?string $metricKey,
        public ?string $periodKey,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            perPage: max(1, min(100, (int) ($input['per_page'] ?? 15))),
            metricKey: isset($input['metric_key']) ? (string) $input['metric_key'] : null,
            periodKey: isset($input['period_key']) ? (string) $input['period_key'] : null,
        );
    }
}
