<?php

namespace Modules\Institute\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Institute\Domain\Events\BrandingUpdated;
use Modules\Institute\Domain\Events\AcademicSessionCreated;
use Modules\Institute\Domain\Events\AcademicSessionDeleted;
use Modules\Institute\Domain\Events\AcademicSessionUpdated;
use Modules\Institute\Domain\Events\InstituteActivated;
use Modules\Institute\Domain\Events\InstituteBrandingUpdated;
use Modules\Institute\Domain\Events\InstituteProvisioned;
use Modules\Institute\Domain\Events\InstituteProvisioningStarted;
use Modules\Institute\Domain\Events\InstituteRegistered;
use Modules\Institute\Domain\Events\InstituteSuspended;
use Modules\Institute\Domain\Events\InstituteUpdated;
use Modules\Institute\Listeners\CreateAcademicSession;
use Modules\Institute\Listeners\CreateInstituteDefaults;
use Modules\Institute\Listeners\CreateStorageDirectories;
use Modules\Institute\Listeners\InvalidateInstituteCaches;
use Modules\Institute\Listeners\LogInstituteActivity;
use Modules\Institute\Listeners\SendWelcomeEmail;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        InstituteRegistered::class => [
            CreateInstituteDefaults::class,
            CreateAcademicSession::class,
            CreateStorageDirectories::class,
            SendWelcomeEmail::class,
            LogInstituteActivity::class,
        ],
        InstituteUpdated::class => [
            LogInstituteActivity::class,
        ],
        InstituteActivated::class => [
            LogInstituteActivity::class,
        ],
        InstituteSuspended::class => [
            LogInstituteActivity::class,
        ],
        InstituteProvisioningStarted::class => [
            LogInstituteActivity::class,
        ],
        InstituteProvisioned::class => [
            LogInstituteActivity::class,
        ],
        InstituteBrandingUpdated::class => [
            InvalidateInstituteCaches::class,
            LogInstituteActivity::class,
        ],
        BrandingUpdated::class => [
            InvalidateInstituteCaches::class,
            LogInstituteActivity::class,
        ],
        AcademicSessionCreated::class => [
            LogInstituteActivity::class,
        ],
        AcademicSessionUpdated::class => [
            LogInstituteActivity::class,
        ],
        AcademicSessionDeleted::class => [
            LogInstituteActivity::class,
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
