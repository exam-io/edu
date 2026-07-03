<?php

use App\Providers\AppServiceProvider;
use App\Providers\TenancyServiceProvider;
use Modules\Tenant\Providers\TenantBindingsServiceProvider;

return [
    AppServiceProvider::class,
    TenancyServiceProvider::class,
    TenantBindingsServiceProvider::class,
];
