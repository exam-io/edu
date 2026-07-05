<?php

namespace Modules\Reporting\Domain\Events;

readonly class ReportRequested
{
    public function __construct(
        public int $executionId,
        public int $tenantId,
    ) {}
}
