<?php

namespace Modules\LiveClass\Domain\Events;

readonly class LiveClassEnded
{
    public function __construct(
        public int $liveClassId,
        public int $tenantId,
    ) {}
}
