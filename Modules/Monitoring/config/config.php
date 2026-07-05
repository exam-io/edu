<?php

return [
    'snapshot_ttl' => (int) env('MONITORING_SNAPSHOT_TTL', 300),
    'default_period_format' => env('MONITORING_DEFAULT_PERIOD', 'Y-m-d-H'),
    'alert_incident_retention_days' => (int) env('MONITORING_INCIDENT_RETENTION_DAYS', 90),
];
