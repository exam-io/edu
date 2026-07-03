<?php

namespace Modules\QuestionBank\Application\DTOs;

readonly class QuestionSetMutationData
{
    public function __construct(
        public array $attributes,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self($data);
    }
}
