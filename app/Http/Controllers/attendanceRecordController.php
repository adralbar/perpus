<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Carbon\Carbon;
use App\Models\absensici;
use App\Models\absensico;
use App\Jobs\UploadFileJob;
use App\Models\SectionModel;
use App\Models\DepartmentModel;
use App\Models\DivisionModel;
use Illuminate\Support\Facades\DB;
use App\Exports\RekapAbsensiExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class AttendanceSummaryController extends Controller
{
    public function getData(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Mengambil data check-in
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

        // Mengambil data check-out
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
            $status = 'Tepat Waktu';
            if ($checkin->waktuci > $checkin->shift1) {
                $status = 'Terlambat';
            }

            // Simpan data ke array hasil
            $results[$key] = [
                'nama' => $checkin->nama,
                'npk' => $checkin->npk,
                'tanggal' => $checkin->tanggal,
                'waktuci' => $checkin->waktuci,
                'waktuco' => null, // Awalnya null
                'shift1' => $checkin->shift1,
                'status' => $status
            ];

            // Simpan data check-in ke database
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

            // Periksa apakah ada data check-in
            if (isset($results[$key])) {
                $results[$key]['waktuco'] = $checkout->waktuco;

                // Update waktu checkout di database
                attendanceRecordModel::where('npk', $checkout->npk)
                    ->where('tanggal', $checkout->tanggal)
                    ->update(['waktuco' => $checkout->waktuco]);
            } else {
                // Jika tidak ada data check-in, simpan hanya check-out
                attendanceRecordModel::updateOrCreate(
                    [
                        'npk' => $checkout->npk,
                        'tanggal' => $checkout->tanggal
                    ],
                    [
                        'waktuco' => $checkout->waktuco,
                        'status' => 'Unknown' // Status tidak dapat ditentukan jika tidak ada waktu check-in
                    ]
                );
            }
        }

        // Mengurutkan hasil dan mengembalikan data ke DataTables
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

                return $row['waktuci'] > $row['shift1'] ? 'Terlambat' : 'Tepat Waktu';
            })
            ->make(true);
    }
}
