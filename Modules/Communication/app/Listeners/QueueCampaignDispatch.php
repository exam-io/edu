<?php

namespace Modules\Communication\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Campaign\Application\Contracts\CampaignRepositoryInterface;
use Modules\Campaign\Domain\Events\CampaignLaunchRequested;
use Modules\Communication\Jobs\SendCommunicationJob;

class QueueCampaignDispatch implements ShouldQueue
{
    public function __construct(
        private readonly CampaignRepositoryInterface $campaignRepository,
    ) {}

    public function handle(CampaignLaunchRequested $event): void
    {
        $campaign = $this->campaignRepository->findForTenant($event->tenantId, $event->campaignId);
        if ($campaign === null) {
            return;
        }

        $recipientIds = $this->campaignRepository->recipientUserIds($event->tenantId, $campaign->id);
        foreach ($recipientIds as $userId) {
            SendCommunicationJob::dispatch(
                tenantId: $event->tenantId,
                sourceType: 'campaign',
                sourceId: $campaign->id,
                userId: $userId,
                subject: $campaign->subject,
                content: $campaign->message,
                channels: $campaign->channels ?? ['in_app'],
            );
        }
    }
}
