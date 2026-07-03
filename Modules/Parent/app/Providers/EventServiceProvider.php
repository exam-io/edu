<?php

namespace Modules\Parent\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Parent\Domain\Events\ParentCreated;
use Modules\Parent\Listeners\CreateParentUserAccount;
use Modules\Parent\Listeners\LogUserManagementActivity;
use Modules\Parent\Listeners\SendWelcomeNotification;
use Modules\Parent\Listeners\UpdateAcademicCache;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ParentCreated::class => [
            CreateParentUserAccount::class,
            SendWelcomeNotification::class,
            LogUserManagementActivity::class,
            UpdateAcademicCache::class,
        ],
    ];

    protected static $shouldDiscoverEvents = false;
}
