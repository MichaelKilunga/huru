<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // Google Gemini (model is chosen in the admin settings)
    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
        'url' => env('GEMINI_URL', 'https://generativelanguage.googleapis.com/v1beta/models'),
        'timeout' => env('GEMINI_TIMEOUT', 30),
    ],

    // Africa's Talking SMS
    'at' => [
        'username' => env('AT_USERNAME'),
        'api_key' => env('AT_API_KEY'),
        'from' => env('AT_FROM'),
        'webhook_secret' => env('AT_WEBHOOK_SECRET'),
        // When true, inbound messages without the keyword are ignored (shared short codes).
        'require_keyword' => env('AT_REQUIRE_KEYWORD', false),
    ],

];
