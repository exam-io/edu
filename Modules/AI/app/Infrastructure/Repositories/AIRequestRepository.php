<?php

namespace Modules\AI\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\AI\Application\Contracts\AIRequestRepositoryInterface;
use Modules\AI\Application\DTOs\AIRequestListQueryData;
use Modules\AI\Domain\Models\AIGenerationOutput;
use Modules\AI\Domain\Models\AIGenerationRequest;

class AIRequestRepository implements AIRequestRepositoryInterface
{
    public function paginate(int $tenantId, AIRequestListQueryData $query, array $with = []): LengthAwarePaginator
    {
        $builder = AIGenerationRequest::query()->where('tenant_id', $tenantId)->with($with);

        if ($query->search !== null && $query->search !== '') {
            $search = $query->search;
            $builder->where(function ($q) use ($search): void {
                $q->orWhere('prompt_text', 'like', "%{$search}%")
                    ->orWhere('generation_type', 'like', "%{$search}%");
            });
        }

        if ($query->status !== null && $query->status !== '') {
            $builder->where('status', $query->status);
        }

        if ($query->generationType !== null && $query->generationType !== '') {
            $builder->where('generation_type', $query->generationType);
        }

        return $builder->latest('id')->paginate($query->perPage);
    }

    public function findForTenant(int $tenantId, int $id, array $with = []): ?AIGenerationRequest
    {
        return AIGenerationRequest::query()
            ->where('tenant_id', $tenantId)
            ->whereKey($id)
            ->with($with)
            ->first();
    }

    public function createRequest(array $attributes): AIGenerationRequest
    {
        return AIGenerationRequest::query()->create($attributes);
    }

    public function updateRequest(AIGenerationRequest $request, array $attributes): AIGenerationRequest
    {
        $request->update($attributes);

        return $request->refresh();
    }

    public function createOutput(array $attributes): AIGenerationOutput
    {
        return AIGenerationOutput::query()->create($attributes);
    }

    public function deleteRequest(AIGenerationRequest $request): void
    {
        $request->delete();
    }
}
