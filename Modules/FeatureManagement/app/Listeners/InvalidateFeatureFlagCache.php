<?php

namespace Modules\FeatureManagement\Listeners;

use Modules\FeatureManagement\Application\Contracts\FeatureFlagServiceInterface;
use Modules\FeatureManagement\Domain\Events\FeatureFlagsChanged;

class InvalidateFeatureFlagCache
{
    public function __construct(
        private readonly FeatureFlagServiceInterface $featureFlagService,
    ) {}

    public function handle(FeatureFlagsChanged $event): void
    {
        $this->featureFlagService->invalidateCache($event->tenantId);
    }
}
