<?php

namespace Modules\Assessment\Application\Contracts;

use Modules\Assessment\Application\DTOs\AttemptAnswerData;
use Modules\Assessment\Domain\Models\AssessmentAttempt;

interface AssessmentAttemptServiceInterface
{
    public function start(int $assessmentId): AssessmentAttempt;

    public function saveAnswer(int $assessmentId, AttemptAnswerData $data): AssessmentAttempt;

    public function submit(int $assessmentId): AssessmentAttempt;

    public function evaluateAttempt(int $assessmentId, int $attemptId, array $answers): AssessmentAttempt;

    public function result(int $assessmentId): array;
}
