<?php

namespace Modules\Calendar\Application\DTOs;

readonly class CalendarEventQueryData
{
    public function __construct(
        public int $perPage = 15,
        public ?string $status = null,
        public ?string $eventType = null,
        public ?string $fromDate = null,
        public ?string $toDate = null,
        public ?string $search = null,
    ) {}

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            perPage: max(1, min(100, (int) ($payload['per_page'] ?? 15))),
            status: isset($payload['status']) ? (string) $payload['status'] : null,
            eventType: isset($payload['event_type']) ? (string) $payload['event_type'] : null,
            fromDate: isset($payload['from_date']) ? (string) $payload['from_date'] : null,
            toDate: isset($payload['to_date']) ? (string) $payload['to_date'] : null,
            search: isset($payload['search']) ? trim((string) $payload['search']) : null,
        );
    }
}
