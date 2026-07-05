<?php

namespace Modules\WhiteLabel\Application\DTOs;

readonly class DomainMutationData
{
    public function __construct(
        public string $host,
        public bool $isPrimary,
        public ?string $status,
    ) {}

    public static function fromArray(array $payload): self
    {
        return new self(
            host: mb_strtolower((string) $payload['host']),
            isPrimary: (bool) ($payload['is_primary'] ?? false),
            status: isset($payload['status']) ? (string) $payload['status'] : null,
        );
    }
}
