<?php

namespace Modules\Content\Application\DTOs;

readonly class ContentListQueryData
{
    public function __construct(
        public ?string $search,
        public ?string $status,
        public ?int $courseId,
        public ?int $courseSectionId,
        public ?string $contentType,
        public int $perPage,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            search: $data['q'] ?? null,
            status: $data['status'] ?? null,
            courseId: isset($data['course_id']) ? (int) $data['course_id'] : null,
            courseSectionId: isset($data['course_section_id']) ? (int) $data['course_section_id'] : null,
            contentType: $data['content_type'] ?? null,
            perPage: (int) ($data['per_page'] ?? 15),
        );
    }
}
