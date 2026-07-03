<?php

namespace Modules\Content\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Content\Application\DTOs\ContentListQueryData;
use Modules\Content\Application\DTOs\ContentMutationData;
use Modules\Content\Domain\Models\CourseSection;

interface CourseSectionServiceInterface
{
    public function list(ContentListQueryData $query): LengthAwarePaginator;

    public function find(int $id): CourseSection;

    public function create(ContentMutationData $data): CourseSection;

    public function update(int $id, ContentMutationData $data): CourseSection;

    public function delete(int $id): void;
}
