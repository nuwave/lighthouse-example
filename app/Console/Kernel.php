<?php

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

final class Kernel extends ConsoleKernel
{
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
