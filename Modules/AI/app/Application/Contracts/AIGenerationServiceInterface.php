<?php

namespace Modules\AI\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\AI\Application\DTOs\AIGenerationRequestData;
use Modules\AI\Application\DTOs\AIRequestListQueryData;
use Modules\AI\Domain\Models\AIGenerationRequest;

interface AIGenerationServiceInterface
{
    public function list(AIRequestListQueryData $query): LengthAwarePaginator;

    public function find(int $id): AIGenerationRequest;

    public function create(AIGenerationRequestData $data): AIGenerationRequest;

    public function delete(int $id): void;
}
