<?php

namespace Modules\Enrollment\Application\DTOs;

readonly class TeacherAssignmentMutationData
{
    public function __construct(public array $attributes)
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(attributes: $data);
    }
}
