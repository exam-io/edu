<?php

namespace Modules\Student\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class StudentServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Student';

    protected string $nameLower = 'student';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
        StudentBindingsServiceProvider::class,
    ];
}
