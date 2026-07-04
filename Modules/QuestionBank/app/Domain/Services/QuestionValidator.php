<?php

namespace Modules\QuestionBank\Domain\Services;

use Illuminate\Validation\ValidationException;
use Modules\QuestionBank\Domain\Models\Question;

class QuestionValidator
{
    public function validateOrFail(array $questions, int $tenantId): array
    {
        $errors = [];
        $seenStems = [];
        $validated = [];

        foreach ($questions as $index => $question) {
            [$isValid, $message, $normalizedStem] = $this->validateQuestionPayload($question, $tenantId, $seenStems);

            if (! $isValid) {
                $errors[] = 'questions.' . $index . ': ' . $message;
                continue;
            }

            $seenStems[$normalizedStem] = true;
            $validated[] = $question;
        }

        if ($errors !== []) {
            throw ValidationException::withMessages([
                'questions' => $errors,
            ]);
        }

        return $validated;
    }

    public function sanitize(array $questions, int $tenantId): array
    {
        $seenStems = [];
        $validated = [];

        foreach ($questions as $question) {
            [$isValid, , $normalizedStem] = $this->validateQuestionPayload($question, $tenantId, $seenStems);

            if (! $isValid) {
                continue;
            }

            $seenStems[$normalizedStem] = true;
            $validated[] = $question;
        }

        return $validated;
    }

    private function validateQuestionPayload(array $question, int $tenantId, array $seenStems): array
    {
        $stem = trim((string) ($question['stem'] ?? ''));
        if (mb_strlen($stem) < 10) {
            return [false, 'Stem must be at least 10 characters.', ''];
        }

        $normalizedStem = mb_strtolower(preg_replace('/\s+/u', ' ', $stem) ?? $stem);

        if (isset($seenStems[$normalizedStem])) {
            return [false, 'Duplicate stem found in payload.', $normalizedStem];
        }

        $exists = Question::query()
            ->where('tenant_id', $tenantId)
            ->whereRaw('LOWER(stem) = ?', [$normalizedStem])
            ->exists();

        if ($exists) {
            return [false, 'Duplicate stem already exists in question bank.', $normalizedStem];
        }

        $questionType = (string) ($question['question_type'] ?? 'short_answer');
        $correctAnswer = $question['correct_answer'] ?? [];

        if (! is_array($correctAnswer) || $correctAnswer === []) {
            return [false, 'correct_answer must be a non-empty array.', $normalizedStem];
        }

        if (in_array($questionType, ['mcq', 'true_false'], true)) {
            $options = $question['options'] ?? [];

            if ($options !== [] && is_array($options)) {
                $normalizedOptions = $this->normalizeValues($options);
                $normalizedCorrect = $this->normalizeValues($correctAnswer);

                foreach ($normalizedCorrect as $answer) {
                    if (! in_array($answer, $normalizedOptions, true)) {
                        return [false, 'correct_answer must be contained in options.', $normalizedStem];
                    }
                }
            }
        }

        return [true, null, $normalizedStem];
    }

    private function normalizeValues(array $values): array
    {
        return array_values(array_filter(array_map(function ($value): string {
            if (is_array($value)) {
                $value = $value['value'] ?? json_encode($value);
            }

            return mb_strtolower(trim((string) $value));
        }, $values), static fn (string $value): bool => $value !== ''));
    }
}
