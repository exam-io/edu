<?php

namespace Modules\Academic\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Modules\Academic\Application\Contracts\SectionServiceInterface;
use Modules\Academic\Application\Contracts\TenantScopedRepositoryInterface;
use Modules\Academic\Application\DTOs\AcademicListQueryData;
use Modules\Academic\Application\DTOs\AcademicMutationData;
use Modules\Academic\Domain\Events\SectionCreated;
use Modules\Academic\Domain\Models\AcademicClass;
use Modules\Academic\Domain\Models\Section;

class SectionService extends BaseAcademicService implements SectionServiceInterface
{
    public function __construct(
        TenantScopedRepositoryInterface $repository,
        AcademicStructureService $structureService,
    ) {
        parent::__construct($repository, $structureService);
    }

    protected function modelClass(): string
    {
        return Section::class;
    }

    protected function searchColumns(): array
    {
        return ['name', 'code'];
    }

    protected function listFilters(AcademicListQueryData $query): array
    {
        return [
            'class_id' => $query->classId,
        ];
    }

    protected function beforeCreate(array $attributes): array
    {
        $this->enforceUniqueCode(Section::class, (string) $attributes['code']);
        $this->assertClass((int) $attributes['class_id']);
        $this->assertCapacity((int) $attributes['capacity']);

        return [
            ...$attributes,
            'tenant_id' => $this->tenantId(),
        ];
    }

    protected function beforeUpdate(Model $model, array $attributes): array
    {
        $code = $attributes['code'] ?? $model->code;
        $this->enforceUniqueCode(Section::class, (string) $code, (int) $model->id);

        if (isset($attributes['class_id'])) {
            $this->assertClass((int) $attributes['class_id']);
        }

        if (isset($attributes['capacity'])) {
            $this->assertCapacity((int) $attributes['capacity']);
        }

        return $attributes;
    }

    public function list(AcademicListQueryData $query): LengthAwarePaginator
    {
        return parent::list($query, ['class']);
    }

    public function find(int $id): Section
    {
        /** @var Section $section */
        $section = parent::find($id, ['class']);

        return $section;
    }

    public function create(AcademicMutationData $data): Section
    {
        /** @var Section $section */
        $section = parent::create($data);

        Event::dispatch(new SectionCreated($section->id, $section->tenant_id));

        return $section;
    }

    public function update(int $id, AcademicMutationData $data): Section
    {
        /** @var Section $section */
        $section = parent::update($id, $data);

        return $section;
    }

    private function assertClass(int $classId): void
    {
        $exists = AcademicClass::query()
            ->where('tenant_id', $this->tenantId())
            ->whereKey($classId)
            ->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'class_id' => ['Class must belong to the tenant.'],
            ]);
        }
    }

    private function assertCapacity(int $capacity): void
    {
        if ($capacity <= 0) {
            throw ValidationException::withMessages([
                'capacity' => ['Capacity must be positive.'],
            ]);
        }
    }
}
