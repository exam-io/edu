<?php

namespace Modules\Academic\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Modules\Academic\Application\Contracts\DepartmentServiceInterface;
use Modules\Academic\Application\Contracts\TenantScopedRepositoryInterface;
use Modules\Academic\Application\DTOs\AcademicListQueryData;
use Modules\Academic\Application\DTOs\AcademicMutationData;
use Modules\Academic\Domain\Events\DepartmentCreated;
use Modules\Academic\Domain\Models\Department;

class DepartmentService extends BaseAcademicService implements DepartmentServiceInterface
{
    public function __construct(
        TenantScopedRepositoryInterface $repository,
        AcademicStructureService $structureService,
    ) {
        parent::__construct($repository, $structureService);
    }

    protected function modelClass(): string
    {
        return Department::class;
    }

    protected function searchColumns(): array
    {
        return ['name', 'code', 'description'];
    }

    protected function listFilters(AcademicListQueryData $query): array
    {
        return [];
    }

    protected function beforeCreate(array $attributes): array
    {
        $this->enforceUniqueCode(Department::class, (string) $attributes['code']);

        return [
            ...$attributes,
            'tenant_id' => $this->tenantId(),
        ];
    }

    protected function beforeUpdate(Model $model, array $attributes): array
    {
        $code = $attributes['code'] ?? $model->code;
        $this->enforceUniqueCode(Department::class, (string) $code, (int) $model->id);

        return $attributes;
    }

    public function list(AcademicListQueryData $query): LengthAwarePaginator
    {
        return parent::list($query);
    }

    public function find(int $id): Department
    {
        /** @var Department $department */
        $department = parent::find($id);

        return $department;
    }

    public function create(AcademicMutationData $data): Department
    {
        /** @var Department $department */
        $department = parent::create($data);

        Event::dispatch(new DepartmentCreated($department->id, $department->tenant_id));

        return $department;
    }

    public function update(int $id, AcademicMutationData $data): Department
    {
        /** @var Department $department */
        $department = parent::update($id, $data);

        return $department;
    }
}
