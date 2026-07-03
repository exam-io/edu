<?php

namespace Modules\Teacher\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Teacher\Application\DTOs\TeacherListQueryData;
use Modules\Teacher\Application\DTOs\TeacherMutationData;
use Modules\Teacher\Domain\Models\Teacher;

interface TeacherServiceInterface
{
    public function list(TeacherListQueryData $query): LengthAwarePaginator;

    public function find(int $id): Teacher;

    public function create(TeacherMutationData $data): Teacher;

    public function update(int $id, TeacherMutationData $data): Teacher;

    public function delete(int $id): void;
}
