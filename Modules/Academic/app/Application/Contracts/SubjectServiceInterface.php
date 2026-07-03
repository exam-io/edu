<?php

namespace Modules\Academic\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Academic\Application\DTOs\AcademicListQueryData;
use Modules\Academic\Application\DTOs\AcademicMutationData;
use Modules\Academic\Domain\Models\Subject;

interface SubjectServiceInterface
{
    public function list(AcademicListQueryData $query): LengthAwarePaginator;

    public function find(int $id): Subject;

    public function create(AcademicMutationData $data): Subject;

    public function update(int $id, AcademicMutationData $data): Subject;

    public function delete(int $id): void;
}
