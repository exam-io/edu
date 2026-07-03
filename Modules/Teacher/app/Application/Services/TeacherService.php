<?php

namespace Modules\Teacher\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Modules\Teacher\Application\Contracts\TeacherServiceInterface;
use Modules\Teacher\Application\Contracts\TeacherTenantRepositoryInterface;
use Modules\Teacher\Application\DTOs\TeacherListQueryData;
use Modules\Teacher\Application\DTOs\TeacherMutationData;
use Modules\Teacher\Domain\Events\TeacherCreated;
use Modules\Teacher\Domain\Models\Teacher;
use Modules\Tenant\Application\Services\TenantContextService;

class TeacherService implements TeacherServiceInterface
{
    public function __construct(
        private readonly TeacherTenantRepositoryInterface $repository,
        private readonly TenantContextService $tenantContext,
    ) {
    }

    public function list(TeacherListQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginate(
            modelClass: Teacher::class,
            tenantId: $this->tenantId(),
            query: $query,
            searchColumns: ['employee_no', 'first_name', 'middle_name', 'last_name', 'email', 'phone'],
            with: ['user', 'assignments'],
        );
    }

    public function find(int $id): Teacher
    {
        $teacher = $this->repository->findForTenant(Teacher::class, $this->tenantId(), $id, ['user', 'assignments']);

        if ($teacher === null) {
            throw (new ModelNotFoundException())->setModel(Teacher::class, [$id]);
        }

        return $teacher;
    }

    public function create(TeacherMutationData $data): Teacher
    {
        $attributes = $data->attributes;
        $this->ensureUniqueEmployeeNo((string) $attributes['employee_no']);

        $provisionLogin = (bool) ($attributes['provision_login_account'] ?? false);
        unset($attributes['provision_login_account']);
        $attributes['tenant_id'] = $this->tenantId();

        /** @var Teacher $teacher */
        $teacher = $this->repository->create(Teacher::class, $attributes);
        Event::dispatch(new TeacherCreated($teacher->id, $teacher->tenant_id, $provisionLogin));

        return $teacher->refresh()->load(['user', 'assignments']);
    }

    public function update(int $id, TeacherMutationData $data): Teacher
    {
        $teacher = $this->find($id);
        $attributes = $data->attributes;

        if (isset($attributes['employee_no'])) {
            $this->ensureUniqueEmployeeNo((string) $attributes['employee_no'], (int) $teacher->id);
        }

        unset($attributes['provision_login_account']);

        /** @var Teacher $updated */
        $updated = $this->repository->update($teacher, $attributes);

        return $updated->refresh()->load(['user', 'assignments']);
    }

    public function delete(int $id): void
    {
        $teacher = $this->find($id);
        $this->repository->delete($teacher);
    }

    private function ensureUniqueEmployeeNo(string $employeeNo, ?int $ignoreId = null): void
    {
        $query = Teacher::query()->where('tenant_id', $this->tenantId())->where('employee_no', $employeeNo);
        if ($ignoreId !== null) {
            $query->whereKeyNot($ignoreId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'employee_no' => ['Employee number must be unique per tenant.'],
            ]);
        }
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
