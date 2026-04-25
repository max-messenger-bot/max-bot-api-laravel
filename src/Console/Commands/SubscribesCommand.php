<?php

declare(strict_types=1);

namespace MaxMessenger\Laravel\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('maxbot:subscribes')]
#[Description('')]
final class SubscribesCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
    }
}
