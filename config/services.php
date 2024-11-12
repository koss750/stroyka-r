<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],


    'tinkoff' => [
        'terminal_key' => env('TINKOFF_TERMINAL_KEY'),
        'secret_key' => env('TINKOFF_SECRET_KEY'),
        'api_url' => env('TINKOFF_API_URL', 'https://securepayments.tinkoff.ru/v2/'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'openai' => ['api_key' => env('OPEN_AI_TOKEN')],

    'yandex' => [
        'api_key' => env('YANDEX_API_KEY'),
    ],

];
