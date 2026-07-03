<?php

namespace Modules\Course\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Modules\Course\Application\Contracts\CourseRepositoryInterface;
use Modules\Course\Application\DTOs\CourseListQueryData;
use Modules\Course\Domain\Models\Course;

class CourseRepository implements CourseRepositoryInterface
{
    public function paginate(int $tenantId, CourseListQueryData $query, array $with = []): LengthAwarePaginator
    {
        $builder = Course::query()->where('tenant_id', $tenantId)->with($with);

        if ($query->search !== null && $query->search !== '') {
            $search = $query->search;
            $builder->where(function ($q) use ($search): void {
                $q->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($query->status !== null && $query->status !== '') {
            $builder->where('status', $query->status);
        }

        if ($query->level !== null && $query->level !== '') {
            $builder->where('level', $query->level);
        }

        return $builder->latest('id')->paginate($query->perPage);
    }

    public function findForTenant(int $tenantId, int $id, array $with = []): ?Model
    {
        return Course::query()
            ->where('tenant_id', $tenantId)
            ->whereKey($id)
            ->with($with)
            ->first();
    }

    public function create(array $attributes): Model
    {
        return Course::query()->create($attributes);
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
