<?php

namespace Modules\System\Domain\Events;

readonly class SystemHealthCheckFailed
{
    public function __construct(
        public int $tenantId,
        public string $checkName,
        public array $meta = [],
    ) {}
}
