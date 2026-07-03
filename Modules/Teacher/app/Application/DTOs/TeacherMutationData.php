<?php

namespace Modules\Teacher\Application\DTOs;

readonly class TeacherMutationData
{
    public function __construct(public array $attributes)
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(attributes: $data);
    }
}
