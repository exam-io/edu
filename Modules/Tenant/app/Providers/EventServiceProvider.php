<?php

namespace Modules\Tenant\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Tenant\Domain\Events\TenantActivated;
use Modules\Tenant\Domain\Events\TenantCreated;
use Modules\Tenant\Domain\Events\TenantSettingsUpdated;
use Modules\Tenant\Domain\Events\TenantSuspended;
use Modules\Tenant\Listeners\CreateTenantDefaults;
use Modules\Tenant\Listeners\CreateTenantRoles;
use Modules\Tenant\Listeners\InitializeTenantSettings;
use Modules\Tenant\Listeners\InvalidateTenantCaches;
use Modules\Tenant\Listeners\LogTenantResolved;
use Modules\Tenant\Listeners\LogTenantStatusChange;
use Modules\Tenant\Listeners\PrepareTenantStorage;
use Modules\Tenant\Domain\Events\TenantResolved;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        TenantResolved::class => [
            LogTenantResolved::class,
        ],
        TenantCreated::class => [
            CreateTenantDefaults::class,
            InitializeTenantSettings::class,
            CreateTenantRoles::class,
            PrepareTenantStorage::class,
        ],
        TenantActivated::class => [
            LogTenantStatusChange::class,
        ],
        TenantSuspended::class => [
            LogTenantStatusChange::class,
        ],
        TenantSettingsUpdated::class => [
            InvalidateTenantCaches::class,
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
