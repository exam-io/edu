<?php

return [
    'currency' => env('BILLING_DEFAULT_CURRENCY', 'USD'),
    'tax_mode' => env('BILLING_TAX_MODE', 'exclusive'),
    'cache_ttl' => 900,
];
