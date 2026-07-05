<?php

namespace Modules\Audit\Application\DTOs;

readonly class AuditQueryData
{
    public function __construct(
        public int $perPage,
        public ?string $action,
        public ?string $actor,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            perPage: max(1, min(100, (int) ($input['per_page'] ?? 15))),
            action: isset($input['action']) ? (string) $input['action'] : null,
            actor: isset($input['actor']) ? (string) $input['actor'] : null,
        );
    }
}
