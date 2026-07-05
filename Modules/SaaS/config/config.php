<?php

return [
    'default_period_format' => 'Y-m',
    'snapshot_retention_days' => (int) env('SAAS_SNAPSHOT_RETENTION_DAYS', 365),
    'quota' => [
        'api_calls' => (int) env('SAAS_QUOTA_API_CALLS', 10000),
        'storage_mb' => (int) env('SAAS_QUOTA_STORAGE_MB', 10240),
    ],
];
