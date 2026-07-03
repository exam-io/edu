<?php

namespace Modules\Student\Application\DTOs;

readonly class StudentListQueryData
{
    public function __construct(
        public ?string $search,
        public ?string $status,
        public int $perPage,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            search: isset($data['search']) ? (string) $data['search'] : null,
            status: isset($data['status']) ? (string) $data['status'] : null,
            perPage: max(1, min(100, (int) ($data['per_page'] ?? 15))),
        );
    }
}
