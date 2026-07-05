<?php

namespace Modules\MobileProvisioning\Listeners;

use Modules\MobileProvisioning\Application\Contracts\MobileConfigServiceInterface;
use Modules\MobileProvisioning\Domain\Events\MobileConfigPublished;

class InvalidateMobileConfigCache
{
    public function __construct(
        private readonly MobileConfigServiceInterface $service,
    ) {}

    public function handle(MobileConfigPublished $event): void
    {
        $this->service->invalidateCache($event->tenantId);
    }
}
