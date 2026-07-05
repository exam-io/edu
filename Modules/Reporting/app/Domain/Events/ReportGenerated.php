<?php

namespace Modules\Reporting\Domain\Events;

readonly class ReportGenerated
{
    public function __construct(
        public int $executionId,
        public int $tenantId,
    ) {}
}
