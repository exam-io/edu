<?php

namespace Modules\Course\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Modules\Course\Application\DTOs\CourseListQueryData;

interface CourseRepositoryInterface
{
    public function paginate(int $tenantId, CourseListQueryData $query, array $with = []): LengthAwarePaginator;

    public function findForTenant(int $tenantId, int $id, array $with = []): ?Model;

    public function create(array $attributes): Model;

    public function update(Model $model, array $attributes): Model;

    public function delete(Model $model): void;
}
