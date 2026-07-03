<?php

namespace Modules\Institute\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Institute\Domain\Events\AcademicSessionCreated;
use Modules\Institute\Domain\Events\AcademicSessionDeleted;
use Modules\Institute\Domain\Events\AcademicSessionUpdated;
use Modules\Institute\Domain\Events\InstituteBrandingUpdated;
use Modules\Institute\Domain\Events\InstituteProvisioned;
use Modules\Institute\Domain\Events\InstituteProvisioningStarted;
use Modules\Institute\Domain\Events\InstituteRegistered;
use Modules\Institute\Listeners\InvalidateInstituteCaches;
use Modules\Institute\Listeners\LogInstituteLifecycleEvent;
use Modules\Institute\Listeners\StartInstituteProvisioning;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        InstituteRegistered::class => [
            StartInstituteProvisioning::class,
            LogInstituteLifecycleEvent::class,
        ],
        InstituteProvisioningStarted::class => [
            LogInstituteLifecycleEvent::class,
        ],
        InstituteProvisioned::class => [
            LogInstituteLifecycleEvent::class,
        ],
        InstituteBrandingUpdated::class => [
            InvalidateInstituteCaches::class,
            LogInstituteLifecycleEvent::class,
        ],
        AcademicSessionCreated::class => [
            LogInstituteLifecycleEvent::class,
        ],
        AcademicSessionUpdated::class => [
            LogInstituteLifecycleEvent::class,
        ],
        AcademicSessionDeleted::class => [
            LogInstituteLifecycleEvent::class,
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
