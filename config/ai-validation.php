<?php

return [
    'chatgpt-api_endpoint' => env('CHATGPT_API_ENDPOINT', 'https://api.openai.com/v1/chat/completions'),
    'chatgpt-api_key' => env('CHATGPT_API_KEY', ''),

    'validation_types' => [
        'spam',
        'promotional',
        'swearing',
        'hate_speech',
        'cultural_sensitivity',
        'secure',
    ]

];
