<?php

namespace Modules\LiveClass\Domain\Events;

readonly class LiveClassJoined
{
    public function __construct(
        public int $liveClassId,
        public int $studentId,
        public int $tenantId,
    ) {}
}
