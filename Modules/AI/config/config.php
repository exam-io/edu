<?php

return [
    'name' => 'AI',

    'provider' => env('AI_PROVIDER', 'null'),

    'timeout_seconds' => (int) env('AI_TIMEOUT_SECONDS', 30),

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
    ],
];
