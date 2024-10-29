<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\absensici;
use App\Models\absensico;
use App\Jobs\UploadFileJob;
use App\Models\SectionModel;
use Illuminate\Http\Request;
use App\Models\DivisionModel;
use App\Models\DepartmentModel;
use Illuminate\Support\Facades\DB;
use App\Exports\RekapAbsensiExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\attendanceRecordModel;
use Yajra\DataTables\Facades\DataTables;

class AttendanceSummaryController extends Controller
{
    public function getData(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $checkinData = DB::table('absensici')
            ->join('users', 'absensici.npk', '=', 'users.npk')
            ->leftJoin('kategorishift as ks', function ($join) {
                $join->on('absensici.npk', '=', 'ks.npk')
                    ->on('absensici.tanggal', '=', 'ks.date');
            })
            ->select(
                'users.nama',
                'users.npk as npk',
                'users.section_id',
                'absensici.tanggal',
                DB::raw('DATE_FORMAT(MIN(absensici.waktuci), "%H:%i") as waktuci'),
                DB::raw('(SELECT shift1 FROM kategorishift WHERE npk = absensici.npk AND date = absensici.tanggal ORDER BY created_at DESC LIMIT 1) as shift1')
            )
            ->groupBy(
                'users.nama',
                'users.npk',
                'users.section_id',
                'absensici.tanggal'
            );

        if (!empty($startDate) && !empty($endDate)) {
            $checkinData->whereBetween('absensici.tanggal', [$startDate, $endDate]);
        }

        $checkinResults = $checkinData->get();

        $checkoutData = DB::table('absensico')
            ->join('users', 'absensico.npk', '=', 'users.npk')
            ->leftJoin('kategorishift as ks', function ($join) {
                $join->on('absensico.npk', '=', 'ks.npk')
                    ->on('absensico.tanggal', '=', 'ks.date');
            })
            ->select(
                'users.nama',
                'users.npk as npk',
                'users.section_id',
                'absensico.tanggal',
                DB::raw('DATE_FORMAT(MAX(absensico.waktuco),"%H:%i") as waktuco'),
                DB::raw('(SELECT shift1 FROM kategorishift WHERE npk = absensico.npk AND date = absensico.tanggal ORDER BY created_at DESC LIMIT 1) as shift1')
            )
            ->groupBy(
                'users.nama',
                'users.npk',
                'users.section_id',
                'absensico.tanggal'
            );

        if (!empty($startDate) && !empty($endDate)) {
            $checkoutData->whereBetween('absensico.tanggal', [$startDate, $endDate]);
        }

        $checkoutResults = $checkoutData->get();

        // Gabungkan data check-in dan check-out
        $results = [];

        foreach ($checkinResults as $checkin) {
            $key = $checkin->npk . '-' . $checkin->tanggal;
            $section = SectionModel::find($checkin->section_id);
            $department = $section ? DepartmentModel::find($section->department_id) : null;
            $division = $department ? DivisionModel::find($department->division_id) : null;

            // Tentukan status berdasarkan waktu shift dan waktuci
            $status = 'Tepat Waktu';
            if ($checkin->waktuci > $checkin->shift1) {
                $status = 'Terlambat';
            }

            $results[$key] = [
                'nama' => $checkin->nama,
                'npk' => $checkin->npk,
                'tanggal' => $checkin->tanggal,
                'waktuci' => $checkin->waktuci,
                'waktuco' => null,
                'shift1' => $checkin->shift1,
                'section_nama' => $section ? $section->nama : 'Unknown',
                'department_nama' => $department ? $department->nama : 'Unknown',
                'division_nama' => $division ? $division->nama : 'Unknown',
                'status' => $status
            ];
            attendanceRecordModel::updateOrCreate(
                [
                    'npk' => $checkin->npk,
                    'tanggal' => $checkin->tanggal
                ],
                [
                    'waktuci' => $checkin->waktuci,
                    'shift1' => $checkin->shift1,
                    'status' => $status,
                    'waktuco' => null // Set waktu checkout sebagai null saat check-in
                ]
            );
        }

        foreach ($checkoutResults as $checkout) {
            $key = $checkout->npk . '-' . $checkout->tanggal;
            $section = SectionModel::find($checkout->section_id);
            $department = $section ? DepartmentModel::find($section->department_id) : null;
            $division = $department ? DivisionModel::find($department->division_id) : null;

            if (isset($results[$key])) {
                $results[$key]['waktuco'] = $checkout->waktuco;
            } else {
                $results[$key] = [
                    'nama' => $checkout->nama,
                    'npk' => $checkout->npk,
                    'tanggal' => $checkout->tanggal,
                    'waktuci' => null,
                    'waktuco' => $checkout->waktuco,
                    'shift1' => $checkout->shift1,
                    'section_nama' => $section ? $section->nama : 'Unknown',
                    'department_nama' => $department ? $department->nama : 'Unknown',
                    'division_nama' => $division ? $division->nama : 'Unknown',
                    'status' => 'Unknown' // Tidak bisa menentukan status jika tidak ada waktu check-in
                ];
            }
        }

        $finalResults = collect(array_values($results))->sortByDesc('tanggal');

        return DataTables::of($finalResults)
            ->addIndexColumn()
            ->editColumn('waktuci', function ($row) {
                return $row['waktuci'] ? $row['waktuci'] : 'NO IN';
            })
            ->editColumn('waktuco', function ($row) {
                return $row['waktuco'] ? $row['waktuco'] : 'NO OUT';
            })
            ->editColumn('shift1', function ($row) {
                return $row['shift1'] ? $row['shift1'] : 'NO SHIFT';
            })
            ->editColumn('status', function ($row) {
                if (!$row['shift1']) {
                    return 'NO SHIFT';
                }
                if (!$row['waktuci']) {
                    return 'NO IN';
                }

                if ($row['waktuci'] > $row['shift1']) {
                    return 'Terlambat';
                }
                return 'Tepat Waktu';
            })
            ->make(true);
    }

