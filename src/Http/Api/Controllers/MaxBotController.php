<?php

declare(strict_types=1);

namespace MaxMessenger\Laravel\Http\Api\Controllers;

use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Http\Request;
use Illuminate\Log\LogManager;
use MaxMessenger\Bot\MaxBot;
use MaxMessenger\Laravel\Contracts\MaxBotConfiguratorInterface;
use MaxMessenger\Laravel\Services\UpdateRequestService;

final class MaxBotController
{
    private UpdateRequestService $updateRequestService;

    public function __construct(
        ConfigRepository $config,
        LogManager $logManager,
        private MaxBot $maxBot,
        MaxBotConfiguratorInterface $configurator
    ) {
        $channel = $config->get('maxapi.log_channel');

        $logger = $channel !== null
            ? $logManager->channel($channel)
            : $logManager->getLogger();
        $secret = $config->get('maxapi.bot_secret');

        $this->updateRequestService = new UpdateRequestService($logger);
        if ($secret) {
            $this->updateRequestService->setSecret($secret);
        }

        $configurator->configureMaxBot($this->maxBot);
    }

    public function webhook(Request $request): string
    {
        $body = $this->updateRequestService->readContentFromRequest($request);
        $update = $this->updateRequestService->makeUpdateFromString($body);

        $this->maxBot->handleUpdate($update);

        return 'Successfully';
    }
}
