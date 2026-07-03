<?php

namespace Modules\Academic\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Academic\Application\DTOs\AcademicListQueryData;
use Modules\Academic\Application\DTOs\AcademicMutationData;
use Modules\Academic\Domain\Models\AcademicClass;

interface ClassServiceInterface
{
    public function list(AcademicListQueryData $query): LengthAwarePaginator;

    public function find(int $id): AcademicClass;

    public function create(AcademicMutationData $data): AcademicClass;

    public function update(int $id, AcademicMutationData $data): AcademicClass;

    public function delete(int $id): void;
}
