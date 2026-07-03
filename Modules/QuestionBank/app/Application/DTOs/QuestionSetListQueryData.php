<?php

namespace Modules\QuestionBank\Application\DTOs;

readonly class QuestionSetListQueryData
{
    public function __construct(
        public ?string $search,
        public ?string $status,
        public ?string $questionType,
        public int $perPage,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            search: $data['q'] ?? null,
            status: $data['status'] ?? null,
            questionType: $data['question_type'] ?? null,
            perPage: max(1, min((int) ($data['per_page'] ?? 15), 100)),
        );
    }
}
