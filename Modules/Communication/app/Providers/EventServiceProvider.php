<?php

namespace Modules\Communication\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Campaign\Domain\Events\CampaignLaunchRequested;
use Modules\Communication\Domain\Events\AnnouncementPublished;
use Modules\Communication\Listeners\QueueAnnouncementDispatch;
use Modules\Communication\Listeners\QueueCampaignDispatch;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        CampaignLaunchRequested::class => [
            QueueCampaignDispatch::class,
        ],
        AnnouncementPublished::class => [
            QueueAnnouncementDispatch::class,
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
