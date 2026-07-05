<?php

namespace Modules\CRM\Domain\Events;

readonly class LeadStatusChanged
{
    public function __construct(
        public int $leadId,
        public int $tenantId,
        public string $oldStatus,
        public string $newStatus,
    ) {}
}
