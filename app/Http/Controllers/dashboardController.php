<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use App\Models\SectionModel;
use App\Models\DepartmentModel;
use App\Models\DivisionModel;

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
                    ->whereRaw('absensici.tanggal = kategorishift.date');
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

        // Subquery untuk mendapatkan waktu check-in paling awal per tanggal untuk setiap karyawan
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
                    ->whereRaw('absensici.tanggal = kategorishift.date');
            })
            ->join('users', 'absensici.npk', '=', 'users.npk') // Join ke tabel users
            ->select(
                'absensici.npk',
                'users.nama',
                'users.division_id',
                'users.department_id',
                'users.section_id',
                DB::raw('YEAR(absensici.tanggal) as tahun'),
                DB::raw('COUNT(*) as total_keterlambatan'),
                DB::raw('GROUP_CONCAT(absensici.tanggal ORDER BY absensici.tanggal) as tanggal'),
                DB::raw('GROUP_CONCAT(absensici.waktuci ORDER BY absensici.tanggal) as waktu'),
                'kategorishift.shift1' // Tambahkan shift1 ke dalam query
            )
            ->whereRaw("TIME(subquery.awal_waktuci) > 
            CASE 
                WHEN kategorishift.shift1 LIKE '07:00 - 16:00' THEN '07:00:00'
                WHEN kategorishift.shift1 LIKE '14:00 - 23:00' THEN '14:00:00'
                WHEN kategorishift.shift1 LIKE '21:00 - 06:00' THEN '21:00:00'
                ELSE '00:00:00' 
            END")
            ->when($tahun, function ($query) use ($tahun) {
                $query->whereYear('absensici.tanggal', $tahun);
            })
            ->groupBy('absensici.npk', DB::raw('YEAR(absensici.tanggal)'), 'kategorishift.shift1', 'users.nama')
            ->orderBy(DB::raw('YEAR(absensici.tanggal)'), 'desc')
            ->get();

        // Loop over the results to fetch section, department, and division
        foreach ($data as $item) {
            $section = SectionModel::find($item->section_id);
            $department = $section ? DepartmentModel::find($section->department_id) : null;
            $division = $department ? DivisionModel::find($department->division_id) : null;

            // Tambahkan data section, department, dan division ke setiap item
            $item->section_nama = $section ? $section->nama : 'Unknown';
            $item->department_nama = $department ? $department->nama : 'Unknown';
            $item->division_nama = $division ? $division->nama : 'Unknown';
        }

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
            data-shift1="' . e($row->shift1) . '"
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


    public function getTable1bData(Request $request)
    {
        $bulan = $request->query('bulan'); // Retrieve 'bulan' (month) from the request
        $tahun = $request->query('tahun'); // Retrieve 'tahun' (year) from the request

        Log::info('Received parameters:', ['bulan' => $bulan, 'tahun' => $tahun]); // Log received parameters for debugging purposes

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
                    ->whereRaw('absensici.tanggal = kategorishift.date');
            })
            ->join('users', 'absensici.npk', '=', 'users.npk') // Join ke tabel users
            ->select(
                'absensici.npk',
                'users.nama',
                'users.division_id',
                'users.department_id',
                'users.section_id',
                DB::raw('YEAR(absensici.tanggal) as tahun'),
                DB::raw('COUNT(*) as total_keterlambatan'),
                DB::raw('GROUP_CONCAT(absensici.tanggal ORDER BY absensici.tanggal) as tanggal'),
                DB::raw('GROUP_CONCAT(absensici.waktuci ORDER BY absensici.tanggal) as waktu'),
                'kategorishift.shift1' // Tambahkan shift1 ke dalam query
            )
            ->whereRaw("TIME(subquery.awal_waktuci) > 
            CASE 
                WHEN kategorishift.shift1 LIKE '07:00 - 16:00' THEN '07:00:00'
                WHEN kategorishift.shift1 LIKE '14:00 - 23:00' THEN '14:00:00'
                WHEN kategorishift.shift1 LIKE '21:00 - 06:00' THEN '21:00:00'
                ELSE '00:00:00' 
            END")
            ->when($tahun, function ($query) use ($tahun) {
                $query->whereYear('absensici.tanggal', $tahun);
            })
            ->when($bulan, function ($query) use ($bulan) {
                $query->whereMonth('absensici.tanggal', $bulan);
            })
            ->groupBy('absensici.npk', DB::raw('YEAR(absensici.tanggal)'), 'kategorishift.shift1', 'users.nama')
            ->orderBy(DB::raw('YEAR(absensici.tanggal)'), 'desc')
            ->get();

        // Loop over the results to fetch section, department, and division
        foreach ($data as $item) {
            $section = SectionModel::find($item->section_id);
            $department = $section ? DepartmentModel::find($section->department_id) : null;
            $division = $department ? DivisionModel::find($department->division_id) : null;

            // Tambahkan data section, department, dan division ke setiap item
            $item->section_nama = $section ? $section->nama : 'Unknown';
            $item->department_nama = $department ? $department->nama : 'Unknown';
            $item->division_nama = $division ? $division->nama : 'Unknown';
        }

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
                    data-shift1="' . e($row->shift1) . '"
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
                    ->whereRaw('absensici.tanggal = kategorishift.date');
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
}
