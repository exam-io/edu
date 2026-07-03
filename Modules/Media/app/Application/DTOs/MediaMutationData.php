<?php

namespace Modules\Media\Application\DTOs;

readonly class MediaMutationData
{
    public function __construct(
        public array $attributes,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(attributes: $data);
    }
}
