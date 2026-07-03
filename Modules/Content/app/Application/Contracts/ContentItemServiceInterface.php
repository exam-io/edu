<?php

namespace Modules\Content\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Content\Application\DTOs\ContentListQueryData;
use Modules\Content\Application\DTOs\ContentMutationData;
use Modules\Content\Domain\Models\ContentItem;

interface ContentItemServiceInterface
{
    public function list(ContentListQueryData $query): LengthAwarePaginator;

    public function find(int $id): ContentItem;

    public function create(ContentMutationData $data): ContentItem;

    public function update(int $id, ContentMutationData $data): ContentItem;

    public function delete(int $id): void;
}
