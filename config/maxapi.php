<?php

declare(strict_types=1);

return [
    /**
     * MaxBot
     */
    'access_token' => env('MAXAPI_ACCESS_TOKEN'),
    'bot_secret' => env('MAXAPI_BOT_SECRET'),

    /**
     * MaxApi Client
     */
    'base_url' => env('MAXAPI_BASE_URL'),
    /** The number of milliseconds to wait while trying to connect. Use `0` to wait indefinitely. */
    'connect_timeout' => null,
    /** The maximum number of milliseconds that a request can run. Use `0` to wait indefinitely. */
    'timeout' => null,

    /**
     * Laravel
     */
    'webhook_path' => '/maxapi/webhook',
    'log_channel' => null,
];
