<?php

namespace Modules\LMS\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\LMS\Application\DTOs\LmsListQueryData;
use Modules\LMS\Application\DTOs\LmsMutationData;
use Modules\LMS\Domain\Models\CourseEnrollment;

interface CourseEnrollmentServiceInterface
{
    public function list(LmsListQueryData $query): LengthAwarePaginator;

    public function find(int $id): CourseEnrollment;

    public function create(LmsMutationData $data): CourseEnrollment;

    public function update(int $id, LmsMutationData $data): CourseEnrollment;

    public function delete(int $id): void;
}
