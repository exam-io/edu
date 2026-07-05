<?php

namespace Modules\FeatureManagement\Domain\Events;

readonly class FeatureFlagsChanged
{
    public function __construct(public int $tenantId) {}
}
