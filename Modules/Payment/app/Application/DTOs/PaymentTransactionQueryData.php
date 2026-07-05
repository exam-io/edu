<?php

namespace Modules\Payment\Application\DTOs;

readonly class PaymentTransactionQueryData
{
    public function __construct(
        public int $perPage,
        public ?string $status,
        public ?string $provider,
        public ?string $search,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            perPage: max(1, min(100, (int) ($input['per_page'] ?? 15))),
            status: isset($input['status']) ? (string) $input['status'] : null,
            provider: isset($input['provider']) ? (string) $input['provider'] : null,
            search: isset($input['search']) ? trim((string) $input['search']) : null,
        );
    }
}
