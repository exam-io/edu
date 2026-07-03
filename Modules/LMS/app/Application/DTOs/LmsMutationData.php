<?php

namespace Modules\LMS\Application\DTOs;

readonly class LmsMutationData
{
    public function __construct(
        public array $attributes,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(attributes: $data);
    }
}
