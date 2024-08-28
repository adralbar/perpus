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
        $data = DB::table(DB::raw('(
            SELECT
                npk,
                tanggal,
                MIN(waktuci) AS waktuci
            FROM absensici
            GROUP BY npk, tanggal
        ) as first_checkin')) // Subquery untuk mendapatkan waktu check-in pertama setiap npk per tanggal
            ->select(
                DB::raw('DATE_FORMAT(first_checkin.tanggal, "%b") AS month'), // Format tanggal menjadi nama bulan (digit)
                DB::raw('COUNT(DISTINCT first_checkin.npk) AS total_keterlambatan') // Hitung jumlah npk yang terlambat
            )
            ->whereRaw('TIME(first_checkin.waktuci) > "07:00:00"') // Filter data untuk waktu check-in setelah pukul 07:00:00
            ->groupBy(DB::raw('DATE_FORMAT(first_checkin.tanggal, "%b")'), DB::raw('DATE_FORMAT(first_checkin.tanggal, "%m")')) // Kelompokkan data berdasarkan nama bulan dan nomor bulan
            ->orderBy(DB::raw('DATE_FORMAT(first_checkin.tanggal, "%m")')) // Urutkan hasil berdasarkan nomor bulan
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

        // Subquery untuk mendapatkan absensi pertama setiap hari
        $subquery = DB::table('absensici')
            ->select('npk', 'tanggal', DB::raw('MIN(waktuci) AS waktuci'))
            ->groupBy('npk', 'tanggal');



        // Query utama 
        $query = DB::table(DB::raw("({$subquery->toSql()}) as first_checkin"))
            ->mergeBindings($subquery) // Menyertakan binding dari subquery
            ->join('absensici as a', function ($join) {
                $join->on('a.npk', '=', 'first_checkin.npk')
                    ->on('a.tanggal', '=', 'first_checkin.tanggal')
                    ->on('a.waktuci', '=', 'first_checkin.waktuci');
            })
            ->select(
                'a.nama',
                'a.npk',

                DB::raw('MONTHNAME(a.tanggal) AS bulan'), // Menampilkan nama bulan
                DB::raw('COUNT(*) AS total_keterlambatan')
            )
            ->where('first_checkin.waktuci', '>', '07:00:00'); // Filter absensi pertama setelah pukul 07:00


        if ($bulan) {
            $query->where(DB::raw('DATE_FORMAT(a.tanggal, "%Y-%m")'), $bulan);
        }

        $data = $query->groupBy('a.nama', 'a.npk', 'bulan')
            ->orderByDesc('total_keterlambatan')
            ->get();

        Log::info('Query results:', ['data' => $data]);

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }
}
