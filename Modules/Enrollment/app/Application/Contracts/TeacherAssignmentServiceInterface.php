<?php

namespace Modules\Enrollment\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Enrollment\Application\DTOs\TeacherAssignmentListQueryData;
use Modules\Enrollment\Application\DTOs\TeacherAssignmentMutationData;
use Modules\Enrollment\Domain\Models\TeacherAssignment;

interface TeacherAssignmentServiceInterface
{
    public function list(TeacherAssignmentListQueryData $query): LengthAwarePaginator;

    public function find(int $id): TeacherAssignment;

    public function create(TeacherAssignmentMutationData $data): TeacherAssignment;

    public function update(int $id, TeacherAssignmentMutationData $data): TeacherAssignment;

    public function delete(int $id): void;
}
