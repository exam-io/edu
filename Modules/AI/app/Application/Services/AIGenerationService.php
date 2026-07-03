<?php

namespace Modules\AI\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Modules\AI\Application\Contracts\AIGenerationServiceInterface;
use Modules\AI\Application\Contracts\AIRequestRepositoryInterface;
use Modules\AI\Application\DTOs\AIGenerationRequestData;
use Modules\AI\Application\DTOs\AIRequestListQueryData;
use Modules\AI\Domain\Events\AIGenerationRequested;
use Modules\AI\Domain\Models\AIGenerationRequest;
use Modules\ContentProcessing\Domain\Models\ContentSource;
use Modules\Tenant\Application\Services\TenantContextService;

class AIGenerationService implements AIGenerationServiceInterface
{
    public function __construct(
        private readonly AIRequestRepositoryInterface $repository,
        private readonly TenantContextService $tenantContext,
    ) {}

    public function list(AIRequestListQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginate($this->tenantId(), $query, ['outputs']);
    }

    public function find(int $id): AIGenerationRequest
    {
        $request = $this->repository->findForTenant($this->tenantId(), $id, ['outputs', 'requester']);

        if (! $request instanceof AIGenerationRequest) {
            throw (new ModelNotFoundException())->setModel(AIGenerationRequest::class, [$id]);
        }

        return $request;
    }

    public function create(AIGenerationRequestData $data): AIGenerationRequest
    {
        $attributes = $data->attributes;
        $sourceId = isset($attributes['content_source_id']) ? (int) $attributes['content_source_id'] : null;

        if ($sourceId !== null) {
            $exists = ContentSource::query()->where('tenant_id', $this->tenantId())->whereKey($sourceId)->exists();
            if (! $exists) {
                throw ValidationException::withMessages([
                    'content_source_id' => ['Content source must belong to tenant.'],
                ]);
            }
        }

        $request = $this->repository->createRequest([
            'tenant_id' => $this->tenantId(),
            'requested_by' => auth()->id(),
            'content_source_id' => $sourceId,
            'generation_type' => (string) $attributes['generation_type'],
            'status' => 'queued',
            'prompt_text' => $attributes['prompt_text'] ?? null,
            'options' => $attributes['options'] ?? [],
        ]);

        Event::dispatch(new AIGenerationRequested($request->id, $request->tenant_id));

        return $request->refresh()->load('outputs');
    }

    public function delete(int $id): void
    {
        $request = $this->find($id);
        $this->repository->deleteRequest($request);
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
