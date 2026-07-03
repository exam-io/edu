<?php

namespace Modules\Parent\Application\DTOs;

readonly class ParentMutationData
{
    public function __construct(public array $attributes)
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(attributes: $data);
    }
}
