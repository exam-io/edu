<?php

namespace Modules\Course\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Course\Application\DTOs\CourseListQueryData;
use Modules\Course\Application\DTOs\CourseMutationData;
use Modules\Course\Domain\Models\Course;

interface CourseServiceInterface
{
    public function list(CourseListQueryData $query): LengthAwarePaginator;

    public function find(int $id): Course;

    public function create(CourseMutationData $data): Course;

    public function update(int $id, CourseMutationData $data): Course;

    public function delete(int $id): void;
}
