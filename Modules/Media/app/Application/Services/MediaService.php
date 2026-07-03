<?php

namespace Modules\Media\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Media\Application\Contracts\MediaRepositoryInterface;
use Modules\Media\Application\Contracts\MediaServiceInterface;
use Modules\Media\Application\DTOs\MediaListQueryData;
use Modules\Media\Application\DTOs\MediaMutationData;
use Modules\Media\Domain\Events\MediaDeleted;
use Modules\Media\Domain\Events\MediaUploaded;
use Modules\Media\Domain\Models\MediaAsset;
use Modules\Tenant\Application\Services\TenantContextService;

class MediaService implements MediaServiceInterface
{
    public function __construct(
        private readonly MediaRepositoryInterface $repository,
        private readonly TenantContextService $tenantContext,
    ) {}

    public function list(MediaListQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginate($this->tenantId(), $query, ['uploader']);
    }

    public function find(int $id): MediaAsset
    {
        $asset = $this->repository->findForTenant($this->tenantId(), $id, ['uploader', 'links']);

        if (! $asset instanceof MediaAsset) {
            throw (new ModelNotFoundException())->setModel(MediaAsset::class, [$id]);
        }

        return $asset;
    }

    public function create(MediaMutationData $data): MediaAsset
    {
        /** @var UploadedFile $file */
        $file = $data->attributes['file'];
        $disk = (string) ($data->attributes['disk'] ?? 'local');
        $visibility = (string) ($data->attributes['visibility'] ?? 'private');

        $basePath = sprintf('tenants/%d/media/%s', $this->tenantId(), now()->format('Y/m/d'));
        $storedPath = $file->store($basePath, ['disk' => $disk]);

        $attributes = [
            'tenant_id' => $this->tenantId(),
            'uploaded_by' => auth()->id(),
            'disk' => $disk,
            'storage_path' => $storedPath,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType() ?? $file->getMimeType() ?? 'application/octet-stream',
            'extension' => $file->getClientOriginalExtension(),
            'size_bytes' => $file->getSize() ?? 0,
            'sha256' => hash_file('sha256', $file->getRealPath()),
            'visibility' => $visibility,
            'status' => 'active',
            'meta' => [
                'uuid' => (string) Str::uuid(),
            ],
        ];

        /** @var MediaAsset $asset */
        $asset = $this->repository->create($attributes);

        Event::dispatch(new MediaUploaded($asset->id, $asset->tenant_id));

        return $asset->refresh()->load('uploader');
    }

    public function update(int $id, MediaMutationData $data): MediaAsset
    {
        $asset = $this->find($id);

        /** @var MediaAsset $updated */
        $updated = $this->repository->update($asset, $data->attributes);

        return $updated->refresh()->load('uploader');
    }

    public function delete(int $id): void
    {
        $asset = $this->find($id);

        Storage::disk($asset->disk)->delete($asset->storage_path);

        $this->repository->delete($asset);

        Event::dispatch(new MediaDeleted($asset->id, $asset->tenant_id));
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
