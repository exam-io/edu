<?php

namespace Modules\ContentProcessing\Application\DTOs;

readonly class ContentSourceListQueryData
{
    public function __construct(
        public ?string $search,
        public ?string $status,
        public ?string $sourceType,
        public int $perPage,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            search: $data['q'] ?? null,
            status: $data['status'] ?? null,
            sourceType: $data['source_type'] ?? null,
            perPage: max(1, min((int) ($data['per_page'] ?? 15), 100)),
        );
    }
}
