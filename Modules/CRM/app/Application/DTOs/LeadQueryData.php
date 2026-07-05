<?php

namespace Modules\CRM\Application\DTOs;

readonly class LeadQueryData
{
    public function __construct(
        public int $perPage = 15,
        public ?string $status = null,
        public ?string $source = null,
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
            source: isset($payload['source']) ? (string) $payload['source'] : null,
            search: isset($payload['search']) ? trim((string) $payload['search']) : null,
        );
    }
}
