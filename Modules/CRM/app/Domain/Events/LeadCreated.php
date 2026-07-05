<?php

namespace Modules\CRM\Domain\Events;

readonly class LeadCreated
{
    public function __construct(
        public int $leadId,
        public int $tenantId,
    ) {}
}
