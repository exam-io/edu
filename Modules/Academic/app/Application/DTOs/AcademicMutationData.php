<?php

namespace Modules\Academic\Application\DTOs;

readonly class AcademicMutationData
{
    public function __construct(
        public array $attributes,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(attributes: $data);
    }
}
