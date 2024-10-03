<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class performaExport implements FromCollection, WithHeadings
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
            ->leftJoin('pcd_master_users', function ($join) {
                $join->on(DB::raw('CONVERT(absensici.npk USING utf8mb4)'), '=', DB::raw('CONVERT(pcd_master_users.npk USING utf8mb4)'));
            })
            ->join('pcd_login_logs', function ($join) {
                $join->on(DB::raw('CONVERT(pcd_master_users.id USING utf8mb4)'), '=', DB::raw('CONVERT(pcd_login_logs.user_id USING utf8mb4)'))
                    ->on('absensici.tanggal', '=', DB::raw('DATE(pcd_login_logs.created_at)'));
            })
            ->join(DB::raw('(SELECT npk, tanggal, MIN(waktuci) AS waktuci FROM absensici GROUP BY npk, tanggal) as first_checkin'), function ($join) {
                $join->on(DB::raw('CONVERT(first_checkin.npk USING utf8mb4)'), '=', DB::raw('CONVERT(absensici.npk USING utf8mb4)'))
                    ->on('first_checkin.tanggal', '=', 'absensici.tanggal');
            })
            ->join('kategorishift', function ($join) {
                $join->on(DB::raw('CONVERT(absensici.npk USING utf8mb4)'), '=', DB::raw('CONVERT(kategorishift.npk USING utf8mb4)'));
            })
            ->select(
                'pcd_master_users.nama',
                'absensici.npk',
                'absensici.tanggal',
                'first_checkin.waktuci AS waktuci_checkin',
                DB::raw('TIME(pcd_login_logs.created_at) AS waktu_login_dashboard'),
                DB::raw('TIMEDIFF(TIME(pcd_login_logs.created_at), TIME(first_checkin.waktuci)) AS selisih_waktu'),
                'kategorishift.npkSistem',
                'kategorishift.divisi',
                'kategorishift.departement',
                'kategorishift.section',
                'kategorishift.nama AS shift_nama'
            )
            ->distinct()
            ->orderBy('absensici.tanggal', 'desc');

        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('absensici.tanggal', [$startDate, $endDate]);
        }


        if (!empty($this->search)) {
            // Terapkan filter search ke query
            $query->where(function ($query) {
                $query->where('kategorishift.nama', 'LIKE', "%{$this->search}%")
                    ->orWhere('kategorishift.npkSistem', 'LIKE', "%{$this->search}%")
                    ->orWhere('kategorishift.divisi', 'LIKE', "%{$this->search}%")
                    ->orWhere('kategorishift.departement', 'LIKE', "%{$this->search}%")
                    ->orWhere('kategorishift.section', 'LIKE', "%{$this->search}%")
                    ->orWhere('absensici.npk', 'LIKE', "%{$this->search}%")
                    ->orWhere('absensici.tanggal', 'LIKE', "%{$this->search}%")
                    ->orWhere('pcd_master_users.nama', 'LIKE', "%{$this->search}%")
                    ->orWhere('first_checkin.waktuci', 'LIKE', "%{$this->search}%")
                    ->orWhere(DB::raw('TIME(pcd_login_logs.created_at)'), 'LIKE', "%{$this->search}%")
                    ->orWhere(DB::raw('TIMEDIFF(TIME(pcd_login_logs.created_at), TIME(first_checkin.waktuci))'), 'LIKE', "%{$this->search}%");
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
            'Waktu Login Dashboard',
            'Selisih waktu',
        ];
    }
}
