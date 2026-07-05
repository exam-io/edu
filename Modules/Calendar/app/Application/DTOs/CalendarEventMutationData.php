<?php

namespace Modules\Calendar\Application\DTOs;

use Carbon\CarbonImmutable;

readonly class CalendarEventMutationData
{
    /**
     * @param array<string, mixed> $meta
     */
    public function __construct(
        public string $title,
        public ?string $description,
        public CarbonImmutable $startAt,
        public ?CarbonImmutable $endAt,
        public bool $allDay,
        public string $eventType,
        public string $status,
        public ?string $url,
        public array $meta = [],
    ) {}

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            title: (string) $payload['title'],
            description: isset($payload['description']) ? (string) $payload['description'] : null,
            startAt: CarbonImmutable::parse($payload['start_at']),
            endAt: isset($payload['end_at']) ? CarbonImmutable::parse($payload['end_at']) : null,
            allDay: (bool) ($payload['all_day'] ?? false),
            eventType: (string) ($payload['event_type'] ?? 'general'),
            status: (string) ($payload['status'] ?? 'scheduled'),
            url: isset($payload['url']) ? (string) $payload['url'] : null,
            meta: isset($payload['meta']) && is_array($payload['meta']) ? $payload['meta'] : [],
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'start_at' => $this->startAt,
            'end_at' => $this->endAt,
            'all_day' => $this->allDay,
            'event_type' => $this->eventType,
            'status' => $this->status,
            'url' => $this->url,
            'meta' => $this->meta,
        ];
    }
}
