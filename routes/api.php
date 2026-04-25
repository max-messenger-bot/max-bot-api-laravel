<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use MaxMessenger\Laravel\Http\Api\Controllers\MaxBotController;
use MaxMessenger\Laravel\Providers\MaxServiceProvider;

/**
 * @var MaxServiceProvider $this
 */

Route::post($this->webhookPath, [MaxBotController::class, 'webhook']);
