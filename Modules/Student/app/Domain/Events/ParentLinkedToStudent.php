<?php

namespace Modules\Student\Domain\Events;

class ParentLinkedToStudent
{
    public function __construct(
        public readonly int $studentId,
        public readonly int $parentId,
        public readonly int $tenantId,
    ) {}
}
