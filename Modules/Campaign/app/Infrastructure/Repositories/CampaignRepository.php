<?php

namespace Modules\Campaign\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Campaign\Application\Contracts\CampaignRepositoryInterface;
use Modules\Campaign\Domain\Models\Campaign;

class CampaignRepository implements CampaignRepositoryInterface
{
    public function paginate(int $tenantId, int $perPage, array $filters = []): LengthAwarePaginator
    {
        $builder = Campaign::query()
            ->where('tenant_id', $tenantId)
            ->latest('id');

        if (! empty($filters['status'])) {
            $builder->where('status', (string) $filters['status']);
        }

        if (! empty($filters['campaign_type'])) {
            $builder->where('campaign_type', (string) $filters['campaign_type']);
        }

        if (! empty($filters['search'])) {
            $q = (string) $filters['search'];
            $builder->where(function ($query) use ($q): void {
                $query->where('name', 'like', '%' . $q . '%')
                    ->orWhere('subject', 'like', '%' . $q . '%')
                    ->orWhere('message', 'like', '%' . $q . '%');
            });
        }

        return $builder->paginate($perPage);
    }

    public function findForTenant(int $tenantId, int $id): ?Campaign
    {
        return Campaign::query()->where('tenant_id', $tenantId)->find($id);
    }

    public function create(array $attributes): Campaign
    {
        return Campaign::query()->create($attributes);
    }

    public function update(Campaign $campaign, array $attributes): Campaign
    {
        $campaign->fill($attributes)->save();

        return $campaign->refresh();
    }

    public function delete(Campaign $campaign): void
    {
        $campaign->delete();
    }

    public function syncRecipients(int $tenantId, int $campaignId, array $userIds): void
    {
        DB::table('campaign_recipients')
            ->where('tenant_id', $tenantId)
            ->where('campaign_id', $campaignId)
            ->delete();

        if ($userIds === []) {
            return;
        }

        $rows = array_map(static fn (int $userId): array => [
            'tenant_id' => $tenantId,
            'campaign_id' => $campaignId,
            'user_id' => $userId,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ], array_values(array_unique($userIds)));

        DB::table('campaign_recipients')->insert($rows);
    }

    public function recipientUserIds(int $tenantId, int $campaignId): array
    {
        return DB::table('campaign_recipients')
            ->where('tenant_id', $tenantId)
            ->where('campaign_id', $campaignId)
            ->pluck('user_id')
            ->map(static fn ($id): int => (int) $id)
            ->values()
            ->all();
    }
}
