<?php

namespace Modules\Assessment\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Assessment\Domain\Events\AssessmentCreated;
use Modules\Assessment\Domain\Events\AssessmentEvaluated;
use Modules\Assessment\Domain\Events\AssessmentPublished;
use Modules\Assessment\Domain\Events\AssessmentStarted;
use Modules\Assessment\Domain\Events\AssessmentSubmitted;
use Modules\Assessment\Domain\Events\ResultGenerated;
use Modules\Assessment\Listeners\GenerateResultAnalytics;
use Modules\Assessment\Listeners\LogAssessmentActivity;
use Modules\Assessment\Listeners\SendAssessmentNotifications;
use Modules\Assessment\Listeners\UpdateAssessmentStatistics;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        AssessmentCreated::class => [
            UpdateAssessmentStatistics::class,
            LogAssessmentActivity::class,
        ],
        AssessmentPublished::class => [
            SendAssessmentNotifications::class,
            LogAssessmentActivity::class,
        ],
        AssessmentStarted::class => [
            UpdateAssessmentStatistics::class,
            LogAssessmentActivity::class,
        ],
        AssessmentSubmitted::class => [
            UpdateAssessmentStatistics::class,
            LogAssessmentActivity::class,
        ],
        AssessmentEvaluated::class => [
            GenerateResultAnalytics::class,
            LogAssessmentActivity::class,
        ],
        ResultGenerated::class => [
            GenerateResultAnalytics::class,
            LogAssessmentActivity::class,
        ],
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}
}
