<?php

namespace Modules\Institute\Listeners;

use Modules\Institute\Application\Services\InstituteBrandingService;
use Modules\Institute\Application\Services\InstituteConfigurationService;
use Modules\Institute\Domain\Events\BrandingUpdated;
use Modules\Institute\Domain\Events\InstituteBrandingUpdated;

class InvalidateInstituteCaches
{
    public function __construct(
        private readonly InstituteBrandingService $brandingService,
        private readonly InstituteConfigurationService $configurationService,
    ) {}

    public function handle(InstituteBrandingUpdated|BrandingUpdated $event): void
    {
        $this->brandingService->invalidateCache($event->instituteId);
        $this->configurationService->invalidateCache($event->instituteId);
    }
}
