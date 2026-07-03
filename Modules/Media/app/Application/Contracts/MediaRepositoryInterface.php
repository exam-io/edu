<?php

namespace Modules\Media\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Application\DTOs\MediaListQueryData;

interface MediaRepositoryInterface
{
    public function paginate(int $tenantId, MediaListQueryData $query, array $with = []): LengthAwarePaginator;

    public function findForTenant(int $tenantId, int $id, array $with = []): ?Model;

    public function create(array $attributes): Model;

    public function update(Model $model, array $attributes): Model;

    public function delete(Model $model): void;
}
