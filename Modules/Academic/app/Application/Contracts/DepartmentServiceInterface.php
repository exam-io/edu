<?php

namespace Modules\Academic\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Academic\Application\DTOs\AcademicListQueryData;
use Modules\Academic\Application\DTOs\AcademicMutationData;
use Modules\Academic\Domain\Models\Department;

interface DepartmentServiceInterface
{
    public function list(AcademicListQueryData $query): LengthAwarePaginator;

    public function find(int $id): Department;

    public function create(AcademicMutationData $data): Department;

    public function update(int $id, AcademicMutationData $data): Department;

    public function delete(int $id): void;
}
