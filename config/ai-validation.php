<?php

return [
    'chatgpt_api_endpoint' => env('CHATGPT_API_ENDPOINT', 'https://api.openai.com/v1/chat/completions'),
    'chatgpt_api_key' => env('CHATGPT_API_KEY', ''),

    'validation_types' => [
        'spam',
        'nonsense',
        'botcheck',
        'emoji_overuse',
        'promotional',
        'ads',
        'swearing',
        'hate_speech',
        'political_bias',
        'adult_content',
        'secure',
        'phishing',
        'personal_info',
    ]
];
