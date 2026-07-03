<?php

namespace Modules\Student\Application\DTOs;

readonly class StudentMutationData
{
    public function __construct(
        public array $attributes,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(attributes: $data);
    }
}
