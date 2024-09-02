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

        $data = DB::table('absensici as a')
            ->select(
                DB::raw('DATE_FORMAT(a.tanggal, "%b") AS month'),
                DB::raw('COUNT(DISTINCT a.npk) AS total_keterlambatan')
            )
            ->whereRaw('TIME(a.waktuci) > "07:00:00"')
            ->groupBy(DB::raw('DATE_FORMAT(a.tanggal, "%b")'), DB::raw('DATE_FORMAT(a.tanggal, "%m")'))
            ->orderBy(DB::raw('DATE_FORMAT(a.tanggal, "%m")'))
            ->get();

        $labels = $data->pluck('month');
        $totals = $data->pluck('total_keterlambatan');

        return view('dashboard.dashboard', compact('labels', 'totals', 'years'));
    }

    public function getTable1Data(Request $request)
    {
        $tahun = $request->query('tahun');

        Log::info('Received parameters:', ['tahun' => $tahun]);

        $data = DB::table('absensici')
            ->select(
                'npk',
                'nama',
                DB::raw('YEAR(tanggal) as tahun'),
                DB::raw('COUNT(*) as total_keterlambatan'),
                DB::raw('GROUP_CONCAT(tanggal) as tanggal'), // Menyimpan semua tanggal dalam satu kolom
                DB::raw('GROUP_CONCAT(waktuci) as waktu') // Menyimpan semua waktu dalam satu kolom
            )
            ->whereRaw('TIME(waktuci) > "07:00:00"')
            ->when($tahun, function ($query) use ($tahun) {
                $query->whereYear('tanggal', $tahun);
            })
            ->groupBy('npk', 'nama', DB::raw('YEAR(tanggal)'))
            ->orderBy(DB::raw('YEAR(tanggal)'), 'desc')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btn = '<button class="btn btn-primary btn-sm btnDetail"
                data-nama="' . $row->nama . '"
                data-npk="' . $row->npk . '"
                data-total="' . $row->total_keterlambatan . '"
                data-tanggal="' . $row->tanggal . '"
                data-waktu="' . $row->waktu . '">
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

        $data = DB::table('absensici as a')
            ->select(
                DB::raw('DATE_FORMAT(a.tanggal, "%b") AS month'),
                DB::raw('COUNT(DISTINCT a.npk) AS total_keterlambatan')
            )
            ->whereYear('a.tanggal', $year)
            ->whereRaw('TIME(a.waktuci) > "07:00:00"')
            ->groupBy(DB::raw('DATE_FORMAT(a.tanggal, "%b")'), DB::raw('DATE_FORMAT(a.tanggal, "%m")'))
            ->orderBy(DB::raw('DATE_FORMAT(a.tanggal, "%m")'))
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
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');

        Log::info('Received parameters:', ['bulan' => $bulan, 'tahun' => $tahun]);

        $data = DB::table('absensici')
            ->select(
                'npk',
                'nama',
                DB::raw('YEAR(tanggal) as tahun'),
                DB::raw('MONTH(tanggal) as bulan'),
                DB::raw('COUNT(*) as total_keterlambatan'),
                DB::raw('GROUP_CONCAT(tanggal) as tanggal'), // Menyimpan semua tanggal dalam satu kolom
                DB::raw('GROUP_CONCAT(waktuci) as waktu') // Menyimpan semua waktu dalam satu kolom
            )
            ->whereRaw('TIME(waktuci) > "07:00:00"')
            ->when($tahun, function ($query) use ($tahun) {
                $query->whereYear('tanggal', $tahun);
            })
            ->when($bulan, function ($query) use ($bulan) {
                $query->where(DB::raw('DATE_FORMAT(tanggal, "%Y-%m")'), '=', $bulan); // Format YEAR-MONTH
            })
            ->groupBy('npk', 'nama', DB::raw('YEAR(tanggal)'), DB::raw('MONTH(tanggal)'))
            ->orderBy(DB::raw('YEAR(tanggal)'), 'desc')
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btn = '<button class="btn btn-primary btn-sm btnDetail"
                data-nama="' . $row->nama . '"
                data-npk="' . $row->npk . '"
                data-total="' . $row->total_keterlambatan . '"
                data-tanggal="' . $row->tanggal . '" 
                data-waktu="' . $row->waktu . '">
                Detail
            </button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}
