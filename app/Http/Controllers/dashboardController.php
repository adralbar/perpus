<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use App\Models\SectionModel;
use App\Models\DepartmentModel;
use App\Models\DivisionModel;
use Carbon\Carbon;

class dashboardController extends Controller
{
    public function index(Request $request)
    {
        //     set_time_limit(300);

        // Ambil tahun dari parameter query, atau gunakan tahun saat ini jika tidak ada
        $year = $request->query('year', date('Y'));

        // Mendapatkan daftar tahun yang tersedia dalam tabel absensici
        $years = DB::table('absensici')
            ->select(DB::raw('YEAR(tanggal) as year'))
            ->distinct()
            ->pluck('year');

        //     // Subquery untuk mendapatkan waktu check-in terawal per tanggal untuk setiap karyawan
        //     $subqueryCheckIn = DB::table('absensici as a')
        //         ->select(
        //             'a.npk',
        //             'a.tanggal',
        //             DB::raw('MIN(a.waktuci) as awal_waktuci') // Mengambil waktu check-in terawal
        //         )
        //         ->groupBy('a.npk', 'a.tanggal');

        //     // Subquery untuk mendapatkan shift1 terbaru dari kategorishift per tanggal untuk setiap karyawan
        //     $subqueryShift = DB::table('kategorishift as ks')
        //         ->select(
        //             'ks.npk',
        //             'ks.date',
        //             'ks.shift1'
        //         )
        //         ->whereIn('ks.id', function ($query) {
        //             $query->select(DB::raw('MAX(id)'))
        //                 ->from('kategorishift as inner_ks')
        //                 ->whereColumn('inner_ks.npk', 'ks.npk')
        //                 ->whereColumn('inner_ks.date', 'ks.date');
        //         });
        //     // Query utama untuk menghitung total keterlambatan per bulan
        //     $data = DB::table('absensici')
        //         ->joinSub($subqueryCheckIn, 'subqueryCheckIn', function ($join) {
        //             $join->on('absensici.npk', '=', 'subqueryCheckIn.npk')
        //                 ->on('absensici.tanggal', '=', 'subqueryCheckIn.tanggal')
        //                 ->on('absensici.waktuci', '=', 'subqueryCheckIn.awal_waktuci');
        //         })
        //         ->joinSub($subqueryShift, 'subqueryShift', function ($join) {
        //             $join->on('absensici.npk', '=', 'subqueryShift.npk')
        //                 ->on('absensici.tanggal', '=', 'subqueryShift.date');
        //         })
        //         ->select(
        //             DB::raw('DATE_FORMAT(absensici.tanggal, "%b") AS month'),
        //             DB::raw('
        //             (COUNT(DISTINCT absensici.npk) * 100 / (SELECT COUNT(*) FROM users))AS total_keterlambatan
        //         ')
        //         )
        //         // Filter keterlambatan dengan membandingkan waktuci dan shift1
        //         ->whereRaw('TIME(subqueryCheckIn.awal_waktuci) > TIME(REPLACE(SUBSTRING_INDEX(subqueryShift.shift1, " - ", 1), ".", ":"))')
        //         ->whereYear('absensici.tanggal', $year)
        //         ->groupBy(DB::raw('DATE_FORMAT(absensici.tanggal, "%b")'), DB::raw('DATE_FORMAT(absensici.tanggal, "%m")'))
        //         ->orderBy(DB::raw('DATE_FORMAT(absensici.tanggal, "%m")'));

        //     $labels = $data->pluck('month');
        //     $totals = $data->pluck('total_keterlambatan');

        return view('dashboard.dashboard', compact('years', 'year'));
        // return view('dashboard.dashboard', compact('labels', 'totals', 'years', 'year'));
    }


