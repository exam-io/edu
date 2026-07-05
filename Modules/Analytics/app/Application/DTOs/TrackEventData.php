<?php

namespace Modules\Analytics\Application\DTOs;

readonly class TrackEventData
{
    public function __construct(
        public string $eventName,
        public string $sourceModule,
        public ?string $entityType,
        public ?int $entityId,
        public array $payload,
        public ?string $occurredAt,
        public ?int $tenantId,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            eventName: (string) $input['event_name'],
            sourceModule: (string) $input['source_module'],
            entityType: isset($input['entity_type']) ? (string) $input['entity_type'] : null,
            entityId: isset($input['entity_id']) ? (int) $input['entity_id'] : null,
            payload: isset($input['payload']) && is_array($input['payload']) ? $input['payload'] : [],
            occurredAt: isset($input['occurred_at']) ? (string) $input['occurred_at'] : null,
            tenantId: isset($input['tenant_id']) ? (int) $input['tenant_id'] : null,
        );
    }
}
