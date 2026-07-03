<?php

namespace Modules\Media\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Application\Contracts\MediaRepositoryInterface;
use Modules\Media\Application\DTOs\MediaListQueryData;
use Modules\Media\Domain\Models\MediaAsset;

class MediaRepository implements MediaRepositoryInterface
{
    public function paginate(int $tenantId, MediaListQueryData $query, array $with = []): LengthAwarePaginator
    {
        $builder = MediaAsset::query()->where('tenant_id', $tenantId)->with($with);

        if ($query->search !== null && $query->search !== '') {
            $search = $query->search;
            $builder->where(function ($q) use ($search): void {
                $q->orWhere('original_name', 'like', "%{$search}%")
                    ->orWhere('mime_type', 'like', "%{$search}%")
                    ->orWhere('storage_path', 'like', "%{$search}%");
            });
        }

        if ($query->mimeType !== null && $query->mimeType !== '') {
            $builder->where('mime_type', $query->mimeType);
        }

        if ($query->status !== null && $query->status !== '') {
            $builder->where('status', $query->status);
        }

        return $builder->latest('id')->paginate($query->perPage);
    }

    public function findForTenant(int $tenantId, int $id, array $with = []): ?Model
    {
        return MediaAsset::query()
            ->where('tenant_id', $tenantId)
            ->whereKey($id)
            ->with($with)
            ->first();
    }

    public function create(array $attributes): Model
    {
        return MediaAsset::query()->create($attributes);
    }

    public function update(Model $model, array $attributes): Model
    {
        $model->update($attributes);

        return $model->refresh();
    }

    public function delete(Model $model): void
    {
        $model->delete();
    }
}