    public function getChartData(Request $request)
    {
        set_time_limit(0);

        $year = $request->query('year', date('Y'));

        $years = DB::table('absensici')
            ->select(DB::raw('YEAR(tanggal) as year'))
            ->distinct()
            ->pluck('year');

        $subqueryCheckIn = DB::table('absensici as a')
            ->select(
                'a.npk',
                'a.tanggal',
                DB::raw('MIN(a.waktuci) as awal_waktuci')
            )
            ->groupBy('a.npk', 'a.tanggal');

        $subqueryShift = DB::table('kategorishift as ks')
            ->select(
                'ks.npk',
                'ks.date',
                'ks.shift1'
            )
            ->whereIn('ks.id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('kategorishift as inner_ks')
                    ->whereColumn('inner_ks.npk', 'ks.npk')
                    ->whereColumn('inner_ks.date', 'ks.date');
            });

        $data = DB::table('absensici')
            ->joinSub($subqueryCheckIn, 'subqueryCheckIn', function ($join) {
                $join->on('absensici.npk', '=', 'subqueryCheckIn.npk')
                    ->on('absensici.tanggal', '=', 'subqueryCheckIn.tanggal')
                    ->on('absensici.waktuci', '=', 'subqueryCheckIn.awal_waktuci');
            })
            ->joinSub($subqueryShift, 'subqueryShift', function ($join) {
                $join->on('absensici.npk', '=', 'subqueryShift.npk')
                    ->on('absensici.tanggal', '=', 'subqueryShift.date');
            })
            ->select(
                DB::raw('DATE_FORMAT(absensici.tanggal, "%b") AS month'),
                DB::raw('
                        (COUNT(DISTINCT absensici.npk) * 100 / (SELECT COUNT(*) FROM users)) AS total_keterlambatan
                    ')
            )
            ->whereYear('absensici.tanggal', $year)
            ->whereRaw('TIME(subqueryCheckIn.awal_waktuci) > TIME(REPLACE(SUBSTRING_INDEX(subqueryShift.shift1, " - ", 1), ".", ":"))')
            ->whereRaw('DAYOFWEEK(absensici.tanggal) NOT IN (1, 7)') // Filter untuk menghindari Sabtu dan Minggu
            ->groupBy(DB::raw('DATE_FORMAT(absensici.tanggal, "%b")'), DB::raw('DATE_FORMAT(absensici.tanggal, "%m")'))
            ->orderBy(DB::raw('DATE_FORMAT(absensici.tanggal, "%m")'))
            ->get();

        $labels = $data->pluck('month');
        $totals = $data->pluck('total_keterlambatan');

        return response()->json([
            'years' => $years,
            'year' => $year,
            'labels' => $labels,
            'totals' => $totals,
        ]);
    }


    public function getDataPerTanggal(Request $request)
    {
        $month = $request->query('month');
        $year = $request->query('year', date('Y'));

        $subqueryCheckIn = DB::table('absensici as a')
            ->select(
                'a.npk',
                'a.tanggal',
                DB::raw('MIN(a.waktuci) as awal_waktuci')
            )
            ->groupBy('a.npk', 'a.tanggal');

        $subqueryShift = DB::table('kategorishift as ks')
            ->select(
                'ks.npk',
                'ks.date',
                'ks.shift1'
            )
            ->whereIn('ks.id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('kategorishift as inner_ks')
                    ->whereColumn('inner_ks.npk', 'ks.npk')
                    ->whereColumn('inner_ks.date', 'ks.date');
            });

