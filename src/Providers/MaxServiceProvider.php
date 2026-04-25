<?php

declare(strict_types=1);

namespace MaxMessenger\Laravel\Providers;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use MaxMessenger\Bot\Contracts\MaxApiConfigInterface;
use MaxMessenger\Bot\MaxApiClient;
use MaxMessenger\Bot\MaxApiConfig;
use MaxMessenger\Bot\MaxBot;
use MaxMessenger\Laravel\Console\Commands\DebugCommand;
use MaxMessenger\Laravel\Console\Commands\PollingCommand;
use MaxMessenger\Laravel\Console\Commands\SubscribeCommand;
use MaxMessenger\Laravel\Console\Commands\SubscribesCommand;
use MaxMessenger\Laravel\Console\Commands\UnsubscribeCommand;
use Mj4444\SimpleHttpClient\Exceptions\HttpClientException;

final class MaxServiceProvider extends ServiceProvider
{
    /**
     * All of the container singletons that should be registered.
     */
    public array $singletons = [
        MaxApiConfigInterface::class => MaxApiConfig::class,
    ];
    private ?string $webhookPath = null;

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        /** @var ConfigRepository $config */
        $config = $this->app->make('config');

        $this->webhookPath = $config->get('maxapi.webhook_path');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([
                __DIR__ . '/../../config/maxapi.php' => $this->app->configPath('maxapi.php'),
            ], ['maxapi', 'maxapi-config']);

            $this->commands([
                DebugCommand::class,
                PollingCommand::class,
                SubscribeCommand::class,
                SubscribesCommand::class,
                UnsubscribeCommand::class,
            ]);
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/maxapi.php', 'maxapi');

        $this->app->singleton(MaxApiConfig::class, static function (Application $app): MaxApiConfig {
            /** @var ConfigRepository $config */
            $config = $app->make('config');

            $maxApiConfig = new MaxApiConfig($config->get('maxapi.access_token'));

            $baseUrl = $config->get('maxapi.base_url');
            if ($baseUrl !== null) {
                $maxApiConfig->setBaseUrl($baseUrl);
            }

            $connectTimeout = $config->get('maxapi.connect_timeout');
            if ($connectTimeout !== null) {
                $maxApiConfig->setConnectTimeout($connectTimeout);
            }

            $timeout = $config->get('maxapi.timeout');
            if ($timeout !== null) {
                $maxApiConfig->setTimeout($timeout);
            }

            return $maxApiConfig;
        });

        $this->app->singleton(MaxApiClient::class, static function (Application $app): MaxApiClient {
            $maxApiConfig = $app->make(MaxApiConfigInterface::class);

            $exceptionLogger = static function (string $method, HttpClientException $exception): void {
                Log::warning($exception->getMessage(), ['method' => $method, 'exception' => $exception]);
            };

            return new MaxApiClient($maxApiConfig, $exceptionLogger);
        });

        $this->app->singleton(MaxBot::class, static function (Application $app): MaxBot {
            $maxApiClient = $app->make(MaxApiClient::class);
            /** @var ConfigRepository $config */
            $config = $app->make('config');

            return new MaxBot($maxApiClient, $config->get('maxapi.bot_secret'));
        });
    }
}
