<?php

namespace Modules\AI\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\AI\Application\DTOs\AIRequestListQueryData;
use Modules\AI\Domain\Models\AIGenerationOutput;
use Modules\AI\Domain\Models\AIGenerationRequest;

interface AIRequestRepositoryInterface
{
    public function paginate(int $tenantId, AIRequestListQueryData $query, array $with = []): LengthAwarePaginator;

    public function findForTenant(int $tenantId, int $id, array $with = []): ?AIGenerationRequest;

    public function createRequest(array $attributes): AIGenerationRequest;

    public function updateRequest(AIGenerationRequest $request, array $attributes): AIGenerationRequest;

    public function createOutput(array $attributes): AIGenerationOutput;

    public function deleteRequest(AIGenerationRequest $request): void;
}
