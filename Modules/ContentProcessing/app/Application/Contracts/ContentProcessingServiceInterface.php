<?php

namespace Modules\ContentProcessing\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\ContentProcessing\Application\DTOs\ContentSourceListQueryData;
use Modules\ContentProcessing\Application\DTOs\ContentSourceMutationData;
use Modules\ContentProcessing\Domain\Models\ContentSource;

interface ContentProcessingServiceInterface
{
    public function list(ContentSourceListQueryData $query): LengthAwarePaginator;

    public function find(int $id): ContentSource;

    public function create(ContentSourceMutationData $data): ContentSource;

    public function retry(int $id): ContentSource;

    public function delete(int $id): void;
}
