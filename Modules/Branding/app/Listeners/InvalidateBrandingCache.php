<?php

namespace Modules\Branding\Listeners;

use Modules\Branding\Application\Contracts\BrandingServiceInterface;
use Modules\Branding\Domain\Events\BrandingUpdated;

class InvalidateBrandingCache
{
    public function __construct(
        private readonly BrandingServiceInterface $brandingService,
    ) {}

    public function handle(BrandingUpdated $event): void
    {
        $this->brandingService->invalidateCache($event->tenantId);
    }
}
