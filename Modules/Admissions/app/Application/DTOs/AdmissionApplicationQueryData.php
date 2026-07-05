<?php

namespace Modules\Admissions\Application\DTOs;

readonly class AdmissionApplicationQueryData
{
    public function __construct(
        public int $perPage = 15,
        public ?string $status = null,
        public ?string $program = null,
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
            program: isset($payload['program']) ? (string) $payload['program'] : null,
            search: isset($payload['search']) ? trim((string) $payload['search']) : null,
        );
    }
}
