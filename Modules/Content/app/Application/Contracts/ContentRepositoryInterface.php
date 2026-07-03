<?php

namespace Modules\Content\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Modules\Content\Application\DTOs\ContentListQueryData;

interface ContentRepositoryInterface
{
    public function paginate(string $modelClass, int $tenantId, ContentListQueryData $query, array $searchColumns, array $filters = [], array $with = []): LengthAwarePaginator;

    public function findForTenant(string $modelClass, int $tenantId, int $id, array $with = []): ?Model;

    public function create(string $modelClass, array $attributes): Model;

    public function update(Model $model, array $attributes): Model;

    public function delete(Model $model): void;
}
