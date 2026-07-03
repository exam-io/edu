<?php

namespace Modules\Academic\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Modules\Academic\Application\Contracts\BatchServiceInterface;
use Modules\Academic\Application\Contracts\TenantScopedRepositoryInterface;
use Modules\Academic\Application\DTOs\AcademicListQueryData;
use Modules\Academic\Application\DTOs\AcademicMutationData;
use Modules\Academic\Domain\Events\BatchCreated;
use Modules\Academic\Domain\Models\AcademicClass;
use Modules\Academic\Domain\Models\Batch;

class BatchService extends BaseAcademicService implements BatchServiceInterface
{
    public function __construct(
        TenantScopedRepositoryInterface $repository,
        AcademicStructureService $structureService,
    ) {
        parent::__construct($repository, $structureService);
    }

    protected function modelClass(): string
    {
        return Batch::class;
    }

    protected function searchColumns(): array
    {
        return ['name'];
    }

    protected function listFilters(AcademicListQueryData $query): array
    {
        return [
            'class_id' => $query->classId,
        ];
    }

    protected function beforeCreate(array $attributes): array
    {
        $this->assertClass((int) $attributes['class_id']);
        $this->assertCapacity((int) $attributes['capacity']);

        return [
            ...$attributes,
            'tenant_id' => $this->tenantId(),
        ];
    }

    protected function beforeUpdate(Model $model, array $attributes): array
    {
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

    public function find(int $id): Batch
    {
        /** @var Batch $batch */
        $batch = parent::find($id, ['class']);

        return $batch;
    }

    public function create(AcademicMutationData $data): Batch
    {
        /** @var Batch $batch */
        $batch = parent::create($data);

        Event::dispatch(new BatchCreated($batch->id, $batch->tenant_id));

        return $batch;
    }

    public function update(int $id, AcademicMutationData $data): Batch
    {
        /** @var Batch $batch */
        $batch = parent::update($id, $data);

        return $batch;
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