        $data = DB::table('absensici')
            ->joinSub($subqueryCheckIn, 'subqueryCheckIn', function ($join) {
                $join->on('absensici.npk', '=', 'subqueryCheckIn.npk')
                    ->on('absensici.tanggal', '=', 'subqueryCheckIn.tanggal')
                    ->on('absensici.waktuci', '=', 'subqueryCheckIn.awal_waktuci');
            })
            ->joinSub($subqueryShift, 'subqueryShift', function ($join) {
                $join->on('absensici.npk', '=', 'subqueryShift.npk')
                    ->on('absensici.tanggal', '=', 'subqueryShift.date');
            })
            ->select(
                DB::raw('DATE_FORMAT(absensici.tanggal, "%d") AS day'),
                DB::raw('
                    (COUNT(DISTINCT absensici.npk) * 100 / (SELECT COUNT(*) FROM users)) AS total_keterlambatan
                ')
            )
            ->whereYear('absensici.tanggal', $year)
            ->whereMonth('absensici.tanggal', $month)
            ->whereRaw('TIME(subqueryCheckIn.awal_waktuci) > TIME(REPLACE(SUBSTRING_INDEX(subqueryShift.shift1, " - ", 1), ".", ":"))')
            ->whereRaw('DAYOFWEEK(absensici.tanggal) NOT IN (1, 7)') // Filter untuk menghindari Sabtu dan Minggu
            ->groupBy(DB::raw('DATE_FORMAT(absensici.tanggal, "%d")'))
            ->orderBy(DB::raw('DATE_FORMAT(absensici.tanggal, "%d")'))
            ->get();

        $days = $data->pluck('day');
        $totals = $data->pluck('total_keterlambatan');

        return response()->json([
            'month' => $month,
            'year' => $year,
            'days' => $days,
            'totals' => $totals
        ]);
    }





    // public function getTable1Data(Request $request)
    // {
    //     $tahun = $request->query('tahun');

    //     Log::info('Received parameters:', ['tahun' => $tahun]);

    //     $subquery = DB::table('absensici as a')
    //         ->select(
    //             'a.npk',
    //             'a.tanggal',
    //             DB::raw('MIN(a.waktuci) as awal_waktuci')
    //         )
    //         ->groupBy('a.npk', 'a.tanggal');

    //     // Main query
    //     $data = DB::table('absensici')
    //         ->joinSub($subquery, 'subquery', function ($join) {
    //             $join->on('absensici.npk', '=', 'subquery.npk')
    //                 ->on('absensici.tanggal', '=', 'subquery.tanggal')
    //                 ->on('absensici.waktuci', '=', 'subquery.awal_waktuci');
    //         })
    //         ->join('kategorishift as k', function ($join) {
    //             $join->on('absensici.npk', '=', 'k.npk')
    //                 ->whereColumn('absensici.tanggal', 'k.date')
    //                 ->whereRaw('k.shift1 = (
    //                     SELECT ks.shift1 
    //                     FROM kategorishift ks 
    //                     WHERE ks.npk = absensici.npk 
    //                     AND ks.date = absensici.tanggal 
    //                     ORDER BY ks.created_at DESC 
    //                     LIMIT 1
    //                 )');
    //         })
    //         ->join('users', 'absensici.npk', '=', 'users.npk')
    //         ->select(
    //             'absensici.npk',
    //             'users.nama',
    //             'users.division_id',
    //             'users.department_id',
    //             'users.section_id',
    //             DB::raw('YEAR(absensici.tanggal) as tahun'),
    //             // Hitung total keterlambatan
    //             DB::raw('COUNT(DISTINCT CASE WHEN TIME(absensici.waktuci) > TIME(REPLACE(SUBSTRING_INDEX(k.shift1, " - ", 1), ".", ":")) THEN absensici.tanggal END) as total_keterlambatan'),
    //             DB::raw('GROUP_CONCAT(DISTINCT absensici.tanggal ORDER BY absensici.tanggal) as tanggal'),
    //             DB::raw('GROUP_CONCAT(DISTINCT absensici.waktuci ORDER BY absensici.tanggal) as waktu'),
    //             DB::raw('GROUP_CONCAT(DISTINCT k.shift1 ORDER BY absensici.tanggal) as shift1')
    //         )
    //         ->when($tahun, function ($query) use ($tahun) {
    //             $query->whereYear('absensici.tanggal', $tahun);
    //         })
    //         ->groupBy('absensici.npk', DB::raw('YEAR(absensici.tanggal)'), 'users.nama')
    //         ->having('total_keterlambatan', '>', 0) // Exclude rows with total_keterlambatan = 0
    //         ->orderBy(DB::raw('YEAR(absensici.tanggal)'), 'desc')
    //         ->get();

