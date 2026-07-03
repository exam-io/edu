<?php

namespace Modules\LMS\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Modules\Course\Domain\Models\Course;
use Modules\LMS\Application\Contracts\LearningProgressServiceInterface;
use Modules\LMS\Application\Contracts\LmsRepositoryInterface;
use Modules\LMS\Application\DTOs\LmsListQueryData;
use Modules\LMS\Application\DTOs\LmsMutationData;
use Modules\LMS\Domain\Events\ProgressUpdated;
use Modules\LMS\Domain\Models\LearningProgress;
use Modules\Student\Domain\Models\Student;
use Modules\Tenant\Application\Services\TenantContextService;

class LearningProgressService implements LearningProgressServiceInterface
{
    public function __construct(
        private readonly LmsRepositoryInterface $repository,
        private readonly TenantContextService $tenantContext,
    ) {}

    public function list(LmsListQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginate(
            modelClass: LearningProgress::class,
            tenantId: $this->tenantId(),
            query: $query,
            searchColumns: ['status'],
            filters: [
                'course_id' => $query->courseId,
                'student_id' => $query->studentId,
            ],
            with: ['course', 'student', 'contentItem'],
        );
    }

    public function find(int $id): LearningProgress
    {
        $progress = $this->repository->findForTenant(LearningProgress::class, $this->tenantId(), $id, ['course', 'student', 'contentItem']);

        if (! $progress instanceof LearningProgress) {
            throw (new ModelNotFoundException())->setModel(LearningProgress::class, [$id]);
        }

        return $progress;
    }

    public function create(LmsMutationData $data): LearningProgress
    {
        $attributes = $data->attributes;
        $this->assertCourse((int) $attributes['course_id']);
        $this->assertStudent((int) $attributes['student_id']);

        $attributes['tenant_id'] = $this->tenantId();
        $attributes['last_activity_at'] = now();

        /** @var LearningProgress $progress */
        $progress = $this->repository->create(LearningProgress::class, $attributes);

        Event::dispatch(new ProgressUpdated($progress->id, $progress->tenant_id, $progress->course_id, $progress->student_id));

        return $progress->refresh()->load(['course', 'student', 'contentItem']);
    }

    public function update(int $id, LmsMutationData $data): LearningProgress
    {
        $progress = $this->find($id);
        $attributes = $data->attributes;

        $attributes['last_activity_at'] = now();

        if (isset($attributes['progress_percent']) && (int) $attributes['progress_percent'] >= 100) {
            $attributes['completed_at'] = now();
            $attributes['status'] = 'completed';
        }

        /** @var LearningProgress $updated */
        $updated = $this->repository->update($progress, $attributes);

        Event::dispatch(new ProgressUpdated($updated->id, $updated->tenant_id, $updated->course_id, $updated->student_id));

        return $updated->refresh()->load(['course', 'student', 'contentItem']);
    }

    public function delete(int $id): void
    {
        $progress = $this->find($id);
        $this->repository->delete($progress);
    }

    private function assertCourse(int $courseId): void
    {
        $exists = Course::query()->where('tenant_id', $this->tenantId())->whereKey($courseId)->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'course_id' => ['Course must belong to tenant.'],
            ]);
        }
    }

    private function assertStudent(int $studentId): void
    {
        $exists = Student::query()->where('tenant_id', $this->tenantId())->whereKey($studentId)->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'student_id' => ['Student must belong to tenant.'],
            ]);
        }
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
