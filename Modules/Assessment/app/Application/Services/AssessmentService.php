<?php

namespace Modules\Assessment\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Modules\Assessment\Application\Contracts\AssessmentAssignmentServiceInterface;
use Modules\Assessment\Application\Contracts\AssessmentBuilderServiceInterface;
use Modules\Assessment\Application\Contracts\AssessmentRepositoryInterface;
use Modules\Assessment\Application\Contracts\AssessmentServiceInterface;
use Modules\Assessment\Application\DTOs\AssessmentMutationData;
use Modules\Assessment\Application\DTOs\AssessmentQueryData;
use Modules\Assessment\Domain\Events\AssessmentCreated;
use Modules\Assessment\Domain\Events\AssessmentPublished;
use Modules\Assessment\Domain\Models\Assessment;
use Modules\Tenant\Application\Services\TenantContextService;

class AssessmentService implements AssessmentServiceInterface
{
    public function __construct(
        private readonly AssessmentRepositoryInterface $repository,
        private readonly AssessmentBuilderServiceInterface $builderService,
        private readonly AssessmentAssignmentServiceInterface $assignmentService,
        private readonly TenantContextService $tenantContext,
    ) {
    }

    public function list(AssessmentQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginate($this->tenantId(), $query);
    }

    public function find(int $id): Assessment
    {
        $assessment = $this->repository->findForTenant($this->tenantId(), $id, [
            'questions.question',
            'assignments',
        ]);

        if (! $assessment instanceof Assessment) {
            throw (new ModelNotFoundException())->setModel(Assessment::class, [$id]);
        }

        return $assessment;
    }

    public function create(AssessmentMutationData $data): Assessment
    {
        $attributes = $data->attributes;
        $this->validateSchedule($attributes);

        $assessment = $this->repository->create([
            'tenant_id' => $this->tenantId(),
            'title' => (string) $attributes['title'],
            'description' => $attributes['description'] ?? null,
            'type' => (string) $attributes['type'],
            'instructions' => $attributes['instructions'] ?? null,
            'start_at' => $attributes['start_at'] ?? null,
            'end_at' => $attributes['end_at'] ?? null,
            'duration_minutes' => $attributes['duration_minutes'] ?? null,
            'total_marks' => (float) ($attributes['total_marks'] ?? 0),
            'passing_marks' => (float) ($attributes['passing_marks'] ?? 0),
            'negative_marking' => (float) ($attributes['negative_marking'] ?? 0),
            'randomize_questions' => (bool) ($attributes['randomize_questions'] ?? false),
            'randomize_options' => (bool) ($attributes['randomize_options'] ?? false),
            'status' => (string) ($attributes['status'] ?? 'draft'),
            'created_by' => auth()->id(),
        ]);

        if (isset($attributes['questions']) && is_array($attributes['questions'])) {
            $assessment = $this->builderService->syncQuestions($assessment, $attributes['questions']);
        }

        if (isset($attributes['assignments']) && is_array($attributes['assignments'])) {
            $assessment = $this->assignmentService->syncAssignments($assessment, $attributes['assignments']);
        }

        Event::dispatch(new AssessmentCreated($assessment->id, $assessment->tenant_id));

        return $assessment->refresh()->load(['questions.question', 'assignments']);
    }

    public function update(int $id, AssessmentMutationData $data): Assessment
    {
        $assessment = $this->find($id);

        if ($assessment->status === 'published' && ! auth()->user()?->can('assessment.publish.override')) {
            throw ValidationException::withMessages([
                'assessment' => ['Published assessments cannot be edited without override permission.'],
            ]);
        }

        $attributes = $data->attributes;
        $this->validateSchedule($attributes);

        $assessment = $this->repository->update($assessment, [
            'title' => $attributes['title'] ?? $assessment->title,
            'description' => $attributes['description'] ?? $assessment->description,
            'type' => $attributes['type'] ?? $assessment->type,
            'instructions' => $attributes['instructions'] ?? $assessment->instructions,
            'start_at' => $attributes['start_at'] ?? $assessment->start_at,
            'end_at' => $attributes['end_at'] ?? $assessment->end_at,
            'duration_minutes' => $attributes['duration_minutes'] ?? $assessment->duration_minutes,
            'total_marks' => $attributes['total_marks'] ?? $assessment->total_marks,
            'passing_marks' => $attributes['passing_marks'] ?? $assessment->passing_marks,
            'negative_marking' => $attributes['negative_marking'] ?? $assessment->negative_marking,
            'randomize_questions' => $attributes['randomize_questions'] ?? $assessment->randomize_questions,
            'randomize_options' => $attributes['randomize_options'] ?? $assessment->randomize_options,
            'status' => $attributes['status'] ?? $assessment->status,
        ]);

        if (isset($attributes['questions']) && is_array($attributes['questions'])) {
            $assessment = $this->builderService->syncQuestions($assessment, $attributes['questions']);
        }

        if (isset($attributes['assignments']) && is_array($attributes['assignments'])) {
            $assessment = $this->assignmentService->syncAssignments($assessment, $attributes['assignments']);
        }

        return $assessment->refresh()->load(['questions.question', 'assignments']);
    }

    public function delete(int $id): void
    {
        $assessment = $this->find($id);

        if ($assessment->status === 'published' && ! auth()->user()?->can('assessment.publish.override')) {
            throw ValidationException::withMessages([
                'assessment' => ['Published assessments cannot be deleted without override permission.'],
            ]);
        }

        $this->repository->delete($assessment);
    }

    public function publish(int $id): Assessment
    {
        $assessment = $this->find($id);

        if ($assessment->questions()->count() === 0) {
            throw ValidationException::withMessages([
                'assessment' => ['Assessment must have at least one question before publishing.'],
            ]);
        }

        $this->repository->update($assessment, [
            'status' => 'published',
            'published_at' => now(),
        ]);

        Event::dispatch(new AssessmentPublished($assessment->id, $assessment->tenant_id));

        return $assessment->refresh()->load(['questions.question', 'assignments']);
    }

    private function validateSchedule(array $attributes): void
    {
        $start = $attributes['start_at'] ?? null;
        $end = $attributes['end_at'] ?? null;

        if ($start !== null && $end !== null && strtotime((string) $start) >= strtotime((string) $end)) {
            throw ValidationException::withMessages([
                'end_at' => ['end_at must be after start_at.'],
            ]);
        }
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
