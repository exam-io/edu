<?php

namespace Modules\LiveClass\Domain\Events;

readonly class LiveClassScheduled
{
    public function __construct(
        public int $liveClassId,
        public int $tenantId,
    ) {}
}
