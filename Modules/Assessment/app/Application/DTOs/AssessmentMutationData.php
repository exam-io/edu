<?php

namespace Modules\Assessment\Application\DTOs;

readonly class AssessmentMutationData
{
    public function __construct(
        public array $attributes,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self($data);
    }
}
