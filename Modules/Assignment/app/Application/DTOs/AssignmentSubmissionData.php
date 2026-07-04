<?php

namespace Modules\Assignment\Application\DTOs;

readonly class AssignmentSubmissionData
{
    public function __construct(
        public int $assessmentId,
        public string $filePath,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            assessmentId: (int) $data['assessment_id'],
            filePath: (string) $data['file_path'],
        );
    }
}
