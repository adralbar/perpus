<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Shift;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;

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
                $data['shift1'] = !empty($row[2]) ? $row[2] : '';
                $startDate = !empty($row[3]) ? Carbon::createFromFormat('Y-m-d', str_replace("'", "", $row[3])) : null;
                $endDate = !empty($row[4]) ? Carbon::createFromFormat('Y-m-d', str_replace("'", "", $row[4])) : null;

                if ($startDate && $endDate) {
                    while ($startDate->lte($endDate)) {
                        // Cek apakah hari adalah Sabtu (6) atau Minggu (0)
                        if ($startDate->isSaturday() || $startDate->isSunday()) {
                            // Jika hari Sabtu atau Minggu, set shift sebagai 'off'
                            $data['shift1'] = 'OFF';
                        } else {
                            // Jika bukan Sabtu atau Minggu, set shift sesuai data yang diambil
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
