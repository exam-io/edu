<?php

namespace Modules\Billing\Application\DTOs;

readonly class InvoiceQueryData
{
    public function __construct(
        public int $perPage,
        public ?string $status,
        public ?string $search,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            perPage: max(1, min(100, (int) ($input['per_page'] ?? 15))),
            status: isset($input['status']) ? (string) $input['status'] : null,
            search: isset($input['search']) ? trim((string) $input['search']) : null,
        );
    }
}
