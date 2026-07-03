<?php

namespace Modules\Parent\Domain\Events;

class ParentCreated
{
    public function __construct(
        public readonly int $parentId,
        public readonly int $tenantId,
        public readonly bool $provisionLoginAccount = false,
    ) {
    }
}
