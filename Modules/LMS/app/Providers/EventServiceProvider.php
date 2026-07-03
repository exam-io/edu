<?php

namespace Modules\LMS\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\LMS\Domain\Events\CourseEnrollmentCreated;
use Modules\LMS\Domain\Events\ProgressUpdated;
use Modules\LMS\Listeners\InitializeEnrollmentProgress;
use Modules\LMS\Listeners\LogLmsActivity;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        CourseEnrollmentCreated::class => [
            LogLmsActivity::class,
            InitializeEnrollmentProgress::class,
        ],
        ProgressUpdated::class => [
            LogLmsActivity::class,
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
