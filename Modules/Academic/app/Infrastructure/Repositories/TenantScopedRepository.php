<?php

namespace Modules\Academic\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Modules\Academic\Application\Contracts\TenantScopedRepositoryInterface;
use Modules\Academic\Application\DTOs\AcademicListQueryData;

class TenantScopedRepository implements TenantScopedRepositoryInterface
{
    public function paginate(string $modelClass, int $tenantId, AcademicListQueryData $query, array $searchColumns, array $filters = [], array $with = []): LengthAwarePaginator
    {
        $builder = $modelClass::query()->where('tenant_id', $tenantId)->with($with);

        if ($query->search !== null && $query->search !== '') {
            $searchTerm = $query->search;
            $builder->where(function ($q) use ($searchColumns, $searchTerm): void {
                foreach ($searchColumns as $column) {
                    $q->orWhere($column, 'like', "%{$searchTerm}%");
                }
            });
        }

        if ($query->status !== null && $query->status !== '') {
            $builder->where('status', $query->status);
        }

        foreach ($filters as $column => $value) {
            if ($value !== null) {
                $builder->where($column, $value);
            }
        }

        return $builder->latest('id')->paginate($query->perPage);
    }

    public function findForTenant(string $modelClass, int $tenantId, int $id, array $with = []): ?Model
    {
        return $modelClass::query()
            ->where('tenant_id', $tenantId)
            ->whereKey($id)
            ->with($with)
            ->first();
    }

    public function create(string $modelClass, array $attributes): Model
    {
        return $modelClass::query()->create($attributes);
    }

    public function update(Model $model, array $attributes): Model
    {
        $model->update($attributes);

        return $model->refresh();
    }

    public function delete(Model $model): void
    {
        $model->delete();
    }
}
