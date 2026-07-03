<?php

namespace Modules\Academic\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Academic\Application\DTOs\AcademicListQueryData;
use Modules\Academic\Application\DTOs\AcademicMutationData;
use Modules\Academic\Domain\Models\Batch;

interface BatchServiceInterface
{
    public function list(AcademicListQueryData $query): LengthAwarePaginator;

    public function find(int $id): Batch;

    public function create(AcademicMutationData $data): Batch;

    public function update(int $id, AcademicMutationData $data): Batch;

    public function delete(int $id): void;
}
