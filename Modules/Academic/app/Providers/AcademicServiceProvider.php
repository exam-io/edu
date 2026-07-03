<?php

namespace Modules\Academic\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class AcademicServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Academic';

    protected string $nameLower = 'academic';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
        AcademicBindingsServiceProvider::class,
    ];
}
