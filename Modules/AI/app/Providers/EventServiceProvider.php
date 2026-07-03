<?php

namespace Modules\AI\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\AI\Domain\Events\AIGenerationCompleted;
use Modules\AI\Domain\Events\AIGenerationRequested;
use Modules\AI\Listeners\LogAIActivity;
use Modules\AI\Listeners\QueueAIGeneration;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        AIGenerationRequested::class => [
            QueueAIGeneration::class,
        ],
        AIGenerationCompleted::class => [
            LogAIActivity::class,
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
