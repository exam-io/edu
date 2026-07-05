<?php

namespace Modules\SaaS\Domain\Events;

readonly class UsageSnapshotRequested
{
    public function __construct(
        public int $tenantId,
        public string $snapshotDate,
    ) {}
}
