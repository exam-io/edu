<?php

return [
    'retention_days' => (int) env('OPERATIONS_RETENTION_DAYS', 90),
    'default_backup_disk' => env('OPERATIONS_BACKUP_DISK', env('FILESYSTEM_DISK', 'local')),
    'queue_log_retention_days' => (int) env('OPERATIONS_QUEUE_LOG_RETENTION_DAYS', 90),
];
