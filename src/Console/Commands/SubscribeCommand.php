<?php

declare(strict_types=1);

namespace MaxMessenger\Laravel\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('maxbot:subscribe {url?}')]
#[Description('')]
final class SubscribeCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
    }
}
