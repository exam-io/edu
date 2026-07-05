<?php

namespace Modules\Insights\Domain\Events;

readonly class InsightGenerated
{
    public function __construct(
        public int $insightId,
        public int $tenantId,
    ) {}
}
