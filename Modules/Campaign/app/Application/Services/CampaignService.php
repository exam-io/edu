<?php

namespace Modules\Campaign\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Event;
use Modules\Campaign\Application\Contracts\CampaignRepositoryInterface;
use Modules\Campaign\Application\Contracts\CampaignServiceInterface;
use Modules\Campaign\Application\DTOs\CampaignMutationData;
use Modules\Campaign\Application\DTOs\CampaignQueryData;
use Modules\Campaign\Domain\Events\CampaignLaunchRequested;
use Modules\Campaign\Domain\Models\Campaign;
use Modules\Tenant\Application\Services\TenantContextService;

class CampaignService implements CampaignServiceInterface
{
    public function __construct(
        private readonly CampaignRepositoryInterface $repository,
        private readonly TenantContextService $tenantContext,
    ) {}

    public function list(CampaignQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginate($this->tenantId(), $query->perPage, [
            'status' => $query->status,
            'campaign_type' => $query->campaignType,
            'search' => $query->search,
        ]);
    }

    public function find(int $id): Campaign
    {
        $campaign = $this->repository->findForTenant($this->tenantId(), $id);
        abort_if($campaign === null, 404, 'Campaign not found.');

        return $campaign;
    }

    public function create(CampaignMutationData $data): Campaign
    {
        $tenantId = $this->tenantId();
        $campaign = $this->repository->create(array_merge($data->toArray(), [
            'tenant_id' => $tenantId,
        ]));

        $this->repository->syncRecipients($tenantId, $campaign->id, $data->recipientUserIds);

        return $campaign;
    }

    public function update(int $id, CampaignMutationData $data): Campaign
    {
        $campaign = $this->find($id);
        $tenantId = $this->tenantId();
        $updated = $this->repository->update($campaign, $data->toArray());
        $this->repository->syncRecipients($tenantId, $campaign->id, $data->recipientUserIds);

        return $updated;
    }

    public function launch(int $id): Campaign
    {
        $campaign = $this->find($id);
        $tenantId = $this->tenantId();

        $updated = $this->repository->update($campaign, [
            'status' => 'launched',
            'launched_at' => now(),
        ]);

        Event::dispatch(new CampaignLaunchRequested($updated->id, $tenantId));

        return $updated;
    }

    public function delete(int $id): void
    {
        $this->repository->delete($this->find($id));
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
