<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TemplateExport implements FromCollection, WithHeadings, WithStyles, WithEvents, WithColumnFormatting
{
    protected $userData;

    public function __construct($userData)
    {
        $this->userData = $userData;
    }

    public function collection()
    {
        return $this->userData;
    }

    public function headings(): array
    {
        return [
            'NPK',
            'Nama',
            'Jadwal Shift',
            'Start Date',
            'End Date',
            '',
            '',
            'Contoh Shift yang terdaftar',
            'Format Tanggal (startdate & enddate)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            'A1:E1' => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFFF00'], // Background kuning untuk kolom A sampai E
                ],
            ],
            'H1' => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFFF00'], // Background kuning untuk kolom H
                ],
            ],
            'I1' => [
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFFF00'],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Mengatur penyelarasan kolom
                $sheet->getStyle('A1:I' . ($this->userData->count() + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // Atur lebar kolom
                $sheet->getColumnDimension('A')->setWidth(15);
                $sheet->getColumnDimension('B')->setWidth(25);
                $sheet->getColumnDimension('C')->setWidth(15);
                $sheet->getColumnDimension('D')->setWidth(15);
                $sheet->getColumnDimension('E')->setWidth(15);
                $sheet->getColumnDimension('F')->setWidth(15);
                $sheet->getColumnDimension('G')->setWidth(15);
                $sheet->getColumnDimension('H')->setWidth(30);
                $sheet->getColumnDimension('I')->setWidth(30);

                // Jadwal shift yang terdaftar sebagai array
                $shiftSchedules = [
                    '06:00 - 15:00',
                    '07:00 - 16:00',
                    '14:00 - 23:00',
                    '13:00 - 22:00',
                    '21:00 - 06:00',
                    '22:00 - 07:00',
                    '23:00 - 08:00',
                    '06:00 - 15:20',
                    '07:00 - 16:30',
                    '15:00 - 00:00',
                    '16:00 - 01:00',
                    '08:00 - 17:20',
                    '09:00 - 18:20',
                    '08:00 - 17:00',
                    'Dinas Luar Stand By'
                ];

                $rowIndex = 2;
                foreach ($shiftSchedules as $schedule) {
                    $sheet->setCellValue('H' . $rowIndex, $schedule);
                    $rowIndex++;
                }

                $noteRow = 2; // Baris setelah jadwal shift terakhir
                $sheet->setCellValue('I' . $noteRow, 'YYYY-MM-DD contoh: ');
                $noteRow = 3; // Baris setelah jadwal shift terakhir
                $sheet->setCellValue('I' . $noteRow, '2024-11-29');


                $noteRow += 14;
                $sheet->setCellValue('H' . $noteRow, 'NB : Pastikan number format text');

                // Mengatur gaya untuk catatan
                $sheet->getStyle('H' . $noteRow)->getFont()->getColor()->setARGB(Color::COLOR_RED);
                $sheet->getStyle('H' . $noteRow)->getFont()->setBold(true);
                $sheet->getStyle('H' . $noteRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            },
        ];
    }




    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT, // NPK
            'B' => NumberFormat::FORMAT_TEXT, // Nama
            'C' => NumberFormat::FORMAT_TEXT, // Shift
            'D' => NumberFormat::FORMAT_TEXT, // Start Date
            'E' => NumberFormat::FORMAT_TEXT, // End Date
            'F' => NumberFormat::FORMAT_TEXT, // Kolom F (default)
            'G' => NumberFormat::FORMAT_TEXT, // Kolom G (default)
            'H' => NumberFormat::FORMAT_TEXT, // Jadwal Shift
            'I' => NumberFormat::FORMAT_TEXT, // Jadwal Shift
        ];
    }
}
