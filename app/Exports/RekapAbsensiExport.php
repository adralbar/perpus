<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekapAbsensiExport implements FromCollection, WithHeadings
{
    protected $filteredData;

    public function __construct($filteredData)
    {
        $this->filteredData = $filteredData;
    }

    public function collection()
    {
        // Hanya ambil kolom yang diperlukan sesuai dengan format DataTable
        return $this->filteredData->map(function ($item) {
            return [
                'npk_sistem' => $item->npk_sistem,
                'npk' => $item->npk,
                'nama' => $item->nama,
                'division_nama' => $item->division_nama,
                'department_nama' => $item->department_nama,
                'section_nama' => $item->section_nama,
                'tanggal' => $item->tanggal,
                'shift1' => $item->shift1,
                'waktuci' => $item->waktuci,
                'waktuco' => $item->waktuco,
                'status' => $item->status,
                'api_time' => $item->api_time,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NPK Sistem',
            'NPK',
            'Nama',
            'Divisi',
            'Departemen',
            'Section',
            'Tanggal',
            'Shift',
            'Waktu Check-in',
            'Waktu Check-out',
            'Status',
            'API Time'
        ];
    }
}
