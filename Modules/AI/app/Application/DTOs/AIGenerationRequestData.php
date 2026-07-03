<?php

namespace Modules\AI\Application\DTOs;

readonly class AIGenerationRequestData
{
    public function __construct(
        public array $attributes,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self($data);
    }
}
