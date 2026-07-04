<?php

namespace Modules\Assignment\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Assignment\Domain\Models\AssignmentSubmission;

interface AssignmentSubmissionRepositoryInterface
{
    public function create(array $attributes): AssignmentSubmission;

    public function paginate(int $tenantId, int $perPage, array $filters = []): LengthAwarePaginator;
}
