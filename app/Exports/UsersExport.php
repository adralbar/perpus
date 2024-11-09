<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithStyles, WithHeadings, WithColumnWidths
{
    public function collection()
    {
        $userData = User::with(['division', 'department', 'section', 'role', 'sistemUser'])->get();

        return $userData->map(function ($user) {
            return [
                'NPK Sistem' => $user->npk_sistem ? $user->npk_sistem : 'Tidak ada',
                'NPK' => $user->npk,
                'Nama' => $user->nama,
                'No Telepon' => $user->no_telp,
                'Divisi' => $user->division ? $user->division->nama : 'Tidak ada',
                'Departemen' => $user->department ? $user->department->nama : 'Tidak ada',
                'Section' => $user->section ? $user->section->nama : 'Tidak ada',
                'Role' => $user->role ? $user->role->nama : 'Tidak ada',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NPK Sistem',
            'NPK',
            'Nama',
            'No Telepon',
            'Divisi',
            'Departemen',
            'Section',
            'Role',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Set format semua kolom menjadi teks
        foreach (range('A', 'H') as $column) {
            $sheet->getStyle($column . '1:' . $column . $sheet->getHighestRow())
                ->getNumberFormat()
                ->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
        }

        // Atur style untuk header
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:H1')->getFill()->getStartColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);

        // Atur alignment untuk isi kolom
        $sheet->getStyle('A2:H' . $sheet->getHighestRow())
            ->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
    }

    // Atur lebar kolom
    public function columnWidths(): array
    {
        return [
            'A' => 15, // NPK
            'B' => 25, // Nama
            'C' => 15, // No Telepon
            'D' => 15, // NPK Sistem
            'E' => 40, // Divisi
            'F' => 40, // Departemen
            'G' => 40, // Section
            'H' => 40, // Role
        ];
    }
}
