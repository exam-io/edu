<?php

namespace Modules\Institute\Listeners;

use Modules\Institute\Application\Services\InstituteProvisioningService;
use Modules\Institute\Domain\Events\InstituteRegistered;

class StartInstituteProvisioning
{
    public function __construct(
        private readonly InstituteProvisioningService $provisioningService,
    ) {}

    public function handle(InstituteRegistered $event): void
    {
        $this->provisioningService->provision($event->instituteId, $event->actorUserId);
    }
}
