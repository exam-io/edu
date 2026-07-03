<?php

namespace Modules\ContentProcessing\Application\DTOs;

readonly class ContentSourceMutationData
{
    public function __construct(
        public array $attributes,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self($data);
    }
}
