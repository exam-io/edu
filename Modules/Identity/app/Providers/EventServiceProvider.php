<?php

namespace Modules\Identity\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Identity\Events\EmailVerified;
use Modules\Identity\Events\PasswordResetRequested;
use Modules\Identity\Events\UserLoggedIn;
use Modules\Identity\Events\UserLoggedOut;
use Modules\Identity\Events\UserRegistered;
use Modules\Identity\Listeners\CreateDefaultSettings;
use Modules\Identity\Listeners\LogAuthenticationActivity;
use Modules\Identity\Listeners\SendWelcomeEmail;
use Modules\Identity\Listeners\UpdateLastLogin;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        UserRegistered::class => [
            CreateDefaultSettings::class,
            SendWelcomeEmail::class,
            LogAuthenticationActivity::class,
        ],
        UserLoggedIn::class => [
            UpdateLastLogin::class,
            LogAuthenticationActivity::class,
        ],
        UserLoggedOut::class => [
            LogAuthenticationActivity::class,
        ],
        PasswordResetRequested::class => [
            LogAuthenticationActivity::class,
        ],
        EmailVerified::class => [
            LogAuthenticationActivity::class,
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
    protected function configureEmailVerification(): void
    {
        // Custom verification flow is handled by module events.
    }
}
