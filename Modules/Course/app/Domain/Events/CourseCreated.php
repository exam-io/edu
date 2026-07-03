<?php

namespace Modules\Course\Domain\Events;

class CourseCreated
{
    public function __construct(
        public readonly int $courseId,
        public readonly int $tenantId,
    ) {}
}
