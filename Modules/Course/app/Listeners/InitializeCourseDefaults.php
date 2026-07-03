<?php

namespace Modules\Course\Listeners;

use Modules\Course\Domain\Events\CourseCreated;

class InitializeCourseDefaults
{
    public function handle(CourseCreated $event): void
    {
        // Reserved for default content bootstrap hooks.
    }
}
