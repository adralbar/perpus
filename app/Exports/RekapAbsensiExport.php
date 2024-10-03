<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

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
        $query = DB::table('absensici')
            ->join(DB::raw('(SELECT npk, tanggal, MIN(waktuci) as waktuci FROM absensici GROUP BY npk, tanggal) as first_checkin'), function ($join) {
                $join->on('absensici.npk', '=', 'first_checkin.npk')
                    ->on('absensici.tanggal', '=', 'first_checkin.tanggal')
                    ->on('absensici.waktuci', '=', 'first_checkin.waktuci');
            })
            ->leftJoin(DB::raw('(SELECT npk, tanggal, MAX(waktuco) as waktuco FROM absensico GROUP BY npk, tanggal) as last_checkout_today'), function ($join) {
                $join->on('absensici.npk', '=', 'last_checkout_today.npk')
                    ->on('absensici.tanggal', '=', 'last_checkout_today.tanggal');
            })
            ->leftJoin(DB::raw('(SELECT npk, tanggal, MAX(waktuco) as waktuco FROM absensico GROUP BY npk, tanggal) as last_checkout_tomorrow'), function ($join) {
                $join->on('absensici.npk', '=', 'last_checkout_tomorrow.npk')
                    ->on(DB::raw('DATE_ADD(absensici.tanggal, INTERVAL 1 DAY)'), '=', 'last_checkout_tomorrow.tanggal')
                    ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('absensici as next_checkin')
                            ->whereRaw('next_checkin.npk = absensici.npk')
                            ->whereRaw('next_checkin.tanggal = DATE_ADD(absensici.tanggal, INTERVAL 1 DAY)')
                            ->whereRaw('next_checkin.waktuci < last_checkout_tomorrow.waktuco');
                    });
            })
            ->join('kategorishift', 'absensici.npk', '=', 'kategorishift.npk')
            ->select(
                'kategorishift.nama',
                'kategorishift.npkSistem',
                'absensici.npk',
                'kategorishift.divisi',
                'kategorishift.departement',
                'kategorishift.section',

                'absensici.tanggal',
                'first_checkin.waktuci as waktuci',
                DB::raw('COALESCE(last_checkout_tomorrow.waktuco, last_checkout_today.waktuco) as waktuco')
            )
            ->distinct()
            ->groupBy('absensici.npk', 'absensici.tanggal', 'kategorishift.nama', 'kategorishift.npkSistem', 'kategorishift.divisi', 'kategorishift.departement', 'kategorishift.section', 'first_checkin.waktuci', 'last_checkout_today.waktuco', 'last_checkout_tomorrow.waktuco')
            ->orderBy('absensici.tanggal', 'desc');

        if (!empty($this->search)) {
            // Terapkan filter search ke query
            $query->where(function ($query) {
                $query->where('kategorishift.nama', 'LIKE', "%{$this->search}%")
                    ->orWhere('kategorishift.npkSistem', 'LIKE', "%{$this->search}%")
                    ->orWhere('kategorishift.divisi', 'LIKE', "%{$this->search}%")
                    ->orWhere('kategorishift.departement', 'LIKE', "%{$this->search}%")
                    ->orWhere('kategorishift.section', 'LIKE', "%{$this->search}%")
                    ->orWhere('absensici.npk', 'LIKE', "%{$this->search}%")
                    ->orWhere('absensici.tanggal', 'LIKE', "%{$this->search}%");
            });
        }

        if (!empty($this->startDate) && !empty($this->endDate)) {
            $query->whereBetween('absensici.tanggal', [$this->startDate, $this->endDate]);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nama',
            'NPK Sistem',
            'NPK API',
            'Divisi',
            'Departemen',
            'Section',
            'Tanggal',
            'Waktu Check-In',
            'Waktu Check-Out',
        ];
    }
}
