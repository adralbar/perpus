<?php

namespace App\Http\Controllers;

use App\Models\PcdLoginLog;
use App\Models\PcdLoginLogs;
use App\Models\SectionModel;
use Illuminate\Http\Request;
use App\Models\DivisionModel;
use App\Models\PcdMasterUser;
use App\Exports\performaExport;
use App\Models\DepartmentModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class performaController extends Controller
{
    public function index()
    {
        return view('performa.performa');
    }
    public function getData(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $query = DB::table('absensici')
            ->leftJoin('users as user1', 'absensici.npk', '=', DB::raw('user1.npk COLLATE utf8mb4_unicode_ci'))
            ->leftJoin('kategorishift', function ($join) {
                $join->on('absensici.npk', '=', DB::raw('kategorishift.npk COLLATE utf8mb4_unicode_ci'))
                    ->on('absensici.tanggal', '=', 'kategorishift.date');
            })
            // Using the 'autoplastik' database in mysql2 connection
            ->join(DB::raw('autoplastik.pcd_master_users as pcd_users'), function ($join) {
                $join->on(DB::raw('pcd_users.npk COLLATE utf8mb4_unicode_ci'), '=', DB::raw('user1.npk COLLATE utf8mb4_unicode_ci'));
            })
            ->join(DB::raw('(SELECT user_id, DATE(created_at) as tanggal, MIN(created_at) AS first_login_time, MAX(station_id) AS station_id 
                             FROM autoplastik.pcd_login_logs 
                             GROUP BY user_id, DATE(created_at)) as first_login'), function ($join) {
                $join->on(DB::raw('pcd_users.id COLLATE utf8mb4_unicode_ci'), '=', DB::raw('first_login.user_id COLLATE utf8mb4_unicode_ci'))
                    ->on('absensici.tanggal', '=', 'first_login.tanggal');
            })
            ->join(DB::raw('(SELECT npk, tanggal, MIN(waktuci) AS waktuci 
                             FROM absensici 
                             GROUP BY npk, tanggal) as first_checkin'), function ($join) {
                $join->on(DB::raw('first_checkin.npk COLLATE utf8mb4_unicode_ci'), '=', DB::raw('absensici.npk COLLATE utf8mb4_unicode_ci'))
                    ->on('first_checkin.tanggal', '=', 'absensici.tanggal');
            })
            ->join('users as user2', 'absensici.npk', '=', 'user2.npk')
            ->select(
                'user2.nama',
                'absensici.npk',
                'absensici.tanggal',
                'first_checkin.waktuci AS waktuci_checkin',
                'kategorishift.shift1',
                DB::raw('TIME(first_login.first_login_time) AS waktu_login_dashboard'),
                'first_login.station_id',
                DB::raw("TIMEDIFF(
                TIME(first_login.first_login_time), 
                STR_TO_DATE(SUBSTRING_INDEX(kategorishift.shift1, ' - ', 1), '%H:%i')
            ) AS selisih_waktu"),
                'user2.division_id',
                'user2.department_id',
                'user2.section_id'
            )
            ->whereIn('user2.section_id', [31, 25])
            ->distinct()
            ->orderBy('absensici.tanggal', 'desc');

        if (!empty($startDate) && !empty($endDate)) {
            $query->whereBetween('absensici.tanggal', [$startDate, $endDate]);
        }

        $data = $query->get();

        foreach ($data as $item) {
            $section = SectionModel::find($item->section_id);
            $department = $section ? DepartmentModel::find($section->department_id) : null;
            $division = $department ? DivisionModel::find($department->division_id) : null;

            $item->section_nama = $section ? $section->nama : 'Unknown';
            $item->department_nama = $department ? $department->nama : 'Unknown';
            $item->division_nama = $division ? $division->nama : 'Unknown';
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btn = '<button class="btn btn-primary btn-sm btnDetail"
                data-nama="' . e($row->nama) . '"
                data-npk="' . e($row->npk) . '"
                data-tanggal="' . e($row->tanggal) . '"  
                data-section="' . e($row->section_nama) . '"
                data-department="' . e($row->department_nama) . '"
                data-division="' . e($row->division_nama) . '"> 
                Detail
            </button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }



    public function storeLogs(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'created_at' => 'required',
        ]);

        PcdLoginLogs::create([
            'user_id' => $request->user_id,
            'created_at' => $request->created_at,
        ]);

        return response()->json(['success' => 'Check-in berhasil ditambahkan!']);
    }

    public function storeUserId(Request $request)
    {
        $request->validate([
            'id' => 'required',
            // 'nama' => 'required',
            'npk' => 'required',
        ]);

        PcdMasterUser::create([
            'id' => $request->id,
            // 'nama' => $request->nama,
            'npk' => $request->npk,
        ]);

        return response()->json(['success' => 'Check-in berhasil ditambahkan!']);
    }
    public function performaExport(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $search = $request->input('search'); // Ambil parameter search

        return Excel::download(new performaExport($startDate, $endDate, $search), 'Performa.xlsx');
    }
}
