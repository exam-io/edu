<?php

namespace Modules\Campaign\Domain\Events;

readonly class CampaignLaunchRequested
{
    public function __construct(
        public int $campaignId,
        public int $tenantId,
    ) {}
}
