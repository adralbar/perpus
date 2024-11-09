<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Models\shift;
use App\Models\absensici;
use App\Models\absensico;
use App\Jobs\UploadFileJob;
use App\Events\FileUploaded;
use App\Models\SectionModel;
use Illuminate\Http\Request;
use App\Models\DivisionModel;
use App\Models\DepartmentModel;
use App\Models\PenyimpanganModel;
use Illuminate\Support\Facades\DB;
use App\Exports\RekapAbsensiExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\attendanceRecordModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Artisan;
use PhpParser\Node\Stmt\Else_;
use Illuminate\Support\Facades\Auth;
use App\Models\CutiModel;


abstract class Controller
{
    public function getData(Request $request)
    {
        $today = date('Y-m-d', strtotime('-1 day'));
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $checkinQuery = Absensici::with(['user', 'shift'])
            ->select('npk', 'tanggal', DB::raw('MIN(waktuci) as waktuci'))
            ->groupBy('npk', 'tanggal');

        if (!empty($startDate) && !empty($endDate)) {
            $checkinQuery->whereBetween('tanggal', [$startDate, $endDate]);
        }
        if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
            $selectedNPK = $request->selectedNpk;
            $checkinQuery->whereIn('npk', $selectedNPK);
        }

        $checkinResults = $checkinQuery->get();

        $checkoutQuery = Absensico::with(['user', 'shift'])
            ->select('npk', 'tanggal', DB::raw('MAX(waktuco) as waktuco'))
            ->groupBy('npk', 'tanggal');

