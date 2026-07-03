<?php

namespace Modules\Course\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Course\Domain\Events\CourseArchived;
use Modules\Course\Domain\Events\CourseCreated;
use Modules\Course\Domain\Events\CourseUpdated;
use Modules\Course\Listeners\InitializeCourseDefaults;
use Modules\Course\Listeners\LogCourseActivity;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        CourseCreated::class => [
            LogCourseActivity::class,
            InitializeCourseDefaults::class,
        ],
        CourseUpdated::class => [
            LogCourseActivity::class,
        ],
        CourseArchived::class => [
            LogCourseActivity::class,
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
