<?php

namespace Modules\Campaign\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Campaign\Application\DTOs\CampaignMutationData;
use Modules\Campaign\Application\DTOs\CampaignQueryData;
use Modules\Campaign\Domain\Models\Campaign;

interface CampaignServiceInterface
{
    public function list(CampaignQueryData $query): LengthAwarePaginator;

    public function find(int $id): Campaign;

    public function create(CampaignMutationData $data): Campaign;

    public function update(int $id, CampaignMutationData $data): Campaign;

    public function launch(int $id): Campaign;

    public function delete(int $id): void;
}
