<?php

return [
    'strict_transport_security' => (bool) env('SYSTEM_HSTS_ENABLED', true),
    'health_cache_ttl' => (int) env('SYSTEM_HEALTH_CACHE_TTL', 60),
    'security' => [
        'default_session_ttl_minutes' => (int) env('SYSTEM_DEFAULT_SESSION_TTL', 120),
        'default_password_rotation_days' => (int) env('SYSTEM_DEFAULT_PASSWORD_ROTATION_DAYS', 90),
    ],
];
