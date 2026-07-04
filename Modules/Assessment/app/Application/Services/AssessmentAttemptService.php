<?php

namespace Modules\Assessment\Application\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Modules\Assessment\Application\Contracts\AssessmentAttemptServiceInterface;
use Modules\Assessment\Application\Contracts\AssessmentEvaluationServiceInterface;
use Modules\Assessment\Application\Contracts\AssessmentRepositoryInterface;
use Modules\Assessment\Application\Contracts\RankingServiceInterface;
use Modules\Assessment\Application\Contracts\ResultServiceInterface;
use Modules\Assessment\Application\DTOs\AttemptAnswerData;
use Modules\Assessment\Domain\Events\AssessmentEvaluated;
use Modules\Assessment\Domain\Events\AssessmentStarted;
use Modules\Assessment\Domain\Events\AssessmentSubmitted;
use Modules\Assessment\Domain\Events\ResultGenerated;
use Modules\Assessment\Domain\Models\Assessment;
use Modules\Assessment\Domain\Models\AssessmentAnswer;
use Modules\Assessment\Domain\Models\AssessmentAttempt;
use Modules\Student\Domain\Models\Student;
use Modules\Tenant\Application\Services\TenantContextService;

class AssessmentAttemptService implements AssessmentAttemptServiceInterface
{
    public function __construct(
        private readonly AssessmentRepositoryInterface $repository,
        private readonly AssessmentEvaluationServiceInterface $evaluationService,
        private readonly ResultServiceInterface $resultService,
        private readonly RankingServiceInterface $rankingService,
        private readonly TenantContextService $tenantContext,
    ) {
    }

    public function start(int $assessmentId): AssessmentAttempt
    {
        $tenantId = $this->tenantId();
        $assessment = $this->findAssessment($tenantId, $assessmentId);
        $this->assertSchedulable($assessment);

        $student = $this->resolveStudent($tenantId);

        $active = $this->repository->findActiveAttempt($tenantId, $assessment->id, $student->id);
        if ($active) {
            return $active->load(['assessment', 'answers']);
        }

        $attempt = $this->repository->createAttempt([
            'tenant_id' => $tenantId,
            'assessment_id' => $assessment->id,
            'student_id' => $student->id,
            'started_at' => now(),
            'status' => 'started',
        ]);

        Event::dispatch(new AssessmentStarted($attempt->id, $assessment->id, $student->id, $tenantId));

        return $attempt->load(['assessment', 'answers']);
    }

    public function saveAnswer(int $assessmentId, AttemptAnswerData $data): AssessmentAttempt
    {
        $tenantId = $this->tenantId();
        $student = $this->resolveStudent($tenantId);
        $attempt = $this->repository->findActiveAttempt($tenantId, $assessmentId, $student->id);

        if (! $attempt instanceof AssessmentAttempt) {
            throw ValidationException::withMessages([
                'attempt' => ['No active attempt found for this student.'],
            ]);
        }

        $assessmentQuestion = $attempt->assessment
            ->questions()
            ->where('question_id', $data->questionId)
            ->first();

        if (! $assessmentQuestion) {
            throw ValidationException::withMessages([
                'question_id' => ['Question does not belong to this assessment.'],
            ]);
        }

        $this->repository->upsertAnswer($tenantId, $attempt->id, $data->questionId, [
            'answer' => $data->selectedAnswer,
            'mark_for_review' => $data->markForReview,
            'auto_saved_at' => now()->toIso8601String(),
        ]);
        $attempt = $this->repository->updateAttempt($attempt, ['status' => 'in_progress']);

        return $attempt->refresh()->load(['assessment', 'answers']);
    }

    public function submit(int $assessmentId): AssessmentAttempt
    {
        $tenantId = $this->tenantId();
        $student = $this->resolveStudent($tenantId);

        $attempt = $this->repository->findActiveAttempt($tenantId, $assessmentId, $student->id);
        if (! $attempt instanceof AssessmentAttempt) {
            throw ValidationException::withMessages([
                'attempt' => ['No active attempt found to submit.'],
            ]);
        }

        $startedAt = $attempt->started_at ?? now();
        $timeTaken = now()->diffInSeconds($startedAt);

        $attempt = $this->repository->updateAttempt($attempt, [
            'submitted_at' => now(),
            'time_taken' => $timeTaken,
            'status' => 'submitted',
        ]);

        Event::dispatch(new AssessmentSubmitted($attempt->id, $attempt->assessment_id, $attempt->student_id, $attempt->tenant_id));

        $attempt = $this->evaluationService->evaluate($attempt);
        $attempt = $this->rankingService->assignRank($attempt);

        Event::dispatch(new ResultGenerated($attempt->id, $attempt->assessment_id, $attempt->tenant_id));

        return $attempt->refresh()->load(['assessment', 'answers']);
    }

