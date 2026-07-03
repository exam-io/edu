<?php

return [
    'tenant_model' => Modules\Tenant\Domain\Models\Tenant::class,
    'default_status' => 'active',
    'base_domain' => env('TENANT_BASE_DOMAIN', null),
    'resolver' => [
        'driver' => env('TENANT_RESOLVER_DRIVER', 'multi-strategy'),
    ],
    'isolation' => [
        'cache_prefix' => env('TENANT_CACHE_PREFIX', 'tenant'),
        'queue_prefix' => env('TENANT_QUEUE_PREFIX', 'tenant'),
        'storage_prefix' => env('TENANT_STORAGE_PREFIX', 'tenants'),
    ],
    'plans' => [
        'free' => [
            'max_users' => 5,
            'max_storage' => 1, // GB
            'features' => ['basic_dashboard'],
        ],
        'pro' => [
            'max_users' => 50,
            'max_storage' => 10, // GB
            'features' => ['advanced_dashboard', 'api_access'],
        ],
        'enterprise' => [
            'max_users' => 999,
            'max_storage' => 100, // GB
            'features' => ['advanced_dashboard', 'api_access', 'custom_domain', 'sso'],
        ],
    ],
];
