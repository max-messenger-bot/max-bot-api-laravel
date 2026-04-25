<?php

declare(strict_types=1);

namespace MaxMessenger\Laravel\Contracts;

use MaxMessenger\Bot\MaxBot;

interface MaxBotConfiguratorInterface
{
    public function configureMaxBot(MaxBot $maxBot): void;
}
