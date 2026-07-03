<?php

namespace Modules\LMS\Application\DTOs;

readonly class LmsListQueryData
{
    public function __construct(
        public ?string $search,
        public ?string $status,
        public ?int $courseId,
        public ?int $studentId,
        public int $perPage,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            search: $data['q'] ?? null,
            status: $data['status'] ?? null,
            courseId: isset($data['course_id']) ? (int) $data['course_id'] : null,
            studentId: isset($data['student_id']) ? (int) $data['student_id'] : null,
            perPage: (int) ($data['per_page'] ?? 15),
        );
    }
}
