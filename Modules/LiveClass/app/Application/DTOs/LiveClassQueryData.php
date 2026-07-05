<?php

namespace Modules\LiveClass\Application\DTOs;

readonly class LiveClassQueryData
{
    public function __construct(
        public int $perPage = 15,
        public ?string $status = null,
        public ?int $classId = null,
        public ?int $sectionId = null,
        public ?string $search = null,
        public ?string $fromDate = null,
        public ?string $toDate = null,
    ) {}

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            perPage: max(1, min(100, (int) ($payload['per_page'] ?? 15))),
            status: isset($payload['status']) ? (string) $payload['status'] : null,
            classId: isset($payload['class_id']) ? (int) $payload['class_id'] : null,
            sectionId: isset($payload['section_id']) ? (int) $payload['section_id'] : null,
            search: isset($payload['search']) ? trim((string) $payload['search']) : null,
            fromDate: isset($payload['from_date']) ? (string) $payload['from_date'] : null,
            toDate: isset($payload['to_date']) ? (string) $payload['to_date'] : null,
        );
    }
}
