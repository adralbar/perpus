<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PcdLoginLog;
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
        $data = DB::table('pcd_login_logs')
            ->join('pcd_master_users', function ($join) {
                $join->on(DB::raw('CONVERT(pcd_login_logs.user_id USING utf8mb4)'), '=', DB::raw('CONVERT(pcd_master_users.id USING utf8mb4)'));
            })
            ->leftJoin(DB::raw('(SELECT npk, tanggal, MIN(waktuci) AS waktuci FROM absensici GROUP BY npk, tanggal) as first_checkin'), function ($join) {
                $join->on(DB::raw('CONVERT(first_checkin.npk USING utf8mb4)'), '=', DB::raw('CONVERT(pcd_master_users.npk USING utf8mb4)'))
                    ->on('first_checkin.tanggal', '=', DB::raw('DATE(pcd_login_logs.created_at)'));
            })
            ->leftJoin('absensici', function ($join) {
                $join->on(DB::raw('CONVERT(absensici.npk USING utf8mb4)'), '=', DB::raw('CONVERT(first_checkin.npk USING utf8mb4)'))
                    ->on('absensici.tanggal', '=', 'first_checkin.tanggal')
                    ->on('absensici.waktuci', '=', 'first_checkin.waktuci');
            })
            ->select(
                'pcd_master_users.name',
                'pcd_master_users.npk',
                DB::raw('DATE(pcd_login_logs.created_at) AS tanggal'),
                'absensici.waktuci AS waktuci_checkin',
                'pcd_login_logs.created_at AS waktu_login_dashboard'
            )
            ->orderBy('pcd_login_logs.created_at', 'desc')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }
}