    //     // Loop over the results to fetch section, department, and division
    //     foreach ($data as $item) {
    //         $section = SectionModel::find($item->section_id);
    //         $department = $section ? DepartmentModel::find($section->department_id) : null;
    //         $division = $department ? DivisionModel::find($department->division_id) : null;

    //         // Add section, department, and division names to each item
    //         $item->section_nama = $section ? $section->nama : 'Unknown';
    //         $item->department_nama = $department ? $department->nama : 'Unknown';
    //         $item->division_nama = $division ? $division->nama : 'Unknown';
    //     }

    //     // Prepare the data for DataTables
    //     return DataTables::of($data)
    //         ->addIndexColumn()
    //         ->addColumn('aksi', function ($row) {
    //             $btn = '<button class="btn btn-primary btn-sm btnDetail"
    //                 data-nama="' . e($row->nama) . '"
    //                 data-npk="' . e($row->npk) . '"
    //                 data-total="' . e($row->total_keterlambatan) . '"
    //                 data-tanggal="' . e($row->tanggal) . '"  
    //                 data-waktu="' . e($row->waktu) . '"
    //                 data-shift1="' . e($row->shift1) . '"
    //                 data-section="' . e($row->section_nama) . '"
    //                 data-department="' . e($row->department_nama) . '"
    //                 data-division="' . e($row->division_nama) . '"> 
    //                 Detail
    //                 </button>';
    //             return $btn;
    //         })
    //         ->rawColumns(['aksi'])
    //         ->make(true);
    // }

    public function getTable1bData(Request $request)
    {
        // Ambil nilai tahun dan bulan dari request, gunakan current year dan month jika null
        $tahun = $request->query('tahun', Carbon::now()->year);
        $bulan = $request->query('bulan', Carbon::now()->month);

        Log::info('Received parameters:', ['tahun' => $tahun, 'bulan' => $bulan]);

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
            ->join('kategorishift as k', function ($join) {
                $join->on('absensici.npk', '=', 'k.npk')
                    ->whereColumn('absensici.tanggal', 'k.date')
                    ->whereRaw('k.shift1 = (
                            SELECT ks.shift1 
                            FROM kategorishift ks 
                            WHERE ks.npk = absensici.npk 
                            AND ks.date = absensici.tanggal 
                            ORDER BY ks.created_at DESC 
                            LIMIT 1
                        )');
            })
            ->join('users', 'absensici.npk', '=', 'users.npk')
            ->select(
                'absensici.npk',
                'users.nama',
                'users.division_id',
                'users.department_id',
                'users.section_id',
                DB::raw('YEAR(absensici.tanggal) as tahun'),
                // Hitung total keterlambatan
                DB::raw('COUNT(DISTINCT CASE WHEN TIME(absensici.waktuci) > TIME(REPLACE(SUBSTRING_INDEX(k.shift1, " - ", 1), ".", ":")) THEN absensici.tanggal END) as total_keterlambatan'),
                DB::raw('GROUP_CONCAT(DISTINCT absensici.tanggal ORDER BY absensici.tanggal) as tanggal'),
                DB::raw('GROUP_CONCAT(DISTINCT absensici.waktuci ORDER BY absensici.tanggal) as waktu'),
                DB::raw('GROUP_CONCAT(DISTINCT k.shift1 ORDER BY absensici.tanggal) as shift1')
            )
            ->whereYear('absensici.tanggal', $tahun)
            ->whereMonth('absensici.tanggal', $bulan)
            ->groupBy('absensici.npk', DB::raw('YEAR(absensici.tanggal)'), 'users.nama')
            ->having('total_keterlambatan', '>', 0) // Exclude rows with total_keterlambatan = 0
            ->orderBy(DB::raw('YEAR(absensici.tanggal)'), 'desc')
            ->get();

        // Loop over the results to fetch section, department, and division
        foreach ($data as $item) {
            $section = SectionModel::find($item->section_id);
            $department = $section ? DepartmentModel::find($section->department_id) : null;
            $division = $department ? DivisionModel::find($department->division_id) : null;

            // Add section, department, and division names to each item
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
}
