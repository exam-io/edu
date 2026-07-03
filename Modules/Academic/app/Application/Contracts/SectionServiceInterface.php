<?php

namespace Modules\Academic\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Academic\Application\DTOs\AcademicListQueryData;
use Modules\Academic\Application\DTOs\AcademicMutationData;
use Modules\Academic\Domain\Models\Section;

interface SectionServiceInterface
{
    public function list(AcademicListQueryData $query): LengthAwarePaginator;

    public function find(int $id): Section;

    public function create(AcademicMutationData $data): Section;

    public function update(int $id, AcademicMutationData $data): Section;

    public function delete(int $id): void;
}
