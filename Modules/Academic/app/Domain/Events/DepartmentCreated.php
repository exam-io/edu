<?php

namespace Modules\Academic\Domain\Events;

class DepartmentCreated
{
    public function __construct(
        public readonly int $departmentId,
        public readonly int $tenantId,
    ) {}
}
