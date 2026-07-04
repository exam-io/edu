<?php

namespace Modules\ContentProcessing\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Modules\ContentProcessing\Application\Contracts\ContentProcessingServiceInterface;
use Modules\ContentProcessing\Application\Contracts\ContentSourceRepositoryInterface;
use Modules\ContentProcessing\Application\Contracts\ExtractionPipelineInterface;
use Modules\ContentProcessing\Application\DTOs\ContentSourceListQueryData;
use Modules\ContentProcessing\Application\DTOs\ContentSourceMutationData;
use Modules\ContentProcessing\Domain\Events\ContentExtracted;
use Modules\ContentProcessing\Domain\Events\ContentSourceUploaded;
use Modules\ContentProcessing\Domain\Models\ContentExtraction;
use Modules\ContentProcessing\Domain\Models\ContentSource;
use Modules\Tenant\Application\Services\TenantContextService;

class ContentProcessingService implements ContentProcessingServiceInterface
{
    public function __construct(
        private readonly ContentSourceRepositoryInterface $repository,
        private readonly ExtractionPipelineInterface $pipeline,
        private readonly TenantContextService $tenantContext,
    ) {}

    public function list(ContentSourceListQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginate($this->tenantId(), $query, ['uploader', 'extractions']);
    }

    public function find(int $id): ContentSource
    {
        $source = $this->repository->findForTenant($this->tenantId(), $id, ['uploader', 'extractions']);

        if (! $source instanceof ContentSource) {
            throw (new ModelNotFoundException())->setModel(ContentSource::class, [$id]);
        }

        return $source;
    }

    public function create(ContentSourceMutationData $data): ContentSource
    {
        $attributes = $data->attributes;

        $sourceType = (string) ($attributes['source_type'] ?? 'upload');
        $mimeType = null;
        $sourceRef = null;
        $meta = [];

        if ($sourceType === 'upload' && isset($attributes['file']) && $attributes['file'] instanceof UploadedFile) {
            /** @var UploadedFile $file */
            $file = $attributes['file'];
            $disk = 'local';
            $basePath = sprintf('tenants/%d/content-processing/raw/%s', $this->tenantId(), now()->format('Y/m/d'));
            $storedPath = $file->store($basePath, ['disk' => $disk]);

            $mimeType = $file->getClientMimeType() ?? $file->getMimeType() ?? 'application/octet-stream';
            $sourceRef = $storedPath;
            $meta = [
                'disk' => $disk,
                'storage_path' => $storedPath,
                'original_name' => $file->getClientOriginalName(),
                'size_bytes' => $file->getSize() ?? 0,
                'sha256' => hash_file('sha256', $file->getRealPath()),
            ];
        } elseif ($sourceType === 'text') {
            $mimeType = 'text/plain';
            $sourceRef = 'inline:text';
            $meta = [
                'raw_text' => (string) ($attributes['raw_text'] ?? ''),
            ];
        } else {
            $sourceType = 'url';
            $url = trim((string) ($attributes['source_url'] ?? ''));

            if ($url === '' || ! filter_var($url, FILTER_VALIDATE_URL)) {
                throw ValidationException::withMessages([
                    'source_url' => ['A valid source_url is required for URL sources.'],
                ]);
            }

            $mimeType = 'text/html';
            $sourceRef = $url;
            $meta = [
                'source_url' => $url,
            ];
        }

        $source = $this->repository->createSource([
            'tenant_id' => $this->tenantId(),
            'uploaded_by' => auth()->id(),
            'title' => (string) ($attributes['title'] ?? 'Untitled Source'),
            'source_type' => $sourceType,
            'source_ref' => $sourceRef,
            'mime_type' => $mimeType,
            'status' => 'queued',
            'meta' => $meta,
        ]);

        Event::dispatch(new ContentSourceUploaded($source->id, $source->tenant_id));

        return $source->refresh()->load('uploader');
    }

    public function retry(int $id): ContentSource
    {
        $source = $this->find($id);

        $source = $this->repository->updateSource($source, [
            'status' => 'queued',
            'processed_at' => null,
        ]);

        Event::dispatch(new ContentSourceUploaded($source->id, $source->tenant_id));

        return $source;
    }

    public function delete(int $id): void
    {
        $source = $this->find($id);

        $disk = (string) data_get($source->meta, 'disk', '');
        $path = (string) data_get($source->meta, 'storage_path', '');

        if ($disk !== '' && $path !== '') {
            Storage::disk($disk)->delete($path);
        }

        $this->repository->deleteSource($source);
    }

    public function processExtraction(int $sourceId): ContentExtraction
    {
        $source = $this->find($sourceId);

        $this->repository->updateSource($source, ['status' => 'processing']);

        $result = $this->pipeline->extract($source);
        $wordCount = $result->text !== null ? str_word_count($result->text) : 0;

        $extraction = $this->repository->createExtraction([
            'tenant_id' => $source->tenant_id,
            'content_source_id' => $source->id,
            'status' => $result->success ? 'success' : 'failed',
            'extracted_text' => $result->text,
            'word_count' => $wordCount,
            'error_message' => $result->error,
            'meta' => $result->meta,
        ]);

        $this->repository->updateSource($source, [
            'status' => $result->success ? 'processed' : 'failed',
            'processed_at' => now(),
        ]);

        if ($result->success) {
            Event::dispatch(new ContentExtracted($source->id, $source->tenant_id, $extraction->id));
        }

        return $extraction;
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
