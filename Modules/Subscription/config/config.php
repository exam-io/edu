<?php

return [
    'statuses' => ['trialing', 'active', 'past_due', 'canceled', 'expired'],
    'default_interval' => env('SUBSCRIPTION_DEFAULT_INTERVAL', 'monthly'),
    'renewal_grace_days' => (int) env('SUBSCRIPTION_RENEWAL_GRACE_DAYS', 3),
];
