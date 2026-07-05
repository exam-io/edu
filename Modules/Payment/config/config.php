<?php

return [
    'default_provider' => env('PAYMENT_DEFAULT_PROVIDER', 'null'),
    'providers' => [
        'null' => ['enabled' => true],
        'stripe' => ['enabled' => (bool) env('PAYMENT_STRIPE_ENABLED', false)],
        'paypal' => ['enabled' => (bool) env('PAYMENT_PAYPAL_ENABLED', false)],
    ],
];
