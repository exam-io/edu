<?php

namespace Modules\Enrollment\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Enrollment\Application\DTOs\EnrollmentListQueryData;
use Modules\Enrollment\Application\DTOs\EnrollmentMutationData;
use Modules\Enrollment\Domain\Models\StudentEnrollment;

interface EnrollmentServiceInterface
{
    public function list(EnrollmentListQueryData $query): LengthAwarePaginator;

    public function find(int $id): StudentEnrollment;

    public function create(EnrollmentMutationData $data): StudentEnrollment;

    public function update(int $id, EnrollmentMutationData $data): StudentEnrollment;

    public function delete(int $id): void;
}
