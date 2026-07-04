<?php

namespace Modules\Assessment\Application\DTOs;

readonly class AssessmentQueryData
{
    public function __construct(
        public ?string $q,
        public ?string $status,
        public ?string $type,
        public int $perPage,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            q: $data['q'] ?? null,
            status: $data['status'] ?? null,
            type: $data['type'] ?? null,
            perPage: (int) ($data['per_page'] ?? 15),
        );
    }
}
