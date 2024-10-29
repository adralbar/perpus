<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Absensici;
use App\Models\Absensico;
use App\Models\Shift;

class RekapAbsensiExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;
    protected $search;

    public function __construct($startDate = null, $endDate = null, $search = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->search = $search;
    }

    public function collection(): Collection
    {
        // Query check-in data
        $today = date('Y-m-d', strtotime('-1 day'));

        $checkinQuery = Absensici::with(['user', 'shift'])
            ->select('npk', 'tanggal', DB::raw('MIN(waktuci) as waktuci'))
            ->groupBy('npk', 'tanggal');

        if (!empty($this->startDate) && !empty($this->endDate)) {
            $checkinQuery->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        }

        $checkinResults = $checkinQuery->get();

        // Query check-out data
        $checkoutQuery = Absensico::with(['user', 'shift'])
            ->select('npk', 'tanggal', DB::raw('MAX(waktuco) as waktuco'))
            ->groupBy('npk', 'tanggal');

        if (!empty($this->startDate) && !empty($this->endDate)) {
            $checkoutQuery->whereBetween('tanggal', [$this->startDate, $this->endDate]);
        }

        $checkoutResults = $checkoutQuery->get();

        // Combine check-in and check-out data
        $results = [];

        foreach ($checkinResults as $checkin) {
            $key = "{$checkin->npk}-{$checkin->tanggal}";
            $section = $checkin->user->section;
            $department = $section ? $section->department : null;
            $division = $department ? $department->division : null;

            // Get latest shift
            $latestShift = $checkin->shift()->latest()->first();
            $shift1 = $latestShift ? $latestShift->shift1 : null;
            $shiftIn = $shift1 ? explode(' - ', str_replace('.', ':', $shift1))[0] : null;
            $shiftInFormatted = $shiftIn ? date('H:i:s', strtotime($shiftIn)) : null;

            $status = $checkin->waktuci > $shiftInFormatted ? 'Terlambat' : 'Tepat Waktu';

            $results[$key] = [
                'nama' => $checkin->user->nama,
                'npk' => $checkin->npk,
                'tanggal' => $checkin->tanggal,
                'waktuci' => $checkin->waktuci,
                'waktuco' => null,
                'shift1' => $shift1,
                'section_nama' => $section ? $section->nama : 'Unknown',
                'department_nama' => $department ? $department->nama : 'Unknown',
                'division_nama' => $division ? $division->nama : 'Unknown',
                'status' => $status
            ];
        }

        foreach ($checkoutResults as $checkout) {
            $key = "{$checkout->npk}-{$checkout->tanggal}";
            if (isset($results[$key])) {
                $results[$key]['waktuco'] = $checkout->waktuco;
            } else {
                $previousDay = date('Y-m-d', strtotime("{$checkout->tanggal} -1 day"));
                $previousKey = "{$checkout->npk}-{$previousDay}";

                if (isset($results[$previousKey]) && !$results[$previousKey]['waktuco']) {
                    $results[$previousKey]['waktuco'] = $checkout->waktuco;
                } else {
                    $results[$key] = [
                        'nama' => $checkout->user->nama,
                        'npk' => $checkout->npk,
                        'tanggal' => $checkout->tanggal,
                        'waktuci' => 'NO IN',
                        'waktuco' => $checkout->waktuco,
                        'shift1' => optional($checkout->shift)->shift1,
                        'section_nama' => $checkout->user->section ? $checkout->user->section->nama : 'Unknown',
                        'department_nama' => $checkout->user->section->department ? $checkout->user->section->department->nama : 'Unknown',
                        'division_nama' => $checkout->user->section->department->division ? $checkout->user->section->department->division->nama : 'Unknown',
                        'status' => 'Unknown'
                    ];
                }
            }
        }

        // Handle cases where employees neither checked in nor out
        $noCheckData = Shift::with(['user.section.department.division'])
            ->leftJoin('absensici', function ($join) {
                $join->on('absensici.npk', '=', 'kategorishift.npk')
                    ->on('absensici.tanggal', '=', 'kategorishift.date');
            })
            ->leftJoin('absensico', function ($join) {
                $join->on('absensico.npk', '=', 'kategorishift.npk')
                    ->on('absensico.tanggal', '=', 'kategorishift.date');
            })
            ->select(
                'kategorishift.npk',
                'kategorishift.date as tanggal',
                'kategorishift.shift1',
                DB::raw('IFNULL(DATE_FORMAT(MIN(absensici.waktuci), "%H:%i"), "NO IN") as waktuci'),
                DB::raw('IFNULL(DATE_FORMAT(MAX(absensico.waktuco), "%H:%i"), "NO OUT") as waktuco')
            )
            ->whereNull('absensici.waktuci')
            ->whereNull('absensico.waktuco')
            ->where('kategorishift.date', '<=', $today)
            ->where('kategorishift.shift1', '!=', 'OFF')
            ->groupBy('kategorishift.npk', 'kategorishift.date', 'kategorishift.shift1')
            ->get();

        foreach ($noCheckData as $noCheck) {
            $key = "{$noCheck->npk}-{$noCheck->tanggal}";
            if (!isset($results[$key])) {
                $results[$key] = [
                    'nama' => $noCheck->user ? $noCheck->user->nama : 'Unknown',
                    'npk' => $noCheck->npk,
                    'tanggal' => $noCheck->tanggal,
                    'waktuci' => 'NO IN',
                    'waktuco' => 'NO OUT',
                    'shift1' => $noCheck->shift1,
                    'section_nama' => $noCheck->user->section ? $noCheck->user->section->nama : 'Unknown',
                    'department_nama' => $noCheck->user->section->department ? $noCheck->user->section->department->nama : 'Unknown',
                    'division_nama' => $noCheck->user->section->department->division ? $noCheck->user->section->department->division->nama : 'Unknown',
                    'status' => 'Mangkir'
                ];
            }
        }

        // Convert to collection and return
        return collect($results);
    }

    public function headings(): array
    {
        return [
            'Nama',
            'NPK',
            'Tanggal',
            'Waktu Check-In',
            'Waktu Check-Out',
            'Shift',
            'Section',
            'Departemen',
            'Divisi',
            'Status'
        ];
    }
}
