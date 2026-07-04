<?php

namespace Modules\Assessment\Application\DTOs;

readonly class AttemptAnswerData
{
    public function __construct(
        public int $questionId,
        public array $selectedAnswer,
        public bool $markForReview,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            questionId: (int) $data['question_id'],
            selectedAnswer: (array) ($data['selected_answer'] ?? []),
            markForReview: (bool) ($data['mark_for_review'] ?? false),
        );
    }
}
