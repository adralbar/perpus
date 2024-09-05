<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UploadController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function upload(Request $request)
    {
        // Validasi file harus berformat .txt
        $request->validate([
            'file' => 'required|mimes:txt|max:2048',
        ]);

        // Proses membaca file
        $file = $request->file('file');
        $fileContents = File::get($file->getRealPath());

        // Memecah konten file menjadi baris
        $lines = explode("\n", $fileContents);

        // Memproses setiap baris dan menyimpan ke database
        foreach ($lines as $line) {
            $data = preg_split('/\s+/', trim($line)); // Memecah baris berdasarkan spasi

            if (count($data) === 5) {
                $tanggal = date('Y-m-d', strtotime($data[2]));
                $status = $data[3];
                $time = $data[4];

                if ($status === 'P10') {
                    // Masukkan ke tabel absensici
                    DB::table('absensici')->insert([
                        'npk' => $data[1], // Asumsi npk di data[1]
                        'tanggal' => $tanggal,
                        'waktuci' => $time,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } elseif ($status === 'P20') {
                    // Masukkan ke tabel absensico
                    DB::table('absensico')->insert([
                        'npk' => $data[1], // Asumsi npk di data[1]
                        'tanggal' => $tanggal,
                        'waktuco' => $time,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        return back()->with('success', 'File uploaded and data inserted successfully!');
    }
}
