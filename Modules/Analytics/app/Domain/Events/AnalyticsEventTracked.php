<?php

namespace Modules\Analytics\Domain\Events;

readonly class AnalyticsEventTracked
{
    public function __construct(
        public int $eventId,
        public int $tenantId,
    ) {}
}
