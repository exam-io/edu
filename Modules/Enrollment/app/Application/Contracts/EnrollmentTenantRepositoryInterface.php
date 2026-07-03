<?php

namespace Modules\Enrollment\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface EnrollmentTenantRepositoryInterface
{
    public function paginate(string $modelClass, int $tenantId, int $perPage, array $with = [], ?string $status = null, ?string $search = null, array $searchColumns = [], array $filters = []): LengthAwarePaginator;

    public function findForTenant(string $modelClass, int $tenantId, int $id, array $with = []): ?Model;

    public function create(string $modelClass, array $attributes): Model;

    public function update(Model $model, array $attributes): Model;

    public function delete(Model $model): void;
}
