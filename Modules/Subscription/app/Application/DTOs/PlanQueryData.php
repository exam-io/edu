<?php

namespace Modules\Subscription\Application\DTOs;

readonly class PlanQueryData
{
    public function __construct(
        public int $perPage,
        public ?string $search,
        public ?string $status,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            perPage: max(1, min(100, (int) ($input['per_page'] ?? 15))),
            search: isset($input['search']) ? trim((string) $input['search']) : null,
            status: isset($input['status']) ? (string) $input['status'] : null,
        );
    }
}
