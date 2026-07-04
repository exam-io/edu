<?php

namespace Modules\Assignment\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Assignment\Application\DTOs\AssignmentSubmissionData;
use Modules\Assignment\Domain\Models\AssignmentSubmission;

interface AssignmentSubmissionServiceInterface
{
    public function submit(AssignmentSubmissionData $data): AssignmentSubmission;

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator;
}
