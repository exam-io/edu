<?php

namespace Modules\Academic\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Modules\Academic\Application\Contracts\ProgramServiceInterface;
use Modules\Academic\Application\Contracts\TenantScopedRepositoryInterface;
use Modules\Academic\Application\DTOs\AcademicListQueryData;
use Modules\Academic\Application\DTOs\AcademicMutationData;
use Modules\Academic\Domain\Events\ProgramCreated;
use Modules\Academic\Domain\Models\Department;
use Modules\Academic\Domain\Models\Program;

class ProgramService extends BaseAcademicService implements ProgramServiceInterface
{
    public function __construct(
        TenantScopedRepositoryInterface $repository,
        AcademicStructureService $structureService,
    ) {
        parent::__construct($repository, $structureService);
    }

    protected function modelClass(): string
    {
        return Program::class;
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
        $this->enforceUniqueCode(Program::class, (string) $attributes['code']);
        $this->assertDepartment((int) $attributes['department_id']);

        return [
            ...$attributes,
            'tenant_id' => $this->tenantId(),
        ];
    }

    protected function beforeUpdate(Model $model, array $attributes): array
    {
        $code = $attributes['code'] ?? $model->code;
        $this->enforceUniqueCode(Program::class, (string) $code, (int) $model->id);

        if (isset($attributes['department_id'])) {
            $this->assertDepartment((int) $attributes['department_id']);
        }

        return $attributes;
    }

    public function list(AcademicListQueryData $query): LengthAwarePaginator
    {
        return parent::list($query, ['department']);
    }

    public function find(int $id): Program
    {
        /** @var Program $program */
        $program = parent::find($id, ['department']);

        return $program;
    }

    public function create(AcademicMutationData $data): Program
    {
        /** @var Program $program */
        $program = parent::create($data);

        Event::dispatch(new ProgramCreated($program->id, $program->tenant_id));

        return $program;
    }

    public function update(int $id, AcademicMutationData $data): Program
    {
        /** @var Program $program */
        $program = parent::update($id, $data);

        return $program;
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
