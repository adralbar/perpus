<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ShiftExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    // Method untuk menambahkan header kolom
    public function headings(): array
    {
        return [
            'NPK',
            'Nama',
            'Tanggal',
            'Shift',
            'Section',
            'Department',
            'Division'
        ];
    }

    // Atur gaya untuk header
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        $sheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:G1')->getFill()->getStartColor()->setARGB(Color::COLOR_YELLOW);

        $sheet->getStyle('A2:F' . $sheet->getHighestRow())
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);
    }

    // Atur lebar kolom
    public function columnWidths(): array
    {
        return [
            'A' => 15, // NPK
            'B' => 25, // Nama
            'C' => 15, // Tanggal
            'D' => 15, // Shift
            'E' => 40, // Section
            'F' => 40, // Department
            'G' => 40, // Division
        ];
    }
}
