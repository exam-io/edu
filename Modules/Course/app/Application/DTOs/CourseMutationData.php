<?php

namespace Modules\Course\Application\DTOs;

readonly class CourseMutationData
{
    public function __construct(
        public array $attributes,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(attributes: $data);
    }
}
