<?php

namespace Modules\Enrollment\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Modules\Academic\Domain\Models\AcademicClass;
use Modules\Academic\Domain\Models\Batch;
use Modules\Academic\Domain\Models\Section;
use Modules\Enrollment\Application\Contracts\EnrollmentServiceInterface;
use Modules\Enrollment\Application\Contracts\EnrollmentTenantRepositoryInterface;
use Modules\Enrollment\Application\DTOs\EnrollmentListQueryData;
use Modules\Enrollment\Application\DTOs\EnrollmentMutationData;
use Modules\Enrollment\Domain\Events\StudentEnrolled;
use Modules\Enrollment\Domain\Models\StudentEnrollment;
use Modules\Institute\Domain\Models\AcademicSession;
use Modules\Student\Domain\Models\Student;
use Modules\Tenant\Application\Services\TenantContextService;

class EnrollmentService implements EnrollmentServiceInterface
{
    public function __construct(
        private readonly EnrollmentTenantRepositoryInterface $repository,
        private readonly TenantContextService $tenantContext,
    ) {
    }

    public function list(EnrollmentListQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginate(
            modelClass: StudentEnrollment::class,
            tenantId: $this->tenantId(),
            perPage: $query->perPage,
            with: ['student', 'academicSession', 'class', 'section', 'batch'],
            status: $query->status,
            search: $query->search,
            searchColumns: ['status'],
            filters: [
                'student_id' => $query->studentId,
                'academic_session_id' => $query->academicSessionId,
                'class_id' => $query->classId,
            ],
        );
    }

    public function find(int $id): StudentEnrollment
    {
        $enrollment = $this->repository->findForTenant(StudentEnrollment::class, $this->tenantId(), $id, ['student', 'academicSession', 'class', 'section', 'batch']);
        if ($enrollment === null) {
            throw (new ModelNotFoundException())->setModel(StudentEnrollment::class, [$id]);
        }

        return $enrollment;
    }

    public function create(EnrollmentMutationData $data): StudentEnrollment
    {
        $attributes = $data->attributes;
        $attributes['tenant_id'] = $this->tenantId();

        $this->assertStudent((int) $attributes['student_id']);
        $this->assertAcademicSession((int) $attributes['academic_session_id']);
        $this->assertClass((int) $attributes['class_id']);
        $this->assertSection($attributes['section_id'] ?? null);
        $this->assertBatch($attributes['batch_id'] ?? null);
        $this->assertSingleActiveEnrollment(
            studentId: (int) $attributes['student_id'],
            academicSessionId: (int) $attributes['academic_session_id'],
            status: (string) ($attributes['status'] ?? 'active'),
        );

        /** @var StudentEnrollment $enrollment */
        $enrollment = $this->repository->create(StudentEnrollment::class, $attributes);

        Event::dispatch(new StudentEnrolled($enrollment->id, $enrollment->student_id, $enrollment->tenant_id));

        return $enrollment->refresh()->load(['student', 'academicSession', 'class', 'section', 'batch']);
    }

    public function update(int $id, EnrollmentMutationData $data): StudentEnrollment
    {
        $enrollment = $this->find($id);
        $attributes = $data->attributes;

        if (isset($attributes['student_id'])) {
            $this->assertStudent((int) $attributes['student_id']);
        }
        if (isset($attributes['academic_session_id'])) {
            $this->assertAcademicSession((int) $attributes['academic_session_id']);
        }
        if (isset($attributes['class_id'])) {
            $this->assertClass((int) $attributes['class_id']);
        }
        if (array_key_exists('section_id', $attributes)) {
            $this->assertSection($attributes['section_id']);
        }
        if (array_key_exists('batch_id', $attributes)) {
            $this->assertBatch($attributes['batch_id']);
        }

        $nextStudentId = (int) ($attributes['student_id'] ?? $enrollment->student_id);
        $nextSessionId = (int) ($attributes['academic_session_id'] ?? $enrollment->academic_session_id);
        $nextStatus = (string) ($attributes['status'] ?? $enrollment->status);

        $this->assertSingleActiveEnrollment($nextStudentId, $nextSessionId, $nextStatus, (int) $enrollment->id);

        /** @var StudentEnrollment $updated */
        $updated = $this->repository->update($enrollment, $attributes);

        return $updated->refresh()->load(['student', 'academicSession', 'class', 'section', 'batch']);
    }

    public function delete(int $id): void
    {
        $enrollment = $this->find($id);
        $this->repository->delete($enrollment);
    }

    private function assertSingleActiveEnrollment(int $studentId, int $academicSessionId, string $status, ?int $ignoreId = null): void
    {
        if ($status !== 'active') {
            return;
        }

        $query = StudentEnrollment::query()
            ->where('tenant_id', $this->tenantId())
            ->where('student_id', $studentId)
            ->where('academic_session_id', $academicSessionId)
            ->where('status', 'active');

        if ($ignoreId !== null) {
            $query->whereKeyNot($ignoreId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'student_id' => ['Only one active enrollment is allowed per student per session.'],
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

    private function assertAcademicSession(int $sessionId): void
    {
        $exists = AcademicSession::query()
            ->whereKey($sessionId)
            ->whereHas('institute', function ($q): void {
                $q->where('tenant_id', $this->tenantId());
            })
            ->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'academic_session_id' => ['Academic session must belong to tenant.'],
            ]);
        }
    }

    private function assertClass(int $classId): void
    {
        $exists = AcademicClass::query()->where('tenant_id', $this->tenantId())->whereKey($classId)->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'class_id' => ['Class must belong to tenant.'],
            ]);
        }
    }

    private function assertSection(mixed $sectionId): void
    {
        if ($sectionId === null) {
            return;
        }

        $exists = Section::query()->where('tenant_id', $this->tenantId())->whereKey((int) $sectionId)->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'section_id' => ['Section must belong to tenant.'],
            ]);
        }
    }

    private function assertBatch(mixed $batchId): void
    {
        if ($batchId === null) {
            return;
        }

        $exists = Batch::query()->where('tenant_id', $this->tenantId())->whereKey((int) $batchId)->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'batch_id' => ['Batch must belong to tenant.'],
            ]);
        }
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
