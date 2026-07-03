<?php

namespace Modules\Teacher\Domain\Events;

class TeacherCreated
{
    public function __construct(
        public readonly int $teacherId,
        public readonly int $tenantId,
        public readonly bool $provisionLoginAccount = false,
    ) {
    }
}
