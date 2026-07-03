<?php

namespace Modules\ContentProcessing\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\ContentProcessing\Application\Contracts\ContentSourceRepositoryInterface;
use Modules\ContentProcessing\Application\DTOs\ContentSourceListQueryData;
use Modules\ContentProcessing\Domain\Models\ContentExtraction;
use Modules\ContentProcessing\Domain\Models\ContentSource;

class ContentSourceRepository implements ContentSourceRepositoryInterface
{
    public function paginate(int $tenantId, ContentSourceListQueryData $query, array $with = []): LengthAwarePaginator
    {
        $builder = ContentSource::query()->where('tenant_id', $tenantId)->with($with);

        if ($query->search !== null && $query->search !== '') {
            $search = $query->search;
            $builder->where(function ($q) use ($search): void {
                $q->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('source_ref', 'like', "%{$search}%");
            });
        }

        if ($query->status !== null && $query->status !== '') {
            $builder->where('status', $query->status);
        }

        if ($query->sourceType !== null && $query->sourceType !== '') {
            $builder->where('source_type', $query->sourceType);
        }

        return $builder->latest('id')->paginate($query->perPage);
    }

    public function findForTenant(int $tenantId, int $id, array $with = []): ?ContentSource
    {
        return ContentSource::query()
            ->where('tenant_id', $tenantId)
            ->whereKey($id)
            ->with($with)
            ->first();
    }

    public function createSource(array $attributes): ContentSource
    {
        return ContentSource::query()->create($attributes);
    }

    public function updateSource(ContentSource $source, array $attributes): ContentSource
    {
        $source->update($attributes);

        return $source->refresh();
    }

    public function createExtraction(array $attributes): ContentExtraction
    {
        return ContentExtraction::query()->create($attributes);
    }

    public function latestExtraction(int $tenantId, int $sourceId): ?ContentExtraction
    {
        return ContentExtraction::query()
            ->where('tenant_id', $tenantId)
            ->where('content_source_id', $sourceId)
            ->latest('id')
            ->first();
    }

    public function deleteSource(ContentSource $source): void
    {
        $source->delete();
    }
}
