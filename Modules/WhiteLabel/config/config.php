<?php

return [
    'cache_ttl' => 1800,
    'domain_verification' => [
        'enabled' => true,
        'txt_prefix' => 'eduos-verify-',
    ],
    'default_navigation' => [
        ['key' => 'dashboard', 'label' => 'Dashboard', 'path' => '/dashboard/analytics', 'enabled' => true],
        ['key' => 'settings', 'label' => 'Settings', 'path' => '/settings', 'enabled' => true],
    ],
];
