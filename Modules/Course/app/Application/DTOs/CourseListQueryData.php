<?php

namespace Modules\Course\Application\DTOs;

readonly class CourseListQueryData
{
    public function __construct(
        public ?string $search,
        public ?string $status,
        public ?string $level,
        public int $perPage,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            search: $data['q'] ?? null,
            status: $data['status'] ?? null,
            level: $data['level'] ?? null,
            perPage: (int) ($data['per_page'] ?? 15),
        );
    }
}
