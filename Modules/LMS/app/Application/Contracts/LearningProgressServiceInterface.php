<?php

namespace Modules\LMS\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\LMS\Application\DTOs\LmsListQueryData;
use Modules\LMS\Application\DTOs\LmsMutationData;
use Modules\LMS\Domain\Models\LearningProgress;

interface LearningProgressServiceInterface
{
    public function list(LmsListQueryData $query): LengthAwarePaginator;

    public function find(int $id): LearningProgress;

    public function create(LmsMutationData $data): LearningProgress;

    public function update(int $id, LmsMutationData $data): LearningProgress;

    public function delete(int $id): void;
}
