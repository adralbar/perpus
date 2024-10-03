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
            if ($indexKe > 1) { // Skip header row

                // Persiapkan data
                $data['npk'] = !empty($row[1]) ? $row[1] : '';
                $data['shift1'] = !empty($row[3]) ? $row[3] : '';
                $startDate = !empty($row[4]) ? Carbon::createFromFormat('Y-m-d', str_replace("'", "", $row[4])) : null;
                $endDate = !empty($row[5]) ? Carbon::createFromFormat('Y-m-d', str_replace("'", "", $row[5])) : null;

                if ($startDate && $endDate) {

                    while ($startDate->lte($endDate)) {
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
