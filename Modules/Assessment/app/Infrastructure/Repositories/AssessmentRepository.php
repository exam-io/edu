<?php

namespace Modules\Assessment\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Assessment\Application\Contracts\AssessmentRepositoryInterface;
use Modules\Assessment\Application\DTOs\AssessmentQueryData;
use Modules\Assessment\Domain\Models\Assessment;
use Modules\Assessment\Domain\Models\AssessmentAnswer;
use Modules\Assessment\Domain\Models\AssessmentAttempt;

class AssessmentRepository implements AssessmentRepositoryInterface
{
    public function paginate(int $tenantId, AssessmentQueryData $query): LengthAwarePaginator
    {
        $builder = Assessment::query()
            ->where('tenant_id', $tenantId)
            ->withCount('questions')
            ->latest('id');

        if ($query->q !== null && $query->q !== '') {
            $builder->where(function ($q) use ($query): void {
                $q->where('title', 'like', '%' . $query->q . '%')
                    ->orWhere('description', 'like', '%' . $query->q . '%');
            });
        }

        if ($query->status !== null && $query->status !== '') {
            $builder->where('status', $query->status);
        }

        if ($query->type !== null && $query->type !== '') {
            $builder->where('type', $query->type);
        }

        return $builder->paginate($query->perPage);
    }

    public function findForTenant(int $tenantId, int $id, array $with = []): ?Assessment
    {
        return Assessment::query()
            ->where('tenant_id', $tenantId)
            ->with($with)
            ->find($id);
    }

    public function create(array $attributes): Assessment
    {
        return Assessment::query()->create($attributes);
    }

    public function update(Assessment $assessment, array $attributes): Assessment
    {
        $assessment->fill($attributes);
        $assessment->save();

        return $assessment;
    }

    public function delete(Assessment $assessment): void
    {
        $assessment->delete();
    }

    public function findActiveAttempt(int $tenantId, int $assessmentId, int $studentId): ?AssessmentAttempt
    {
        return AssessmentAttempt::query()
            ->where('tenant_id', $tenantId)
            ->where('assessment_id', $assessmentId)
            ->where('student_id', $studentId)
            ->whereIn('status', ['started', 'in_progress'])
            ->latest('id')
            ->first();
    }

    public function hasSubmittedAttempt(int $tenantId, int $assessmentId, int $studentId): bool
    {
        return AssessmentAttempt::query()
            ->where('tenant_id', $tenantId)
            ->where('assessment_id', $assessmentId)
            ->where('student_id', $studentId)
            ->whereIn('status', ['submitted', 'evaluated'])
            ->exists();
    }

    public function createAttempt(array $attributes): AssessmentAttempt
    {
        return AssessmentAttempt::query()->create($attributes);
    }

    public function updateAttempt(AssessmentAttempt $attempt, array $attributes): AssessmentAttempt
    {
        $attempt->fill($attributes);
        $attempt->save();

        return $attempt;
    }

    public function upsertAnswer(int $tenantId, int $attemptId, int $questionId, array $selectedAnswer): AssessmentAnswer
    {
        return AssessmentAnswer::query()->updateOrCreate(
            [
                'tenant_id' => $tenantId,
                'assessment_attempt_id' => $attemptId,
                'question_id' => $questionId,
            ],
            [
                'selected_answer' => $selectedAnswer,
            ],
        );
    }

    public function fetchLeaderboard(int $tenantId, int $assessmentId): array
    {
        return AssessmentAttempt::query()
            ->where('tenant_id', $tenantId)
            ->where('assessment_id', $assessmentId)
            ->whereIn('status', ['submitted', 'evaluated'])
            ->orderByDesc('score')
            ->orderBy('time_taken')
            ->orderBy('id')
            ->get(['id', 'score', 'time_taken'])
            ->all();
    }

    public function updateRanks(int $tenantId, array $rankByAttemptId): void
    {
        if ($rankByAttemptId === []) {
            return;
        }

        $attemptIds = array_map('intval', array_keys($rankByAttemptId));

        $caseSql = 'CASE id ';
        foreach ($rankByAttemptId as $attemptId => $rank) {
            $caseSql .= 'WHEN ' . (int) $attemptId . ' THEN ' . (int) $rank . ' ';
        }
        $caseSql .= 'END';

        AssessmentAttempt::query()
            ->where('tenant_id', $tenantId)
            ->whereIn('id', $attemptIds)
            ->update(['rank' => DB::raw($caseSql)]);
    }
}
