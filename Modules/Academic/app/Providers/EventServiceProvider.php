<?php

namespace Modules\Academic\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Academic\Domain\Events\BatchCreated;
use Modules\Academic\Domain\Events\ClassCreated;
use Modules\Academic\Domain\Events\DepartmentCreated;
use Modules\Academic\Domain\Events\ProgramCreated;
use Modules\Academic\Domain\Events\SectionCreated;
use Modules\Academic\Domain\Events\SubjectCreated;
use Modules\Academic\Listeners\InitializeDefaults;
use Modules\Academic\Listeners\LogAcademicActivity;
use Modules\Academic\Listeners\UpdateAcademicCache;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        DepartmentCreated::class => [
            LogAcademicActivity::class,
            UpdateAcademicCache::class,
            InitializeDefaults::class,
        ],
        ProgramCreated::class => [
            LogAcademicActivity::class,
            UpdateAcademicCache::class,
            InitializeDefaults::class,
        ],
        ClassCreated::class => [
            LogAcademicActivity::class,
            UpdateAcademicCache::class,
            InitializeDefaults::class,
        ],
        SectionCreated::class => [
            LogAcademicActivity::class,
            UpdateAcademicCache::class,
            InitializeDefaults::class,
        ],
        BatchCreated::class => [
            LogAcademicActivity::class,
            UpdateAcademicCache::class,
            InitializeDefaults::class,
        ],
        SubjectCreated::class => [
            LogAcademicActivity::class,
            UpdateAcademicCache::class,
            InitializeDefaults::class,
        ],
    ];

    protected static $shouldDiscoverEvents = false;
}
