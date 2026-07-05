<?php

namespace Modules\Audit\Application\DTOs;

readonly class AuditRecordData
{
    public function __construct(
        public ?int $actorUserId,
        public string $actorType,
        public string $action,
        public string $resourceType,
        public ?string $resourceId,
        public array $beforeState,
        public array $afterState,
        public array $context,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            actorUserId: isset($input['actor_user_id']) ? (int) $input['actor_user_id'] : null,
            actorType: (string) ($input['actor_type'] ?? 'user'),
            action: (string) $input['action'],
            resourceType: (string) ($input['resource_type'] ?? 'system'),
            resourceId: isset($input['resource_id']) ? (string) $input['resource_id'] : null,
            beforeState: is_array($input['before_state'] ?? null) ? $input['before_state'] : [],
            afterState: is_array($input['after_state'] ?? null) ? $input['after_state'] : [],
            context: is_array($input['context'] ?? null) ? $input['context'] : [],
        );
    }
}
