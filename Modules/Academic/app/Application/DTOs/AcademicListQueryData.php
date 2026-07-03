<?php

namespace Modules\Academic\Application\DTOs;

readonly class AcademicListQueryData
{
    public function __construct(
        public ?string $search,
        public ?string $status,
        public ?int $departmentId,
        public ?int $programId,
        public ?int $classId,
        public ?int $academicSessionId,
        public int $perPage,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            search: $data['q'] ?? null,
            status: $data['status'] ?? null,
            departmentId: isset($data['department_id']) ? (int) $data['department_id'] : null,
            programId: isset($data['program_id']) ? (int) $data['program_id'] : null,
            classId: isset($data['class_id']) ? (int) $data['class_id'] : null,
            academicSessionId: isset($data['academic_session_id']) ? (int) $data['academic_session_id'] : null,
            perPage: max(1, min((int) ($data['per_page'] ?? 15), 100)),
        );
    }
}