    public function index()
    {
        // Mengambil daftar tahun yang ada di tabel absensici
        $years = DB::table('absensici')
            ->select(DB::raw('YEAR(tanggal) as year'))
            ->distinct()
            ->pluck('year');

        // Subquery untuk mendapatkan waktu check-in terawal per tanggal untuk setiap karyawan
        $subqueryCheckIn = DB::table('absensici as a')
            ->select(
                'a.npk',
                'a.tanggal',
                DB::raw('MIN(a.waktuci) as awal_waktuci') // Mengambil waktu check-in terawal
            )
            ->groupBy('a.npk', 'a.tanggal');

        // Subquery untuk mendapatkan shift1 terbaru dari kategorishift per tanggal untuk setiap karyawan
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

        // Query utama untuk menghitung total keterlambatan per bulan
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
                DB::raw('COUNT(DISTINCT absensici.npk) AS total_keterlambatan')
            )
            // Filter keterlambatan dengan membandingkan waktuci dan shift1
            ->whereRaw('TIME(subqueryCheckIn.awal_waktuci) > TIME(REPLACE(SUBSTRING_INDEX(subqueryShift.shift1, " - ", 1), ".", ":"))')
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
            ->when($tahun, function ($query) use ($tahun) {
                $query->whereYear('absensici.tanggal', $tahun);
            })
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





    public function getTable1bData(Request $request)
    {
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');

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
                DB::raw('COUNT(DISTINCT CASE WHEN TIME(absensici.waktuci) > TIME(REPLACE(SUBSTRING_INDEX(kategorishift.shift1, " - ", 1), ".", ":")) THEN absensici.tanggal END) as total_keterlambatan'),
                DB::raw('GROUP_CONCAT(DISTINCT absensici.tanggal ORDER BY absensici.tanggal) as tanggal'),
                DB::raw('GROUP_CONCAT(DISTINCT absensici.waktuci ORDER BY absensici.tanggal) as waktu'),
                'kategorishift.shift1' // Tambahkan shift1 ke dalam query
            )
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
