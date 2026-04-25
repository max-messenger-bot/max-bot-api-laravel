<?php

declare(strict_types=1);

namespace MaxMessenger\Laravel\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;

/**
 * Artisan command to start processing updates via long polling.
 */
#[Signature('maxbot:poll {--timeout=30 : Long polling timeout in seconds} {--limit=100 : Max updates per request}')]
#[Description('Start processing updates via long polling')]
final class PollingCommand extends Command implements Isolatable
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
    }
}
