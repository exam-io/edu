<?php

namespace Modules\Course\Domain\Events;

class CourseUpdated
{
    public function __construct(
        public readonly int $courseId,
        public readonly int $tenantId,
    ) {}
}
