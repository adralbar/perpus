<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\QueueWorkerCommand::class,
        Commands\CallApiCommand::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('api:check-late-absen')->dailyAt('09:00'); 
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }


    
}
