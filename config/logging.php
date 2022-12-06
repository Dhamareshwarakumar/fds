<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['access_log', 'error_log'],
            'ignore_exceptions' => false,
        ],

        'access_log' => [
            'driver' => 'single',
            'path' => storage_path('../logs/zomato_backend_laravel_access.log'),
            'level' => env('info'),
        ],

        'error_log' => [
            'driver' => 'single',
            'path' => storage_path('../logs/zomato_backend_laravel_error.log'),
            'level' => env('error'),
        ],
    ]
];