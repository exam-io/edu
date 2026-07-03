<?php

namespace Modules\Enrollment\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Modules\Academic\Domain\Models\AcademicClass;
use Modules\Academic\Domain\Models\Batch;
use Modules\Academic\Domain\Models\Section;
use Modules\Academic\Domain\Models\Subject;
use Modules\Enrollment\Application\Contracts\EnrollmentTenantRepositoryInterface;
use Modules\Enrollment\Application\Contracts\TeacherAssignmentServiceInterface;
use Modules\Enrollment\Application\DTOs\TeacherAssignmentListQueryData;
use Modules\Enrollment\Application\DTOs\TeacherAssignmentMutationData;
use Modules\Enrollment\Domain\Events\TeacherAssigned;
use Modules\Enrollment\Domain\Models\TeacherAssignment;
use Modules\Institute\Domain\Models\AcademicSession;
use Modules\Teacher\Domain\Models\Teacher;
use Modules\Tenant\Application\Services\TenantContextService;

class TeacherAssignmentService implements TeacherAssignmentServiceInterface
{
    public function __construct(
        private readonly EnrollmentTenantRepositoryInterface $repository,
        private readonly TenantContextService $tenantContext,
    ) {
    }

    public function list(TeacherAssignmentListQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginate(
            modelClass: TeacherAssignment::class,
            tenantId: $this->tenantId(),
            perPage: $query->perPage,
            with: ['teacher', 'academicSession', 'class', 'section', 'batch', 'subject'],
            status: $query->status,
            search: $query->search,
            searchColumns: ['status'],
            filters: [
                'teacher_id' => $query->teacherId,
                'academic_session_id' => $query->academicSessionId,
            ],
        );
    }

    public function find(int $id): TeacherAssignment
    {
        $assignment = $this->repository->findForTenant(TeacherAssignment::class, $this->tenantId(), $id, ['teacher', 'academicSession', 'class', 'section', 'batch', 'subject']);
        if ($assignment === null) {
            throw (new ModelNotFoundException())->setModel(TeacherAssignment::class, [$id]);
        }

        return $assignment;
    }

    public function create(TeacherAssignmentMutationData $data): TeacherAssignment
    {
        $attributes = $data->attributes;
        $attributes['tenant_id'] = $this->tenantId();

        $this->assertTeacher((int) $attributes['teacher_id']);
        $this->assertAcademicSession((int) $attributes['academic_session_id']);
        $this->assertClass($attributes['class_id'] ?? null);
        $this->assertSection($attributes['section_id'] ?? null);
        $this->assertBatch($attributes['batch_id'] ?? null);
        $this->assertSubject($attributes['subject_id'] ?? null);

        /** @var TeacherAssignment $assignment */
        $assignment = $this->repository->create(TeacherAssignment::class, $attributes);

        Event::dispatch(new TeacherAssigned($assignment->id, $assignment->teacher_id, $assignment->tenant_id));

        return $assignment->refresh()->load(['teacher', 'academicSession', 'class', 'section', 'batch', 'subject']);
    }

    public function update(int $id, TeacherAssignmentMutationData $data): TeacherAssignment
    {
        $assignment = $this->find($id);
        $attributes = $data->attributes;

        if (isset($attributes['teacher_id'])) {
            $this->assertTeacher((int) $attributes['teacher_id']);
        }
        if (isset($attributes['academic_session_id'])) {
            $this->assertAcademicSession((int) $attributes['academic_session_id']);
        }
        if (array_key_exists('class_id', $attributes)) {
            $this->assertClass($attributes['class_id']);
        }
        if (array_key_exists('section_id', $attributes)) {
            $this->assertSection($attributes['section_id']);
        }
        if (array_key_exists('batch_id', $attributes)) {
            $this->assertBatch($attributes['batch_id']);
        }
        if (array_key_exists('subject_id', $attributes)) {
            $this->assertSubject($attributes['subject_id']);
        }

        /** @var TeacherAssignment $updated */
        $updated = $this->repository->update($assignment, $attributes);

        return $updated->refresh()->load(['teacher', 'academicSession', 'class', 'section', 'batch', 'subject']);
    }

    public function delete(int $id): void
    {
        $assignment = $this->find($id);
        $this->repository->delete($assignment);
    }

    private function assertTeacher(int $teacherId): void
    {
        $exists = Teacher::query()->where('tenant_id', $this->tenantId())->whereKey($teacherId)->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'teacher_id' => ['Teacher must belong to tenant.'],
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

    private function assertClass(mixed $classId): void
    {
        if ($classId === null) {
            return;
        }

        $exists = AcademicClass::query()->where('tenant_id', $this->tenantId())->whereKey((int) $classId)->exists();

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

    private function assertSubject(mixed $subjectId): void
    {
        if ($subjectId === null) {
            return;
        }

        $exists = Subject::query()->where('tenant_id', $this->tenantId())->whereKey((int) $subjectId)->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'subject_id' => ['Subject must belong to tenant.'],
            ]);
        }
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
