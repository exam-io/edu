<?php

namespace Modules\LiveClass\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\LiveClass\Domain\Events\LiveClassEnded;
use Modules\LiveClass\Domain\Events\LiveClassScheduled;
use Modules\LiveClass\Domain\Events\LiveClassStarted;
use Modules\Notification\Listeners\QueueLiveClassNotifications;
use Modules\Calendar\Listeners\SyncLiveClassToCalendar;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        LiveClassScheduled::class => [
            SyncLiveClassToCalendar::class,
            QueueLiveClassNotifications::class,
        ],
        LiveClassStarted::class => [
            QueueLiveClassNotifications::class,
        ],
        LiveClassEnded::class => [
            QueueLiveClassNotifications::class,
        ],
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = false;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}
}
