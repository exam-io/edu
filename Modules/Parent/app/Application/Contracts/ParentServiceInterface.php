<?php

namespace Modules\Parent\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Parent\Application\DTOs\ParentListQueryData;
use Modules\Parent\Application\DTOs\ParentMutationData;
use Modules\Parent\Domain\Models\ParentProfile;

interface ParentServiceInterface
{
    public function list(ParentListQueryData $query): LengthAwarePaginator;

    public function find(int $id): ParentProfile;

    public function create(ParentMutationData $data): ParentProfile;

    public function update(int $id, ParentMutationData $data): ParentProfile;

    public function delete(int $id): void;
}
