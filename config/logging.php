<?php

return [

    'default' => 'debug_log',

    'channels' => [
        'debug_log' => [
            'driver' => 'single',
            'path' => storage_path('../logs/zomato_backend_lumen_debug.log'),
            'level' => 'debug',
        ],

        'critical_log' => [
            'driver' => 'stack',
            'channels' => ['error_log', 'slack'],
        ],

        'error_log' => [
            'driver' => 'single',
            'path' => storage_path('../logs/zomato_backend_lumen_error.log'),
            'level' => 'error',
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Zomato Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],
    ],

];