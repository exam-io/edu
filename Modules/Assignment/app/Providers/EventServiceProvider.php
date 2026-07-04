<?php

namespace Modules\Assignment\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Assessment\Listeners\LogAssessmentActivity;
use Modules\Assessment\Listeners\SendAssessmentNotifications;
use Modules\Assignment\Domain\Events\AssignmentSubmitted;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        AssignmentSubmitted::class => [
            LogAssessmentActivity::class,
            SendAssessmentNotifications::class,
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
