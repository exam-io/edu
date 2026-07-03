<?php

namespace Modules\Academic\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Modules\Academic\Application\Contracts\TenantScopedRepositoryInterface;
use Modules\Academic\Application\DTOs\AcademicListQueryData;
use Modules\Academic\Application\DTOs\AcademicMutationData;

abstract class BaseAcademicService
{
    public function __construct(
        protected readonly TenantScopedRepositoryInterface $repository,
        protected readonly AcademicStructureService $structureService,
    ) {}

    abstract protected function modelClass(): string;

    abstract protected function searchColumns(): array;

    abstract protected function listFilters(AcademicListQueryData $query): array;

    abstract protected function beforeCreate(array $attributes): array;

    abstract protected function beforeUpdate(Model $model, array $attributes): array;

    public function list(AcademicListQueryData $query, array $with = []): LengthAwarePaginator
    {
        return $this->repository->paginate(
            modelClass: $this->modelClass(),
            tenantId: $this->structureService->tenantId(),
            query: $query,
            searchColumns: $this->searchColumns(),
            filters: $this->listFilters($query),
            with: $with,
        );
    }

    public function find(int $id, array $with = []): Model
    {
        $model = $this->repository->findForTenant(
            modelClass: $this->modelClass(),
            tenantId: $this->structureService->tenantId(),
            id: $id,
            with: $with,
        );

        if ($model === null) {
            throw (new ModelNotFoundException())->setModel($this->modelClass(), [$id]);
        }

        return $model;
    }

    public function create(AcademicMutationData $data): Model
    {
        $attributes = $this->beforeCreate($data->attributes);

        return $this->repository->create($this->modelClass(), $attributes);
    }

    public function update(int $id, AcademicMutationData $data): Model
    {
        $model = $this->find($id);
        $attributes = $this->beforeUpdate($model, $data->attributes);

        return $this->repository->update($model, $attributes);
    }

    public function delete(int $id): void
    {
        $model = $this->find($id);
        $this->repository->delete($model);
    }

    protected function enforceUniqueCode(string $modelClass, string $code, ?int $ignoreId = null): void
    {
        $query = $modelClass::query()
            ->where('tenant_id', $this->structureService->tenantId())
            ->where('code', $code);

        if ($ignoreId !== null) {
            $query->whereKeyNot($ignoreId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'code' => ['Code must be unique within tenant.'],
            ]);
        }
    }

    protected function tenantId(): int
    {
        return $this->structureService->tenantId();
    }
}
