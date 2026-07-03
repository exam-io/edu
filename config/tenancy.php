<?php

return [
    'tenant_model' => Modules\Tenant\Domain\Models\Tenant::class,
    'default_status' => 'active',
    'resolver' => [
        'driver' => env('TENANT_RESOLVER_DRIVER', 'domain'),
    ],
    'isolation' => [
        'cache_prefix' => env('TENANT_CACHE_PREFIX', 'tenant'),
        'queue_prefix' => env('TENANT_QUEUE_PREFIX', 'tenant'),
        'storage_prefix' => env('TENANT_STORAGE_PREFIX', 'tenants'),
    ],
];
