<?php

namespace Modules\Teacher\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Modules\Teacher\Application\DTOs\TeacherListQueryData;

interface TeacherTenantRepositoryInterface
{
    public function paginate(string $modelClass, int $tenantId, TeacherListQueryData $query, array $searchColumns, array $filters = [], array $with = []): LengthAwarePaginator;

    public function findForTenant(string $modelClass, int $tenantId, int $id, array $with = []): ?Model;

    public function create(string $modelClass, array $attributes): Model;

    public function update(Model $model, array $attributes): Model;

    public function delete(Model $model): void;
}
