<?php

namespace Modules\Reporting\Application\DTOs;

readonly class ReportQueryData
{
    public function __construct(
        public ?string $q,
        public ?string $status,
        public int $perPage,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            q: isset($input['q']) ? (string) $input['q'] : null,
            status: isset($input['status']) ? (string) $input['status'] : null,
            perPage: (int) ($input['per_page'] ?? 15),
        );
    }
}
