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
        // Mengambil daftar tahun yang ada di tabel absensici
        $years = DB::table('absensici')
            ->select(DB::raw('YEAR(tanggal) as year'))
            ->distinct()
            ->pluck('year');

        // Subquery to get the earliest check-in time per date for each employee
        $subquery = DB::table('absensici as a')
            ->select(
                'a.npk',
                'a.tanggal',
                DB::raw('MIN(a.waktuci) as awal_waktuci')
            )
            ->groupBy('a.npk', 'a.tanggal');

        // Main query to calculate total lateness per month
        $data = DB::table('absensici')
            ->joinSub($subquery, 'subquery', function ($join) {
                $join->on('absensici.npk', '=', 'subquery.npk')
                    ->on('absensici.tanggal', '=', 'subquery.tanggal')
                    ->on('absensici.waktuci', '=', 'subquery.awal_waktuci');
            })
            ->join('kategorishift', function ($join) {
                $join->on('absensici.npk', '=', 'kategorishift.npk')
                    ->whereRaw('absensici.tanggal BETWEEN kategorishift.START_DATE AND kategorishift.END_DATE');
            })
            ->select(
                DB::raw('DATE_FORMAT(absensici.tanggal, "%b") AS month'),
                DB::raw('COUNT(DISTINCT absensici.npk) AS total_keterlambatan')
            )
            // Filter where the check-in time is later than the shift start time
            ->whereRaw("
                CASE 
                    WHEN kategorishift.shift1 LIKE '07:00 - 16:00' THEN TIME(subquery.awal_waktuci) > '07:00:00'
                    WHEN kategorishift.shift1 LIKE '14:00 - 23:00' THEN TIME(subquery.awal_waktuci) > '14:00:00'
                    WHEN kategorishift.shift1 LIKE '21:00 - 06:00' THEN TIME(subquery.awal_waktuci) > '21:00:00'
                    ELSE 1=1 
                END
            ")
            ->groupBy(DB::raw('DATE_FORMAT(absensici.tanggal, "%b")'), DB::raw('DATE_FORMAT(absensici.tanggal, "%m")'))
            ->orderBy(DB::raw('DATE_FORMAT(absensici.tanggal, "%m")'))
            ->get();

        $labels = $data->pluck('month');
        $totals = $data->pluck('total_keterlambatan');

        return view('dashboard.dashboard', compact('labels', 'totals', 'years'));
    }



    public function getTable1Data(Request $request)
    {
        $tahun = $request->query('tahun');

        Log::info('Received parameters:', ['tahun' => $tahun]);

        // Subquery to get the earliest check-in time per date for each employee
        $subquery = DB::table('absensici as a')
            ->select(
                'a.npk',
                'a.tanggal',
                DB::raw('MIN(a.waktuci) as awal_waktuci')
            )
            ->groupBy('a.npk', 'a.tanggal');

        // Main query
        $data = DB::table('absensici')
            ->joinSub($subquery, 'subquery', function ($join) {
                $join->on('absensici.npk', '=', 'subquery.npk')
                    ->on('absensici.tanggal', '=', 'subquery.tanggal')
                    ->on('absensici.waktuci', '=', 'subquery.awal_waktuci');
            })
            ->join('kategorishift', function ($join) {
                $join->on('absensici.npk', '=', 'kategorishift.npk')
                    ->whereRaw('absensici.tanggal BETWEEN kategorishift.start_date AND kategorishift.end_date');
            })
            ->select(
                'absensici.npk',
                'kategorishift.npksistem',
                'kategorishift.divisi',
                'kategorishift.departement',
                'kategorishift.section',
                'kategorishift.nama',
                DB::raw('YEAR(absensici.tanggal) as tahun'),
                DB::raw('COUNT(*) as total_keterlambatan'),
                DB::raw('GROUP_CONCAT(absensici.tanggal ORDER BY absensici.tanggal) as tanggal'),
                DB::raw('GROUP_CONCAT(absensici.waktuci ORDER BY absensici.tanggal) as waktu')
            )
            // Filter where the check-in time is later than the shift start time
            ->whereRaw("
                CASE 
                    WHEN kategorishift.shift1 LIKE '07:00 - 16:00' THEN TIME(subquery.awal_waktuci) > '07:00:00'
                    WHEN kategorishift.shift1 LIKE '14:00 - 23:00' THEN TIME(subquery.awal_waktuci) > '14:00:00'
                    WHEN kategorishift.shift1 LIKE '21:00 - 06:00' THEN TIME(subquery.awal_waktuci) > '21:00:00'
                    ELSE 1=1 
                END
            ")
            ->when($tahun, function ($query) use ($tahun) {
                $query->whereYear('absensici.tanggal', $tahun);
            })
            ->groupBy('absensici.npk', 'kategorishift.npksistem', 'kategorishift.divisi', 'kategorishift.departement', 'kategorishift.section', 'kategorishift.nama', DB::raw('YEAR(absensici.tanggal)'))
            ->orderBy(DB::raw('YEAR(absensici.tanggal)'), 'desc')
            ->get();

        // Prepare the data for DataTables
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btn = '<button class="btn btn-primary btn-sm btnDetail"
                data-nama="' . e($row->nama) . '"
                data-npk="' . e($row->npk) . '"
                data-total="' . e($row->total_keterlambatan) . '"
                data-tanggal="' . e($row->tanggal) . '"  
                data-waktu="' . e($row->waktu) . '"
                data-npksistem="' . e($row->npksistem) . '"
                data-divisi="' . e($row->divisi) . '"
                data-departement="' . e($row->departement) . '"
                data-section="' . e($row->section) . '">
                Detail
            </button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }




    public function getChartData(Request $request)
    {
        $year = $request->query('year');

        // Subquery to get the earliest check-in time per date for each employee
        $subquery = DB::table('absensici as a')
            ->select(
                'a.npk',
                'a.tanggal',
                DB::raw('MIN(a.waktuci) as awal_waktuci')
            )
            ->groupBy('a.npk', 'a.tanggal');

        // Main query to calculate lateness count per month
        $data = DB::table('absensici')
            ->joinSub($subquery, 'subquery', function ($join) {
                $join->on('absensici.npk', '=', 'subquery.npk')
                    ->on('absensici.tanggal', '=', 'subquery.tanggal')
                    ->on('absensici.waktuci', '=', 'subquery.awal_waktuci');
            })
            ->join('kategorishift', function ($join) {
                $join->on('absensici.npk', '=', 'kategorishift.npk')
                    ->whereRaw('absensici.tanggal BETWEEN kategorishift.START_DATE AND kategorishift.END_DATE');
            })
            ->select(
                DB::raw('DATE_FORMAT(absensici.tanggal, "%b") AS month'),
                DB::raw('COUNT(DISTINCT absensici.npk) AS total_keterlambatan')
            )
            ->whereYear('absensici.tanggal', $year)
            ->whereRaw("
                CASE 
                    WHEN kategorishift.shift1 LIKE '07:00 - 16:00' THEN TIME(subquery.awal_waktuci) > '07:00:00'
                    WHEN kategorishift.shift1 LIKE '14:00 - 23:00' THEN TIME(subquery.awal_waktuci) > '14:00:00'
                    WHEN kategorishift.shift1 LIKE '21:00 - 06:00' THEN TIME(subquery.awal_waktuci) > '21:00:00'
                    ELSE 1=1 
                END
            ")
            ->groupBy(DB::raw('DATE_FORMAT(absensici.tanggal, "%b")'), DB::raw('DATE_FORMAT(absensici.tanggal, "%m")'))
            ->orderBy(DB::raw('DATE_FORMAT(absensici.tanggal, "%m")'))
            ->get();

        $labels = $data->pluck('month');
        $totals = $data->pluck('total_keterlambatan');

        return response()->json([
            'labels' => $labels,
            'totals' => $totals
        ]);
    }

    public function getTable1bData(Request $request)
    {
        $bulan = $request->query('bulan'); // Retrieve 'bulan' (month) from the request
        $tahun = $request->query('tahun'); // Retrieve 'tahun' (year) from the request

        Log::info('Received parameters:', ['bulan' => $bulan, 'tahun' => $tahun]); // Log received parameters for debugging purposes

        // Subquery to get the earliest check-in time per date for each employee
        $subquery = DB::table('absensici as a1')
            ->select(
                'a1.npk',
                'a1.tanggal',
                DB::raw('MIN(a1.waktuci) as awal_waktuci')
            )
            ->groupBy('a1.npk', 'a1.tanggal');

        // Main query
        $data = DB::table('absensici')
            ->joinSub($subquery, 'subquery', function ($join) {
                $join->on('absensici.npk', '=', 'subquery.npk')
                    ->on('absensici.tanggal', '=', 'subquery.tanggal')
                    ->on('absensici.waktuci', '=', 'subquery.awal_waktuci');
            })
            ->join('kategorishift', function ($join) {
                $join->on('absensici.npk', '=', 'kategorishift.npk')
                    ->whereRaw('absensici.tanggal BETWEEN kategorishift.start_date AND kategorishift.end_date');
            })
            ->select(
                'absensici.npk',
                'kategorishift.npksistem',
                'kategorishift.divisi',
                'kategorishift.departement',
                'kategorishift.section',
                'kategorishift.nama',
                DB::raw('YEAR(absensici.tanggal) as tahun'),
                DB::raw('MONTH(absensici.tanggal) as bulan'),
                DB::raw('COUNT(*) as total_keterlambatan'),
                DB::raw('GROUP_CONCAT(DATE_FORMAT(absensici.tanggal, "%Y-%m-%d") ORDER BY absensici.tanggal) as tanggal'),
                DB::raw('GROUP_CONCAT(TIME_FORMAT(subquery.awal_waktuci, "%H:%i:%s") ORDER BY absensici.tanggal) as waktu')
            )
            ->whereRaw("
                CASE 
                    WHEN kategorishift.shift1 LIKE '07:00 - 16:00' THEN TIME(subquery.awal_waktuci) > '07:00:00'
                    WHEN kategorishift.shift1 LIKE '14:00 - 23:00' THEN TIME(subquery.awal_waktuci) > '14:00:00'
                    WHEN kategorishift.shift1 LIKE '21:00 - 06:00' THEN TIME(subquery.awal_waktuci) > '21:00:00'
                    ELSE 1=1 
                END
            ") // Filter for lateness based on shift
            ->when($tahun, function ($query) use ($tahun) { // Filter by year if provided
                $query->whereYear('absensici.tanggal', $tahun);
            })
            ->when($bulan, function ($query) use ($bulan) { // Filter by month if provided
                $query->whereMonth('absensici.tanggal', $bulan); // Use whereMonth for clarity
            })
            ->groupBy('absensici.npk', 'kategorishift.npksistem', 'kategorishift.divisi', 'kategorishift.departement', 'kategorishift.section', 'kategorishift.nama', DB::raw('YEAR(absensici.tanggal)'), DB::raw('MONTH(absensici.tanggal)'))
            ->orderBy(DB::raw('YEAR(absensici.tanggal)'), 'desc') // Order by year descending
            ->orderBy(DB::raw('MONTH(absensici.tanggal)'), 'asc') // Order by month ascending
            ->get();

        // Return the data to DataTables with an action column
        return DataTables::of($data)
            ->addIndexColumn() // Add an index column for DataTables
            ->addColumn('aksi', function ($row) { // Add 'aksi' column with buttons
                $btn = '<button class="btn btn-primary btn-sm btnDetail"
                data-nama="' . e($row->nama) . '"
                data-npk="' . e($row->npk) . '"
                data-npksistem="' . e($row->npksistem) . '"
                data-divisi="' . e($row->divisi) . '"
                data-departement="' . e($row->departement) . '"
                data-section="' . e($row->section) . '"
                data-total="' . e($row->total_keterlambatan) . '"
                data-tanggal="' . e($row->tanggal) . '"  
                data-waktu="' . e($row->waktu) . '">
                Detail
            </button>';
                return $btn;
            })
            ->rawColumns(['aksi']) // Render the 'aksi' column as raw HTML
            ->make(true); // Return the formatted data
    }
}
