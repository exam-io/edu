<?php

namespace Modules\Analytics\Application\DTOs;

readonly class AnalyticsQueryData
{
    public function __construct(
        public ?string $q,
        public ?string $eventName,
        public int $perPage,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            q: isset($input['q']) ? (string) $input['q'] : null,
            eventName: isset($input['event_name']) ? (string) $input['event_name'] : null,
            perPage: (int) ($input['per_page'] ?? 15),
        );
    }
}
