<?php

namespace Modules\Enrollment\Application\DTOs;

readonly class EnrollmentListQueryData
{
    public function __construct(
        public ?string $search,
        public ?string $status,
        public ?int $studentId,
        public ?int $academicSessionId,
        public ?int $classId,
        public int $perPage,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            search: isset($data['search']) ? (string) $data['search'] : null,
            status: isset($data['status']) ? (string) $data['status'] : null,
            studentId: isset($data['student_id']) ? (int) $data['student_id'] : null,
            academicSessionId: isset($data['academic_session_id']) ? (int) $data['academic_session_id'] : null,
            classId: isset($data['class_id']) ? (int) $data['class_id'] : null,
            perPage: max(1, min(100, (int) ($data['per_page'] ?? 15))),
        );
    }
}
