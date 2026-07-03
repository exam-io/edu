<?php

namespace Modules\AI\Application\DTOs;

readonly class AIRequestListQueryData
{
    public function __construct(
        public ?string $search,
        public ?string $status,
        public ?string $generationType,
        public int $perPage,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            search: $data['q'] ?? null,
            status: $data['status'] ?? null,
            generationType: $data['generation_type'] ?? null,
            perPage: max(1, min((int) ($data['per_page'] ?? 15), 100)),
        );
    }
}
