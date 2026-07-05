<?php

namespace Modules\Reporting\Domain\Events;

readonly class ReportScheduled
{
    public function __construct(
        public int $scheduleId,
        public int $tenantId,
    ) {}
}
