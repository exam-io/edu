<?php

namespace Modules\Enrollment\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Modules\Enrollment\Application\Contracts\EnrollmentTenantRepositoryInterface;

class EnrollmentTenantRepository implements EnrollmentTenantRepositoryInterface
{
    public function paginate(string $modelClass, int $tenantId, int $perPage, array $with = [], ?string $status = null, ?string $search = null, array $searchColumns = [], array $filters = []): LengthAwarePaginator
    {
        $builder = $modelClass::query()->where('tenant_id', $tenantId)->with($with);

        if ($status !== null && $status !== '') {
            $builder->where('status', $status);
        }

        if ($search !== null && $search !== '') {
            $builder->where(function ($q) use ($searchColumns, $search): void {
                foreach ($searchColumns as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }
            });
        }

        foreach ($filters as $column => $value) {
            if ($value !== null) {
                $builder->where($column, $value);
            }
        }

        return $builder->latest('id')->paginate($perPage);
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
