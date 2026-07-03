<?php

namespace Modules\Enrollment\Application\DTOs;

readonly class TeacherAssignmentListQueryData
{
    public function __construct(
        public ?string $search,
        public ?string $status,
        public ?int $teacherId,
        public ?int $academicSessionId,
        public int $perPage,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            search: isset($data['search']) ? (string) $data['search'] : null,
            status: isset($data['status']) ? (string) $data['status'] : null,
            teacherId: isset($data['teacher_id']) ? (int) $data['teacher_id'] : null,
            academicSessionId: isset($data['academic_session_id']) ? (int) $data['academic_session_id'] : null,
            perPage: max(1, min(100, (int) ($data['per_page'] ?? 15))),
        );
    }
}
