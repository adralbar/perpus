<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Shift; // Pastikan Anda memiliki model Shift
use Maatwebsite\Excel\Concerns\ToCollection;

class ShiftsImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $indexKe = 1;
        foreach ($collection as $row) {
            if ($indexKe > 2) { // Skip header row

                // Persiapkan data
                $data['npk'] = !empty($row[2]) ? $row[2] : '';
                $data['nama'] = !empty($row[3]) ? $row[3] : '';
                $data['divisi'] = !empty($row[4]) ? $row[4] : '';
                $data['departement'] = !empty($row[5]) ? $row[5] : '';
                $data['section'] = !empty($row[6]) ? $row[6] : '';
                $data['shift1'] = !empty($row[7]) ? $row[7] : '';
                $data['status'] = !empty($row[8]) ? $row[8] : '';

                // Simpan data ke database
                Shift::create($data);
            }
            $indexKe++;
        }
    }
}
