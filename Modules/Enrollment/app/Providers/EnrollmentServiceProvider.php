<?php

namespace Modules\Enrollment\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class EnrollmentServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Enrollment';

    protected string $nameLower = 'enrollment';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
        EnrollmentBindingsServiceProvider::class,
    ];
}
