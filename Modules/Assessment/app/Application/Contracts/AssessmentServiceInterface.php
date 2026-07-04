<?php

namespace Modules\Assessment\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Assessment\Application\DTOs\AssessmentMutationData;
use Modules\Assessment\Application\DTOs\AssessmentQueryData;
use Modules\Assessment\Domain\Models\Assessment;

interface AssessmentServiceInterface
{
    public function list(AssessmentQueryData $query): LengthAwarePaginator;

    public function find(int $id): Assessment;

    public function create(AssessmentMutationData $data): Assessment;

    public function update(int $id, AssessmentMutationData $data): Assessment;

    public function delete(int $id): void;

    public function publish(int $id): Assessment;
}
