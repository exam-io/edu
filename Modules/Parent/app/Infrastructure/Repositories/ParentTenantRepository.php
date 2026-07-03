<?php

namespace Modules\Parent\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Modules\Parent\Application\Contracts\ParentTenantRepositoryInterface;
use Modules\Parent\Application\DTOs\ParentListQueryData;

class ParentTenantRepository implements ParentTenantRepositoryInterface
{
    public function paginate(string $modelClass, int $tenantId, ParentListQueryData $query, array $searchColumns, array $filters = [], array $with = []): LengthAwarePaginator
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
        return $modelClass::query()->where('tenant_id', $tenantId)->whereKey($id)->with($with)->first();
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
