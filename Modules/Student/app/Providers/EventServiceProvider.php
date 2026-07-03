<?php

namespace Modules\Student\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Student\Domain\Events\ParentLinkedToStudent;
use Modules\Student\Domain\Events\StudentCreated;
use Modules\Student\Domain\Events\StudentUpdated;
use Modules\Student\Listeners\CreateStudentUserAccount;
use Modules\Student\Listeners\LogUserManagementActivity;
use Modules\Student\Listeners\SendWelcomeNotification;
use Modules\Student\Listeners\UpdateAcademicCache;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        StudentCreated::class => [
            CreateStudentUserAccount::class,
            SendWelcomeNotification::class,
            LogUserManagementActivity::class,
            UpdateAcademicCache::class,
        ],
        StudentUpdated::class => [
            LogUserManagementActivity::class,
            UpdateAcademicCache::class,
        ],
        ParentLinkedToStudent::class => [
            LogUserManagementActivity::class,
            UpdateAcademicCache::class,
        ],
    ];

    protected static $shouldDiscoverEvents = false;
}