    public function evaluateAttempt(int $assessmentId, int $attemptId, array $answers): AssessmentAttempt
    {
        $tenantId = $this->tenantId();

        $attempt = AssessmentAttempt::query()
            ->where('tenant_id', $tenantId)
            ->where('assessment_id', $assessmentId)
            ->whereKey($attemptId)
            ->with(['assessment', 'answers'])
            ->first();

        if (! $attempt instanceof AssessmentAttempt) {
            throw ValidationException::withMessages([
                'attempt' => ['Assessment attempt not found.'],
            ]);
        }

        foreach ($answers as $answerData) {
            $answer = AssessmentAnswer::query()
                ->where('tenant_id', $tenantId)
                ->where('assessment_attempt_id', $attempt->id)
                ->where('question_id', (int) $answerData['question_id'])
                ->first();

            if (! $answer instanceof AssessmentAnswer) {
                continue;
            }

            $answer->marks_awarded = (float) $answerData['marks_awarded'];
            $answer->is_correct = array_key_exists('is_correct', $answerData) ? $answerData['is_correct'] : $answer->is_correct;
            $answer->save();
        }

        $score = (float) AssessmentAnswer::query()
            ->where('tenant_id', $tenantId)
            ->where('assessment_attempt_id', $attempt->id)
            ->sum('marks_awarded');
        $score = max(0, $score);

        $totalMarks = (float) $attempt->assessment->total_marks;
        $percentage = $totalMarks > 0 ? round(($score / $totalMarks) * 100, 2) : 0.0;

        $attempt = $this->repository->updateAttempt($attempt, [
            'score' => $score,
            'percentage' => $percentage,
            'status' => 'evaluated',
            'submitted_at' => $attempt->submitted_at ?? now(),
        ]);

        Event::dispatch(new AssessmentEvaluated($attempt->id, $attempt->assessment_id, $attempt->tenant_id));

        $attempt = $this->rankingService->assignRank($attempt);

        Event::dispatch(new ResultGenerated($attempt->id, $attempt->assessment_id, $attempt->tenant_id));

        return $attempt->refresh()->load(['assessment', 'answers']);
    }

    public function result(int $assessmentId): array
    {
        $tenantId = $this->tenantId();
        $student = $this->resolveStudent($tenantId);

        $attempt = AssessmentAttempt::query()
            ->where('tenant_id', $tenantId)
            ->where('assessment_id', $assessmentId)
            ->where('student_id', $student->id)
            ->whereIn('status', ['submitted', 'evaluated'])
            ->latest('id')
            ->first();

        if (! $attempt instanceof AssessmentAttempt) {
            throw ValidationException::withMessages([
                'result' => ['No submitted result found for this assessment.'],
            ]);
        }

        return $this->resultService->buildResult($attempt);
    }

    private function findAssessment(int $tenantId, int $assessmentId): Assessment
    {
        $assessment = $this->repository->findForTenant($tenantId, $assessmentId, ['questions']);

        if (! $assessment instanceof Assessment) {
            throw (new ModelNotFoundException())->setModel(Assessment::class, [$assessmentId]);
        }

        return $assessment;
    }

    private function assertSchedulable(Assessment $assessment): void
    {
        if ($assessment->status !== 'published') {
            throw ValidationException::withMessages([
                'assessment' => ['Assessment is not published.'],
            ]);
        }

        if ($assessment->start_at && now()->lt($assessment->start_at)) {
            throw ValidationException::withMessages([
                'assessment' => ['Assessment has not started yet.'],
            ]);
        }

        if ($assessment->end_at && now()->gt($assessment->end_at)) {
            throw ValidationException::withMessages([
                'assessment' => ['Assessment schedule has ended.'],
            ]);
        }
    }

    private function resolveStudent(int $tenantId): Student
    {
        $student = Student::query()
            ->where('tenant_id', $tenantId)
            ->where('user_id', auth()->id())
            ->first();

        if (! $student instanceof Student) {
            throw ValidationException::withMessages([
                'student' => ['Authenticated user is not a student in this tenant.'],
            ]);
        }

        return $student;
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
