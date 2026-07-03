<?php

namespace Modules\Academic\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Modules\Academic\Application\Contracts\SubjectServiceInterface;
use Modules\Academic\Application\Contracts\TenantScopedRepositoryInterface;
use Modules\Academic\Application\DTOs\AcademicListQueryData;
use Modules\Academic\Application\DTOs\AcademicMutationData;
use Modules\Academic\Domain\Events\SubjectCreated;
use Modules\Academic\Domain\Models\Department;
use Modules\Academic\Domain\Models\Subject;

class SubjectService extends BaseAcademicService implements SubjectServiceInterface
{
    public function __construct(
        TenantScopedRepositoryInterface $repository,
        AcademicStructureService $structureService,
    ) {
        parent::__construct($repository, $structureService);
    }

    protected function modelClass(): string
    {
        return Subject::class;
    }

    protected function searchColumns(): array
    {
        return ['name', 'code', 'description'];
    }

    protected function listFilters(AcademicListQueryData $query): array
    {
        return [
            'department_id' => $query->departmentId,
        ];
    }

    protected function beforeCreate(array $attributes): array
    {
        $this->enforceUniqueCode(Subject::class, (string) $attributes['code']);
        $this->assertDepartment((int) $attributes['department_id']);

        return [
            ...$attributes,
            'tenant_id' => $this->tenantId(),
        ];
    }

    protected function beforeUpdate(Model $model, array $attributes): array
    {
        $code = $attributes['code'] ?? $model->code;
        $this->enforceUniqueCode(Subject::class, (string) $code, (int) $model->id);

        if (isset($attributes['department_id'])) {
            $this->assertDepartment((int) $attributes['department_id']);
        }

        return $attributes;
    }

    public function list(AcademicListQueryData $query): LengthAwarePaginator
    {
        return parent::list($query, ['department']);
    }

    public function find(int $id): Subject
    {
        /** @var Subject $subject */
        $subject = parent::find($id, ['department']);

        return $subject;
    }

    public function create(AcademicMutationData $data): Subject
    {
        /** @var Subject $subject */
        $subject = parent::create($data);

        Event::dispatch(new SubjectCreated($subject->id, $subject->tenant_id));

        return $subject;
    }

    public function update(int $id, AcademicMutationData $data): Subject
    {
        /** @var Subject $subject */
        $subject = parent::update($id, $data);

        return $subject;
    }

    private function assertDepartment(int $departmentId): void
    {
        $exists = Department::query()
            ->where('tenant_id', $this->tenantId())
            ->whereKey($departmentId)
            ->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'department_id' => ['Department must belong to the tenant.'],
            ]);
        }
    }
}
