<?php

namespace App\Http\Controllers;

use App\Models\PcdLoginLog;
use App\Models\PcdLoginLogs;
use Illuminate\Http\Request;
use App\Models\PcdMasterUser;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\performaExport;

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
                'pcd_master_users.name',
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

        $data = $query->get();
        return DataTables::of($data)
            ->addIndexColumn()
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
            'name' => 'required',
            'npk' => 'required',
        ]);

        PcdMasterUser::create([
            'id' => $request->id,
            'name' => $request->name,
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
