<?php

declare(strict_types=1);

namespace MaxMessenger\Laravel\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use MaxMessenger\Bot\MaxBot;

/**
 * Artisan command to start monitoring updates via long polling.
 */
#[Signature('maxbot:debug')]
#[Description('Start monitoring updates via long polling')]
final class DebugCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(MaxBot $bot): void
    {
        $apiClient = $bot->apiClient;
    }
}
