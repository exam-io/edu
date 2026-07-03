<?php

namespace Modules\ContentProcessing\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\ContentProcessing\Application\DTOs\ContentSourceListQueryData;
use Modules\ContentProcessing\Domain\Models\ContentExtraction;
use Modules\ContentProcessing\Domain\Models\ContentSource;

interface ContentSourceRepositoryInterface
{
    public function paginate(int $tenantId, ContentSourceListQueryData $query, array $with = []): LengthAwarePaginator;

    public function findForTenant(int $tenantId, int $id, array $with = []): ?ContentSource;

    public function createSource(array $attributes): ContentSource;

    public function updateSource(ContentSource $source, array $attributes): ContentSource;

    public function createExtraction(array $attributes): ContentExtraction;

    public function latestExtraction(int $tenantId, int $sourceId): ?ContentExtraction;

    public function deleteSource(ContentSource $source): void;
}
