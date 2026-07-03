<?php

namespace Modules\Student\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Student\Application\DTOs\StudentListQueryData;
use Modules\Student\Application\DTOs\StudentMutationData;
use Modules\Student\Domain\Models\Student;

interface StudentServiceInterface
{
    public function list(StudentListQueryData $query): LengthAwarePaginator;

    public function find(int $id): Student;

    public function create(StudentMutationData $data): Student;

    public function update(int $id, StudentMutationData $data): Student;

    public function delete(int $id): void;
}
