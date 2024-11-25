<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CallApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:check-late-absen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call API to check late and absent records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = 'http://127.0.0.1:8000/api/check-late-and-absen';

        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $this->info('API called successfully. Response: ' . $response->body());
            } else {
                $this->error('Failed to call API. Status: ' . $response->status());
            }
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
