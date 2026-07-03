<?php

namespace Modules\LMS\Listeners;

use Modules\LMS\Domain\Events\CourseEnrollmentCreated;

class InitializeEnrollmentProgress
{
    public function handle(CourseEnrollmentCreated $event): void
    {
        // Reserved for async workflow that initializes richer progress artifacts.
    }
}
