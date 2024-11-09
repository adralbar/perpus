<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Shift;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class ShiftsImport implements ToCollection
{
    /**
     * @param Collection 
     */
    public function collection(Collection $collection)
    {
        $indexKe = 1;
        foreach ($collection as $row) {
            if ($indexKe > 1) { // Lewati baris header

                $data['npk'] = !empty($row[0]) ? $row[0] : '';
                $data['shift1'] = !empty($row[2]) ? $row[2] : ''; // Mengambil shift awal dari data
                $startDate = !empty($row[3]) ? Carbon::createFromFormat('Y-m-d', str_replace("'", "", $row[3])) : null;
                $endDate = !empty($row[4]) ? Carbon::createFromFormat('Y-m-d', str_replace("'", "", $row[4])) : null;

                // Ambil section_id dari database berdasarkan npk
                $user = User::where('npk', $data['npk'])->first();
                $sectionId = $user ? $user->section_id : null;

                if ($startDate && $endDate && $sectionId !== null) {
                    while ($startDate->lte($endDate)) {
                        if ($startDate->isWeekend()) {
                            if ($startDate->isSaturday() && in_array($sectionId, [22, 40])) {
                                // Untuk section_id 22 dan 40, gunakan shift dari data
                                $data['shift1'] = !empty($row[2]) ? $row[2] : '';
                            } else {
                                // Set 'OFF' untuk hari Sabtu dan Minggu pada section lainnya
                                $data['shift1'] = 'OFF';
                            }
                        } else {
                            // Jika bukan akhir pekan, set shift sesuai data yang diambil
                            $data['shift1'] = !empty($row[2]) ? $row[2] : '';
                        }

                        $data['date'] = $startDate->toDateString();

                        // Simpan data ke tabel Shift
                        Shift::create($data);

                        // Lanjut ke hari berikutnya
                        $startDate->addDay();
                    }
                }
            }
            $indexKe++;
        }
    }
}
