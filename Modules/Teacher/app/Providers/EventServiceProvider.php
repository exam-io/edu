<?php

namespace Modules\Teacher\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Teacher\Domain\Events\TeacherCreated;
use Modules\Teacher\Listeners\CreateTeacherUserAccount;
use Modules\Teacher\Listeners\LogUserManagementActivity;
use Modules\Teacher\Listeners\SendWelcomeNotification;
use Modules\Teacher\Listeners\UpdateAcademicCache;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TeacherCreated::class => [
            CreateTeacherUserAccount::class,
            SendWelcomeNotification::class,
            LogUserManagementActivity::class,
            UpdateAcademicCache::class,
        ],
    ];

    protected static $shouldDiscoverEvents = false;
}
