<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\MasterShift;
use Carbon\Carbon;

class MasterShiftApiController extends Controller
{
    public function getMasterShift()
    {
        try {
            $data = MasterShift::all()->map(function ($shift) {
                // Pastikan kolom waktu tidak kosong atau null
                if (isset($shift->waktu) && !empty($shift->waktu)) {
                    // Menghapus spasi yang tidak diinginkan
                    $waktu = trim($shift->waktu);

                    // Jika waktu mengandung "OFF", set startTime dan endTime ke "OFF"
                    if (stripos($waktu, 'OFF') !== false) {
                        $startTime = 'OFF';
                        $endTime = 'OFF';
                    }
                    // Jika waktu mengandung format dengan hari dalam tanda kurung (misalnya 06:00 - 15:20 (Fri)), hapus bagian hari
                    elseif (preg_match('/\d{2}:\d{2} - \d{2}:\d{2} \([A-Za-z]{3}\)/', $waktu)) {
                        // Hapus bagian hari dalam tanda kurung (misalnya "(Fri)")
                        $waktu = preg_replace('/ \([A-Za-z]{3}\)$/', '', $waktu);
                        $times = explode(' - ', $waktu);

                        try {
                            $startTime = Carbon::createFromFormat('H:i', trim($times[0]))->format('H:i:s');
                            $endTime = Carbon::createFromFormat('H:i', trim($times[1]))->format('H:i:s');
                        } catch (\Exception $e) {
                            // Jika ada kesalahan saat parsing waktu, set null
                            $startTime = $endTime = null;
                        }
                    }
                    // Tangani kasus "Dinas Luar Stand By"
                    elseif (stripos($waktu, 'Dinas Luar Stand By') !== false) {
                        // Jika diperlukan, ganti dengan string atau null sesuai kebutuhan
                        $startTime = 'Dinas Luar Stand By';
                        $endTime = 'Dinas Luar Stand By'; // Atau set null jika diinginkan
                    }
                    // Format waktu yang benar tanpa hari
                    else {
                        // Pisahkan waktu mulai dan selesai
                        $times = explode(' - ', $waktu);
                        try {
                            $startTime = Carbon::createFromFormat('H:i', trim($times[0]))->format('H:i:s');
                            $endTime = Carbon::createFromFormat('H:i', trim($times[1]))->format('H:i:s');
                        } catch (\Exception $e) {
                            $startTime = $endTime = null;
                        }
                    }
                } else {
                    // Jika waktu kosong atau null, set waktu null
                    $startTime = $endTime = null;
                }

                return [
                    'id' => $shift->id,
                    'shift_name' => $shift->shift_name,
                    'startTime' => $startTime,
                    'endTime' => $endTime,
                    'created_at' => $shift->created_at,
                    'updated_at' => $shift->updated_at
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Data retrieved successfully',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
