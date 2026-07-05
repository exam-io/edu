<?php

namespace Modules\Audit\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class AuditServiceProvider extends ModuleServiceProvider
{
    protected string $name = 'Audit';

    protected string $nameLower = 'audit';

    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
        AuditBindingsServiceProvider::class,
    ];
}
