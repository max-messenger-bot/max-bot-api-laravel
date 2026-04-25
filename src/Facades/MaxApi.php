<?php

declare(strict_types=1);

namespace MaxMessenger\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use MaxMessenger\Bot\MaxApiClient;

final class MaxApi extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return MaxApiClient::class;
    }
}
