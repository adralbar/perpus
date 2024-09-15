<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PcdLoginLog;
use App\Models\PcdLoginLogs;
use App\Models\PcdMasterUser;
use Yajra\DataTables\Facades\DataTables;

class performaController extends Controller
{
    public function index()
    {
        return view('performa.performa');
    }
    public function getData()
    {
        $data = DB::table('absensici')
            ->leftJoin('pcd_master_users', function ($join) {
                $join->on(DB::raw('CONVERT(absensici.npk USING utf8mb4)'), '=', DB::raw('CONVERT(pcd_master_users.npk USING utf8mb4)'));
            })
            ->leftJoin('pcd_login_logs', function ($join) {
                $join->on(DB::raw('CONVERT(pcd_master_users.id USING utf8mb4)'), '=', DB::raw('CONVERT(pcd_login_logs.user_id USING utf8mb4)'))
                    ->on('absensici.tanggal', '=', DB::raw('DATE(pcd_login_logs.created_at)'));
            })
            ->leftJoin(DB::raw('(SELECT npk, tanggal, MIN(waktuci) AS waktuci FROM absensici GROUP BY npk, tanggal) as first_checkin'), function ($join) {
                $join->on(DB::raw('CONVERT(first_checkin.npk USING utf8mb4)'), '=', DB::raw('CONVERT(absensici.npk USING utf8mb4)'))
                    ->on('first_checkin.tanggal', '=', 'absensici.tanggal');
            })
            ->leftJoin('kategorishift', function ($join) {
                $join->on(DB::raw('CONVERT(absensici.npk USING utf8mb4)'), '=', DB::raw('CONVERT(kategorishift.npk USING utf8mb4)'));
            })
            ->select(
                'pcd_master_users.nama',
                'absensici.npk',
                'absensici.tanggal',
                'first_checkin.waktuci AS waktuci_checkin',
                DB::raw('TIME(pcd_login_logs.created_at) AS waktu_login_dashboard'),
                DB::raw('TIMEDIFF(TIME(pcd_login_logs.created_at), TIME(first_checkin.waktuci)) AS selisih_waktu'),
                'kategorishift.npkSistem', // Adding npkSistem
                'kategorishift.divisi', // Adding divisi
                'kategorishift.departement', // Adding departement
                'kategorishift.section', // Adding section
                'kategorishift.nama AS nama' // Renaming nama to shift_nama to avoid ambiguity
            )
            ->distinct()
            ->orderBy('absensici.tanggal', 'desc')
            ->get();

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
            'nama' => 'required',
            'npk' => 'required',
        ]);

        PcdMasterUser::create([
            'id' => $request->id,
            'nama' => $request->nama,
            'npk' => $request->npk,
        ]);

        return response()->json(['success' => 'Check-in berhasil ditambahkan!']);
    }
}
