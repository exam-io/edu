<?php

namespace Modules\LMS\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Modules\Course\Domain\Models\Course;
use Modules\LMS\Application\Contracts\CourseEnrollmentServiceInterface;
use Modules\LMS\Application\Contracts\LmsRepositoryInterface;
use Modules\LMS\Application\DTOs\LmsListQueryData;
use Modules\LMS\Application\DTOs\LmsMutationData;
use Modules\LMS\Domain\Events\CourseEnrollmentCreated;
use Modules\LMS\Domain\Models\CourseEnrollment;
use Modules\Student\Domain\Models\Student;
use Modules\Tenant\Application\Services\TenantContextService;

class CourseEnrollmentService implements CourseEnrollmentServiceInterface
{
    public function __construct(
        private readonly LmsRepositoryInterface $repository,
        private readonly TenantContextService $tenantContext,
    ) {}

    public function list(LmsListQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginate(
            modelClass: CourseEnrollment::class,
            tenantId: $this->tenantId(),
            query: $query,
            searchColumns: ['status'],
            filters: [
                'course_id' => $query->courseId,
                'student_id' => $query->studentId,
            ],
            with: ['course', 'student'],
        );
    }

    public function find(int $id): CourseEnrollment
    {
        $enrollment = $this->repository->findForTenant(CourseEnrollment::class, $this->tenantId(), $id, ['course', 'student']);

        if (! $enrollment instanceof CourseEnrollment) {
            throw (new ModelNotFoundException())->setModel(CourseEnrollment::class, [$id]);
        }

        return $enrollment;
    }

    public function create(LmsMutationData $data): CourseEnrollment
    {
        $attributes = $data->attributes;
        $this->assertCourse((int) $attributes['course_id']);
        $this->assertStudent((int) $attributes['student_id']);
        $this->assertUnique((int) $attributes['course_id'], (int) $attributes['student_id']);

        $attributes['tenant_id'] = $this->tenantId();

        /** @var CourseEnrollment $enrollment */
        $enrollment = $this->repository->create(CourseEnrollment::class, $attributes);

        Event::dispatch(new CourseEnrollmentCreated($enrollment->id, $enrollment->tenant_id, $enrollment->course_id, $enrollment->student_id));

        return $enrollment->refresh()->load(['course', 'student']);
    }

    public function update(int $id, LmsMutationData $data): CourseEnrollment
    {
        $enrollment = $this->find($id);
        $attributes = $data->attributes;

        /** @var CourseEnrollment $updated */
        $updated = $this->repository->update($enrollment, $attributes);

        return $updated->refresh()->load(['course', 'student']);
    }

    public function delete(int $id): void
    {
        $enrollment = $this->find($id);
        $this->repository->delete($enrollment);
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

    private function assertUnique(int $courseId, int $studentId): void
    {
        $exists = CourseEnrollment::query()
            ->where('tenant_id', $this->tenantId())
            ->where('course_id', $courseId)
            ->where('student_id', $studentId)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'student_id' => ['Student is already enrolled in this course.'],
            ]);
        }
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
