<?php

namespace Modules\SaaS\Application\DTOs;

readonly class TrackUsageData
{
    public function __construct(
        public string $metricKey,
        public float $incrementBy,
        public string $periodKey,
        public array $meta,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            metricKey: (string) $input['metric_key'],
            incrementBy: (float) ($input['increment_by'] ?? 1),
            periodKey: (string) ($input['period_key'] ?? now()->format('Y-m')),
            meta: is_array($input['meta'] ?? null) ? $input['meta'] : [],
        );
    }
}
