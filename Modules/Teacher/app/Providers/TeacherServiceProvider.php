<?php

namespace Modules\Teacher\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class TeacherServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Teacher';

    protected string $nameLower = 'teacher';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
        TeacherBindingsServiceProvider::class,
    ];
}
