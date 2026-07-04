<?php

namespace Modules\Assessment\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Assessment\Application\DTOs\AssessmentQueryData;
use Modules\Assessment\Domain\Models\Assessment;
use Modules\Assessment\Domain\Models\AssessmentAnswer;
use Modules\Assessment\Domain\Models\AssessmentAttempt;

interface AssessmentRepositoryInterface
{
    public function paginate(int $tenantId, AssessmentQueryData $query): LengthAwarePaginator;

    public function findForTenant(int $tenantId, int $id, array $with = []): ?Assessment;

    public function create(array $attributes): Assessment;

    public function update(Assessment $assessment, array $attributes): Assessment;

    public function delete(Assessment $assessment): void;

    public function findActiveAttempt(int $tenantId, int $assessmentId, int $studentId): ?AssessmentAttempt;

    public function createAttempt(array $attributes): AssessmentAttempt;

    public function updateAttempt(AssessmentAttempt $attempt, array $attributes): AssessmentAttempt;

    public function upsertAnswer(int $tenantId, int $attemptId, int $questionId, array $selectedAnswer): AssessmentAnswer;

    public function fetchLeaderboard(int $tenantId, int $assessmentId): array;
}
