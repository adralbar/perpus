<?php



namespace App\Jobs;

use App\Models\Absensici;
use App\Models\Absensico;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use DateTime;

class UploadFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    protected $offset;
    protected $batchSize;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($filePath, $offset = 0, $batchSize = 100)
    {
        $this->filePath = $filePath;
        $this->offset = $offset;
        $this->batchSize = $batchSize;  // Misalnya proses 100 baris per job
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Buka file berdasarkan path
        $fileContent = file(storage_path('app/' . $this->filePath));

        // Ambil bagian dari file sesuai batch
        $batch = array_slice($fileContent, $this->offset, $this->batchSize);

        // Proses isi file batch
        foreach ($batch as $line) {
            $data = str_getcsv($line, "\t");

            if (count($data) >= 5) {
                $npk = $data[1];
                $tanggal = $data[2];
                $status = $data[3];
                $time = $data[4];

                // Mengonversi format tanggal
                $date = DateTime::createFromFormat('d.m.Y', $tanggal);
                if ($date) {
                    $formattedDate = $date->format('Y-m-d');
                } else {
                    Log::error('Invalid date format', ['date' => $tanggal]);
                    continue;
                }

                // Simpan atau update ke dalam tabel yang sesuai
                if ($status == 'P10') {
                    Absensici::updateOrCreate(
                        ['npk' => $npk, 'tanggal' => $formattedDate],
                        ['waktuci' => $time]
                    );
                } elseif ($status == 'P20') {
                    Absensico::updateOrCreate(
                        ['npk' => $npk, 'tanggal' => $formattedDate],
                        ['waktuco' => $time]
                    );
                }
            } else {
                Log::warning('Insufficient data in line', ['line' => $line]);
            }
        }

        // Cek apakah ada baris lain yang harus diproses
        if ($this->offset + $this->batchSize < count($fileContent)) {
            // Dispatch job baru untuk batch berikutnya
            UploadFileJob::dispatch($this->filePath, $this->offset + $this->batchSize, $this->batchSize);
        }
    }
}
