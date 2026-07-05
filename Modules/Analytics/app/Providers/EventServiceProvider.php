<?php

namespace Modules\Analytics\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Analytics\Domain\Events\AnalyticsEventTracked;
use Modules\Analytics\Listeners\QueueMetricAggregation;
use Modules\Analytics\Listeners\TrackAssessmentSubmittedEvent;
use Modules\Analytics\Listeners\TrackAssignmentSubmittedEvent;
use Modules\Assessment\Domain\Events\AssessmentSubmitted;
use Modules\Assignment\Domain\Events\AssignmentSubmitted;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        AnalyticsEventTracked::class => [
            QueueMetricAggregation::class,
        ],
        AssessmentSubmitted::class => [
            TrackAssessmentSubmittedEvent::class,
        ],
        AssignmentSubmitted::class => [
            TrackAssignmentSubmittedEvent::class,
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
