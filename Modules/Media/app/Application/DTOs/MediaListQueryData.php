<?php

namespace Modules\Media\Application\DTOs;

readonly class MediaListQueryData
{
    public function __construct(
        public ?string $search,
        public ?string $mimeType,
        public ?string $status,
        public int $perPage,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            search: $data['q'] ?? null,
            mimeType: $data['mime_type'] ?? null,
            status: $data['status'] ?? null,
            perPage: (int) ($data['per_page'] ?? 15),
        );
    }
}
