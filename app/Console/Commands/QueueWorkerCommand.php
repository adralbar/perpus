<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class QueueWorkerCommand extends Command
{
    protected $signature = 'queue:work-custom';
    protected $description = 'Run the queue worker';

    public function handle()
    {
        // Jalankan queue worker
        Artisan::call('queue:work', [
            '--tries' => 3, // jumlah maksimal percobaan
            '--timeout' => 60, // waktu timeout dalam detik
        ]);

        $this->info('Queue worker is running...');
    }
}
