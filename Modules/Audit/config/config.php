<?php

return [
    'export_batch_size' => (int) env('AUDIT_EXPORT_BATCH_SIZE', 100),
    'retention_days' => (int) env('AUDIT_RETENTION_DAYS', 365),
    'redact_keys' => ['password', 'token', 'secret', 'api_key', 'authorization'],
];
