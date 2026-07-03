<?php

namespace Modules\Academic\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Modules\Academic\Application\Contracts\ClassServiceInterface;
use Modules\Academic\Application\Contracts\TenantScopedRepositoryInterface;
use Modules\Academic\Application\DTOs\AcademicListQueryData;
use Modules\Academic\Application\DTOs\AcademicMutationData;
use Modules\Academic\Domain\Events\ClassCreated;
use Modules\Academic\Domain\Models\AcademicClass;
use Modules\Academic\Domain\Models\Program;
use Modules\Institute\Domain\Models\AcademicSession;

class ClassService extends BaseAcademicService implements ClassServiceInterface
{
    public function __construct(
        TenantScopedRepositoryInterface $repository,
        AcademicStructureService $structureService,
    ) {
        parent::__construct($repository, $structureService);
    }

    protected function modelClass(): string
    {
        return AcademicClass::class;
    }

    protected function searchColumns(): array
    {
        return ['name', 'code', 'description'];
    }

    protected function listFilters(AcademicListQueryData $query): array
    {
        return [
            'program_id' => $query->programId,
            'academic_session_id' => $query->academicSessionId,
        ];
    }

    protected function beforeCreate(array $attributes): array
    {
        $this->enforceUniqueCode(AcademicClass::class, (string) $attributes['code']);
        $this->assertProgram((int) $attributes['program_id']);
        $this->assertAcademicSession((int) $attributes['academic_session_id']);

        return [
            ...$attributes,
            'tenant_id' => $this->tenantId(),
        ];
    }

    protected function beforeUpdate(Model $model, array $attributes): array
    {
        $code = $attributes['code'] ?? $model->code;
        $this->enforceUniqueCode(AcademicClass::class, (string) $code, (int) $model->id);

        if (isset($attributes['program_id'])) {
            $this->assertProgram((int) $attributes['program_id']);
        }

        if (isset($attributes['academic_session_id'])) {
            $this->assertAcademicSession((int) $attributes['academic_session_id']);
        }

        return $attributes;
    }

    public function list(AcademicListQueryData $query): LengthAwarePaginator
    {
        return parent::list($query, ['program', 'academicSession']);
    }

    public function find(int $id): AcademicClass
    {
        /** @var AcademicClass $class */
        $class = parent::find($id, ['program', 'academicSession']);

        return $class;
    }

    public function create(AcademicMutationData $data): AcademicClass
    {
        /** @var AcademicClass $class */
        $class = parent::create($data);

        Event::dispatch(new ClassCreated($class->id, $class->tenant_id));

        return $class;
    }

    public function update(int $id, AcademicMutationData $data): AcademicClass
    {
        /** @var AcademicClass $class */
        $class = parent::update($id, $data);

        return $class;
    }

    private function assertProgram(int $programId): void
    {
        $exists = Program::query()
            ->where('tenant_id', $this->tenantId())
            ->whereKey($programId)
            ->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'program_id' => ['Program must belong to the tenant.'],
            ]);
        }
    }

    private function assertAcademicSession(int $academicSessionId): void
    {
        $exists = AcademicSession::query()
            ->whereKey($academicSessionId)
            ->whereHas('institute', function ($query): void {
                $query->where('tenant_id', $this->tenantId());
            })
            ->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'academic_session_id' => ['Academic session must belong to the tenant.'],
            ]);
        }
    }
}
