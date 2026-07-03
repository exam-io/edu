<?php

namespace Modules\Enrollment\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Enrollment\Domain\Events\StudentEnrolled;
use Modules\Enrollment\Domain\Events\TeacherAssigned;
use Modules\Enrollment\Listeners\LogUserManagementActivity;
use Modules\Enrollment\Listeners\UpdateAcademicCache;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        StudentEnrolled::class => [
            LogUserManagementActivity::class,
            UpdateAcademicCache::class,
        ],
        TeacherAssigned::class => [
            LogUserManagementActivity::class,
            UpdateAcademicCache::class,
        ],
    ];

    protected static $shouldDiscoverEvents = false;
}
