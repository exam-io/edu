<?php

namespace Modules\Student\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Modules\Student\Application\Contracts\StudentRelationshipServiceInterface;
use Modules\Student\Application\Contracts\StudentServiceInterface;
use Modules\Student\Application\Contracts\StudentTenantRepositoryInterface;
use Modules\Student\Application\DTOs\StudentListQueryData;
use Modules\Student\Application\DTOs\StudentMutationData;
use Modules\Student\Domain\Events\StudentCreated;
use Modules\Student\Domain\Events\StudentUpdated;
use Modules\Student\Domain\Models\Student;
use Modules\Tenant\Application\Services\TenantContextService;

class StudentService implements StudentServiceInterface
{
    public function __construct(
        private readonly StudentTenantRepositoryInterface $repository,
        private readonly StudentRelationshipServiceInterface $relationshipService,
        private readonly TenantContextService $tenantContext,
    ) {}

    public function list(StudentListQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginate(
            modelClass: Student::class,
            tenantId: $this->tenantId(),
            query: $query,
            searchColumns: ['admission_no', 'roll_no', 'first_name', 'middle_name', 'last_name', 'email', 'phone'],
            with: ['user', 'parents', 'enrollments'],
        );
    }

    public function find(int $id): Student
    {
        $student = $this->repository->findForTenant(Student::class, $this->tenantId(), $id, ['user', 'parents', 'enrollments']);

        if ($student === null) {
            throw (new ModelNotFoundException())->setModel(Student::class, [$id]);
        }

        return $student;
    }

    public function create(StudentMutationData $data): Student
    {
        $attributes = $data->attributes;
        $this->ensureUniqueAdmissionNo((string) $attributes['admission_no']);

        return DB::transaction(function () use ($attributes): Student {
            $parentIds = $attributes['parent_ids'] ?? [];
            $primaryParentId = $attributes['primary_parent_id'] ?? null;
            $provisionLogin = (bool) ($attributes['provision_login_account'] ?? false);

            unset($attributes['parent_ids'], $attributes['primary_parent_id'], $attributes['provision_login_account']);
            $attributes['tenant_id'] = $this->tenantId();

            /** @var Student $student */
            $student = $this->repository->create(Student::class, $attributes);

            $this->relationshipService->syncParents($student, is_array($parentIds) ? $parentIds : [], $primaryParentId);

            Event::dispatch(new StudentCreated($student->id, $student->tenant_id, $provisionLogin));

            return $student->refresh()->load(['user', 'parents', 'enrollments']);
        });
    }

    public function update(int $id, StudentMutationData $data): Student
    {
        $student = $this->find($id);
        $attributes = $data->attributes;

        if (isset($attributes['admission_no'])) {
            $this->ensureUniqueAdmissionNo((string) $attributes['admission_no'], (int) $student->id);
        }

        return DB::transaction(function () use ($student, $attributes): Student {
            $parentIds = $attributes['parent_ids'] ?? null;
            $primaryParentId = $attributes['primary_parent_id'] ?? null;

            unset($attributes['parent_ids'], $attributes['primary_parent_id'], $attributes['provision_login_account']);

            /** @var Student $updated */
            $updated = $this->repository->update($student, $attributes);

            if (is_array($parentIds)) {
                $this->relationshipService->syncParents($updated, $parentIds, $primaryParentId);
            }

            Event::dispatch(new StudentUpdated($updated->id, $updated->tenant_id));

            return $updated->refresh()->load(['user', 'parents', 'enrollments']);
        });
    }

    public function delete(int $id): void
    {
        $student = $this->find($id);
        $this->repository->delete($student);
    }

    private function ensureUniqueAdmissionNo(string $admissionNo, ?int $ignoreId = null): void
    {
        $query = Student::query()->where('tenant_id', $this->tenantId())->where('admission_no', $admissionNo);

        if ($ignoreId !== null) {
            $query->whereKeyNot($ignoreId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'admission_no' => ['Admission number must be unique per tenant.'],
            ]);
        }
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