        if (!empty($startDate) && !empty($endDate)) {
            $checkoutQuery->whereBetween('tanggal', [$startDate, $endDate]);
        }
        if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
            $selectedNPK = $request->selectedNpk;
            $checkoutQuery->whereIn('npk', $selectedNPK);
        }
        $checkoutResults = $checkoutQuery->get();

        // Gabungkan data check-in dan check-out
        $results = [];

        foreach ($checkinResults as $checkin) {
            $key = "{$checkin->npk}-{$checkin->tanggal}";
            $section = $checkin->user ? $checkin->user->section : null;
            $department = $section ? $section->department : null;
            $division = $department ? $department->division : null;

            $latestShift = shift::where('npk', $checkin->npk)
                ->where('date', $checkin->tanggal)
                ->latest()
                ->first();
            $shift1 = $latestShift ? $latestShift->shift1 : null;

            $shiftIn = $shift1 ? explode(' - ', str_replace('.', ':', $shift1))[0] : null;

            $shiftInFormatted = $shiftIn ? date('H:i:s', strtotime($shiftIn)) : null; // Ganti '.' dengan ':' sebelum konversi

            if ($latestShift === null) {
                $status = 'No Shift';
            } else if ($checkin->waktuci > $shiftInFormatted) {
                $status = 'Terlambat';
            } else {
                $status = 'Tepat Waktu';
            }


            // Hasil akhir
            $results[$key] = [
                'nama' => $checkin->user ? $checkin->user->nama : '',
                'npk' => $checkin->npk,
                'tanggal' => $checkin->tanggal,
                'waktuci' => $checkin->waktuci,
                'waktuco' => null,
                'shift1' => $shift1,
                'section_nama' => $section ? $section->nama : '',
                'department_nama' => $department ? $department->nama : '',
                'division_nama' => $division ? $division->nama : '',
                'status' => $status
            ];
        }

        // Dalam loop checkoutResults
        foreach ($checkoutResults as $checkout) {
            $key = "{$checkout->npk}-{$checkout->tanggal}";

            if (isset($results[$key])) {
                $results[$key]['waktuco'] = $checkout->waktuco;
            } else {
                $previousDay = date('Y-m-d', strtotime("{$checkout->tanggal} -1 day"));
                $previousKey = "{$checkout->npk}-{$previousDay}";

                if (isset($results[$previousKey]) && !$results[$previousKey]['waktuco']) {
                    $results[$previousKey]['waktuco'] = $checkout->waktuco;
                } else {
                    $results[$key] = [
                        'nama' => $checkout->user ? $checkout->user->nama : '',
                        'npk' => $checkout->npk,
                        'tanggal' => $checkout->tanggal,
                        'waktuci' => null,
                        'waktuco' => $checkout->waktuco,
                        'shift1' => optional($checkout->shift)->shift1,
                        'section_nama' => $checkout->user && $checkout->user->section ? $checkout->user->section->nama : '',
                        'department_nama' => $checkout->user && $checkout->user->section && $checkout->user->section->department ? $checkout->user->section->department->nama : '',
                        'division_nama' => $checkout->user && $checkout->user->section && $checkout->user->section->department && $checkout->user->section->department->division ? $checkout->user->section->department->division->nama : '',
                        'status' => 'Unknown'
                    ];
                }
            }
        }


        $noCheckData = Shift::with(['user.section.department.division'])
            ->leftJoin('absensici', function ($join) {
                $join->on('absensici.npk', '=', 'kategorishift.npk')
                    ->on('absensici.tanggal', '=', 'kategorishift.date');
            })
            ->leftJoin('absensico', function ($join) {
                $join->on('absensico.npk', '=', 'kategorishift.npk')
                    ->on('absensico.tanggal', '=', 'kategorishift.date');
            })
            ->select(
                'kategorishift.npk',
                'kategorishift.date as tanggal',
                'kategorishift.shift1',
                DB::raw('IFNULL(DATE_FORMAT(MIN(absensici.waktuci), "%H:%i"), "NO IN") as waktuci'),
                DB::raw('IFNULL(DATE_FORMAT(MAX(absensico.waktuco), "%H:%i"), "NO OUT") as waktuco')
            )
            ->whereNull('absensici.waktuci')
            ->whereNull('absensico.waktuco')
            ->where('kategorishift.date', '<=', $today)
            ->where('kategorishift.shift1', '!=', 'OFF')
            ->groupBy('kategorishift.npk', 'kategorishift.date', 'kategorishift.shift1')
            ->get();

        foreach ($noCheckData as $noCheck) {
            $key = "{$noCheck->npk}-{$noCheck->tanggal}";

            if (!isset($results[$key])) {
                $results[$key] = [
                    'nama' => $noCheck->user ? $noCheck->user->nama : '',
                    'npk' => $noCheck->npk,
                    'tanggal' => $noCheck->tanggal,
                    'waktuci' => 'NO IN',
                    'waktuco' => 'NO OUT',
                    'shift1' => $noCheck->shift1,
                    'section_nama' => $noCheck->user->section ? $noCheck->user->section->nama : '',
                    'department_nama' => $noCheck->user->section->department ? $noCheck->user->section->department->nama : '',
                    'division_nama' => $noCheck->user->section->department->division ? $noCheck->user->section->department->division->nama : '',
                    'status' => 'Mangkir'
                ];
            }
        }

        $finalResults = collect(array_values($results))->sortByDesc('tanggal');

        foreach ($finalResults as $key => $row) {
            $npk = $row['npk'];
            $tanggalMulai = $row['tanggal'] ?? null;

            if (!$tanggalMulai) {
                Log::error("Tanggal tidak ditemukan untuk NPK: $npk");
                continue;
            }

            $penyimpanganCount = Penyimpanganmodel::where('npk', $npk)
                ->where('tanggal_mulai', $tanggalMulai)
                ->count();

            $apiTime = null;
            if ($penyimpanganCount > 0) {
                $apiTime = '<button class="btn btn-warning view-pelanggaran" data-npk="' . $npk . '" data-tanggal="' . $tanggalMulai . '">Lihat Penyimpangan</button>';
            }

            $finalResults->put($key, array_merge($row, [
                'has_penyimpangan' => $penyimpanganCount > 0,
                'api_time' => $apiTime
            ]));
        }

        $finalResults = $finalResults->map(function ($row) {
            // Return semua data dari $row, dengan kondisi untuk 'waktuci' dan 'waktuco'
            $row['waktuci'] = $row['waktuci'] ?: 'NO IN';
            $row['waktuco'] = $row['waktuco'] ?: 'NO OUT';

            return $row;
        });

        $totalRecords = $finalResults->count();
        $filteredRecords = $totalRecords;

        $data = [];
        $draw = intval($request->get('draw'));
        foreach ($finalResults as $item) {
            $item['DT_RowIndex'] = count($data) + 1;
            $data[] = $item;
        }

        return response()->json([
            "data" => $data,
        ]);
    }
}
