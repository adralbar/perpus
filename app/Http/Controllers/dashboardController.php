<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class dashboardController extends Controller
{
    public function index()
    {


        $data = DB::table('absensici as a')
            ->select(
                DB::raw('DATE_FORMAT(a.tanggal, "%b") AS month'), // Format tanggal menjadi nama bulan (digit)
                DB::raw('COUNT(DISTINCT a.npk) AS total_keterlambatan') // Hitung jumlah npk yang terlambat
            )
            ->whereRaw('TIME(a.waktuci) > "07:00:00"') // Filter data untuk waktu check-in setelah pukul 07:00:00
            ->groupBy(DB::raw('DATE_FORMAT(a.tanggal, "%b")'), DB::raw('DATE_FORMAT(a.tanggal, "%m")')) // Kelompokkan data berdasarkan nama bulan dan nomor bulan
            ->orderBy(DB::raw('DATE_FORMAT(a.tanggal, "%m")')) // Urutkan hasil berdasarkan nomor bulan
            ->get(); // Eksekusi query dan ambil hasilnya

        // Mengambil label dan total dari koleksi
        $labels = $data->pluck('month');
        $totals = $data->pluck('total_keterlambatan');

        return view('dashboard.dashboard', compact('labels', 'totals'));
    }

    public function getTable1Data(Request $request)
    {
        $bulan = $request->query('bulan');

        Log::info('Received bulan parameter:', ['bulan' => $bulan]);

        $data = DB::table('absensici as a')
            ->select(
                'a.npk',
                'a.nama',
                DB::raw('MONTHNAME(a.tanggal) AS bulan'),
                DB::raw('COUNT(a.npk) AS total_keterlambatan'),
                DB::raw('GROUP_CONCAT(DATE_FORMAT(a.tanggal, "%d-%m-%Y") ORDER BY a.tanggal ASC) AS tanggal_keterlambatan'),
                DB::raw('GROUP_CONCAT(a.waktuci ORDER BY a.tanggal ASC) AS waktu_keterlambatan')
            )
            ->join(DB::raw('(SELECT npk, tanggal, MIN(waktuci) AS waktuci FROM absensici GROUP BY npk, tanggal) as first_checkin'), function ($join) {
                $join->on('a.npk', '=', 'first_checkin.npk')
                    ->on('a.tanggal', '=', 'first_checkin.tanggal')
                    ->on('a.waktuci', '=', 'first_checkin.waktuci');
            })
            ->where('first_checkin.waktuci', '>', '07:00:00');

        if ($bulan) {
            $data->where(DB::raw('DATE_FORMAT(a.tanggal, "%Y-%m")'), $bulan);
        }

        $data = $data->groupBy('a.npk', 'a.nama', 'bulan')
            ->orderByDesc('total_keterlambatan')
            ->get();

        Log::info('Query results:', ['data' => $data]);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($data) {
                return view('tombol.tombol')->with('data', $data);
            })
            ->make(true);
    }
}
