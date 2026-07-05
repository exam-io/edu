<?php

namespace Modules\Campaign\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Campaign\Domain\Models\Campaign;

interface CampaignRepositoryInterface
{
    public function paginate(int $tenantId, int $perPage, array $filters = []): LengthAwarePaginator;

    public function findForTenant(int $tenantId, int $id): ?Campaign;

    public function create(array $attributes): Campaign;

    public function update(Campaign $campaign, array $attributes): Campaign;

    public function delete(Campaign $campaign): void;

    /**
     * @param list<int> $userIds
     */
    public function syncRecipients(int $tenantId, int $campaignId, array $userIds): void;

    /**
     * @return list<int>
     */
    public function recipientUserIds(int $tenantId, int $campaignId): array;
}
