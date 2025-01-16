<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Models\shift;
use App\Models\absensici;
use App\Models\absensico;
use App\Models\CutiModel;
use App\Jobs\UploadFileJob;
use App\Models\MasterShift;
use App\Events\FileUploaded;
use App\Models\SectionModel;
use Illuminate\Http\Request;
use App\Models\DivisionModel;
use PhpParser\Node\Stmt\Else_;
use App\Models\DepartmentModel;
use App\Models\PenyimpanganModel;
use Illuminate\Support\Facades\DB;
use App\Exports\RekapAbsensiExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\attendanceRecordModel;
use Illuminate\Support\Facades\Artisan;
use Yajra\DataTables\Facades\DataTables;

class rekapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $roleId = $user->role_id;
        $sectionId = $user->section_id;

        $query = User::select('nama', 'npk')->where('status', 1);
        if ($roleId == 2) {
            $query->where('section_id', $sectionId);
        }
        $masterShift = MasterShift::pluck('waktu');

        $userData = $query->get();
        return view('rekap.rekapAbsensi', compact('userData', 'masterShift'));
    }
    // $day = Carbon::parse($checkout->tanggal)->subDay()->format('Y-m-d');  // Mengurangi 1 hari

    //             // Query untuk mendapatkan waktu checkout di hari sebelumnya antara pukul 00:00 dan 10:00
    //             $dayCheckout = Absensico::where('npk', $checkout->npk)
    //                 ->whereDate('tanggal', $day)
    //                 ->whereBetween('waktuco', ['00:00:00', '10:00:00'])
    //                 ->orderBy('tanggal', 'asc')
    //                 ->first();

    public function getData(Request $request)
    {
        set_time_limit(300);
        $today = date('Y-m-d');
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
            ->where(function ($query) use ($startDate, $endDate) {
                // Kondisi untuk waktu dalam rentang 00:00 - 10:00
                $query->whereBetween('waktuco', ['00:00:00', '10:00:00']);

                // Kondisi untuk tanggal dalam range
                if (!empty($startDate) && !empty($endDate)) {
                    $query->where(function ($subQuery) use ($startDate, $endDate) {
                        // Kondisi untuk tanggal start hingga endDate
                        $subQuery->whereBetween('tanggal', [$startDate, $endDate])
                            ->orWhere(function ($orQuery) use ($endDate) {
                                // Tambahkan kondisi untuk endDate + 1 hari
                                $orQuery->whereDate('tanggal', '=', \Carbon\Carbon::parse($endDate)->addDay()->toDateString());
                            });
                    });
                }
            })
            ->groupBy('npk', 'tanggal');


        if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
            $selectedNPK = $request->selectedNpk;
            $checkoutQuery->whereIn('npk', $selectedNPK);
        }

        $checkoutResults = $checkoutQuery->get();
        $results = [];

        $checkoutQueryrange = Absensico::with(['user', 'shift'])
            ->select('npk', 'tanggal', 'waktuco')
            ->where(function ($query) use ($startDate, $endDate) {
                // Kondisi untuk waktu dalam rentang 00:00 - 10:00
                $query->whereBetween('waktuco', ['00:00:00', '10:00:00']);

                // Kondisi untuk tanggal dalam range
                if (!empty($startDate) && !empty($endDate)) {
                    $query->where(function ($subQuery) use ($startDate, $endDate) {
                        // Kondisi untuk tanggal start hingga endDate
                        $subQuery->whereBetween('tanggal', [$startDate, $endDate])
                            ->orWhere(function ($orQuery) use ($endDate) {
                                // Tambahkan kondisi untuk endDate + 1 hari
                                $orQuery->whereDate('tanggal', '=', \Carbon\Carbon::parse($endDate)->addDay()->toDateString());
                            });
                    });
                }
            })
            ->groupBy('npk', 'tanggal', 'waktuco');
        if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
            $selectedNPK = $request->selectedNpk;
            $checkoutQueryrange->whereIn('npk', $selectedNPK);
        }

        $checkoutQueryrange = $checkoutQueryrange->get();

        foreach ($checkinResults as $checkin) {
            $key = "{$checkin->npk}-{$checkin->tanggal}";

            // Ambil informasi section, department, dan division
            $section = $checkin->user ? $checkin->user->section : null;
            $department = $section ? $section->department : null;
            $division = $department ? $department->division : null;

            // Ambil shift
            $latestShift = shift::where('npk', $checkin->npk)
                ->where('date', $checkin->tanggal)
                ->latest()
                ->first();
            $shift1 = $latestShift ? $latestShift->shift1 : null;

            $role = $checkin->user ? $checkin->user->role : null;
            $status = '';
            // Cek jika role adalah 5 atau 8, maka status langsung 'Tepat Waktu'
            if ($role && in_array($role->id, [5, 8])) {
                $status = 'Tepat Waktu';
            } elseif ($latestShift) {
                // Jika shift1 adalah OFF, maka status juga OFF
                if (strtoupper($shift1) === 'OFF') {
                    $status = 'OFF';
                } else {
                    // Jika tidak, cek apakah terlambat atau tepat waktu berdasarkan shift
                    $shiftIn = explode(' - ', str_replace('.', ':', $shift1))[0];
                    $shiftInFormatted = date('H:i:s', strtotime($shiftIn));
                    $status = $checkin->waktuci > $shiftInFormatted ? 'Terlambat' : 'Tepat Waktu';
                }
            }


            // Menggunakan Carbon untuk memastikan tanggal checkout adalah 1 hari setelah tanggal checkin

            // Update hasil
            $results[$key] = [
                'nama' => $checkin->user ? $checkin->user->nama : '',
                'npk' => $checkin->npk,
                'tanggal' => $checkin->tanggal,
                'waktuci' => $checkin->waktuci,
                'waktuco' => null,  // Update waktu checkout dengan hasil yang ditemukan
                'shift1' => $shift1,
                'section_nama' => $section ? $section->nama : '',
                'department_nama' => $department ? $department->nama : '',
                'division_nama' => $division ? $division->nama : '',
                'status' => ($role && in_array($role->id, [4, 5, 8])) ? $status : ($shift1 === null ? 'Mangkir' : ($status ?? 'belum terkondisikan')),
            ];
        }


        $checkoutNpkList = [];
        foreach ($checkoutResults as $checkout) {
            $checkoutNpkList[] = $checkout->npk;
            $key = "{$checkout->npk}-{$checkout->tanggal}";
            $role = $checkout->user ? $checkout->user->role : null;
            // Tentukan status default
            $status = 'NO IN';

            // Jika user dengan role tertentu (misal 5 atau 8), berikan status "Tepat Waktu" secara default
            if ($role && in_array($role->id, [5, 8])) {
                $status = 'Tepat Waktu';
            }

            $latestShift = Shift::where('npk', $checkout->npk)
                ->where('date', $checkout->tanggal)
                ->latest()
                ->first();
            $shift1 = $latestShift ? $latestShift->shift1 : null;

            $latestprevShift = Shift::where('npk', $checkout->npk)
                ->where('date', Carbon::parse($checkout->tanggal)->subDay()->toDateString()) // Mengurangi 1 hari dengan Carbon
                ->latest()
                ->first();
            $shiftprevious = $latestprevShift ? $latestprevShift->shift1 : null;


            if (isset($results[$key])) {
                // Pastikan waktuco tidak null dan menggantikan yang lama
                if ($checkout->waktuco && $results[$key]['waktuco'] != $checkout->waktuco) {
                    $results[$key]['waktuco'] = ($checkout->waktuco >= '00:00:00' && $checkout->waktuco <= '10:00:00') ? null : $checkout->waktuco;
                }
            } else {
                if ($checkout->waktuco >= '00:00:00' && $checkout->waktuco <= '10:00:00') {
                    // dd($checkin);
                    $tanggalMinusOneDay = date('Y-m-d', strtotime($checkout->tanggal . ' -1 day'));
                    $waktuci = null;

                    if (!isset($results[$key])) {
                        $results["{$checkout->npk}-{$checkout->tanggal}"] = [
                            'nama' => $checkout->user ? $checkout->user->nama : '',
                            'npk' => $checkout->npk,
                            'tanggal' => $checkout->tanggal,
                            'waktuci' => null, // Tidak ada check-in
                            'waktuco' => null,
                            'shift1' => $shift1,
                            'section_nama' => $checkout->user && $checkout->user->section ? $checkout->user->section->nama : '',
                            'department_nama' => $checkout->user && $checkout->user->section && $checkout->user->section->department ? $checkout->user->section->department->nama : '',
                            'division_nama' => $checkout->user && $checkout->user->section && $checkout->user->section->department && $checkout->user->section->department->division ? $checkout->user->section->department->division->nama : '',
                            'status' => 'Mangkir',
                        ];
                    }

                    // $results["{$checkout->npk}-{$tanggalMinusOneDay}"]['waktuco'] = $checkout->waktuco;
                } else { // Jika tidak ada data check-in, tetap tambahkan data checkout dengan status "NO IN"
                    $results[$key] = [
                        'nama' => $checkout->user ? $checkout->user->nama : '',
                        'npk' => $checkout->npk,
                        'tanggal' => $checkout->tanggal,
                        'waktuci' => null, // Tidak ada check-in
                        'waktuco' => $checkout->waktuco,
                        'shift1' => $shift1,
                        'section_nama' => $checkout->user && $checkout->user->section ? $checkout->user->section->nama : '',
                        'department_nama' => $checkout->user && $checkout->user->section && $checkout->user->section->department ? $checkout->user->section->department->nama : '',
                        'division_nama' => $checkout->user && $checkout->user->section && $checkout->user->section->department && $checkout->user->section->department->division ? $checkout->user->section->department->division->nama : '',
                        'status' => $status,
                    ];
                }
            }
        }

        foreach ($checkoutQueryrange as $checkoutRange) {


            $latestprevShift = Shift::where('npk', $checkoutRange->npk)
                ->where('date', Carbon::parse($checkoutRange->tanggal)->subDay()->toDateString()) // Mengurangi 1 hari dengan Carbon
                ->latest()
                ->first();
            $shiftprevious = $latestprevShift ? $latestprevShift->shift1 : null;
            $status = 'NO IN';


            $tanggalMinusOneDay = date('Y-m-d', strtotime($checkoutRange->tanggal . ' -1 day'));
            $key = "{$checkoutRange->npk}-{$tanggalMinusOneDay}";
            if (in_array($checkoutRange->npk, $checkoutNpkList)) {
                if (isset($results[$key])) {
                    $results[$key]['waktuco'] = $checkoutRange->waktuco;
                }
            }
            if (!isset($results[$key])) {
                $results[$key] = [
                    'nama' => $checkoutRange->user ? $checkoutRange->user->nama : '', // Ambil nama dari checkoutRange
                    'npk' => $checkoutRange->npk, // Ambil npk dari checkoutRange
                    'tanggal' => $tanggalMinusOneDay, // Gunakan tanggal H-1
                    'waktuci' => null, // Tidak ada check-in
                    'waktuco' => $checkoutRange->waktuco, // Ambil waktuco dari checkoutRange
                    'shift1' => $shiftprevious, // Ambil shift1 dari checkout jika tersedia
                    'section_nama' => $checkoutRange->user && $checkoutRange->user->section ? $checkoutRange->user->section->nama : '',
                    'department_nama' => $checkoutRange->user && $checkoutRange->user->section && $checkoutRange->user->section->department ? $checkoutRange->user->section->department->nama : '',
                    'division_nama' => $checkoutRange->user && $checkoutRange->user->section && $checkoutRange->user->section->department && $checkoutRange->user->section->department->division ? $checkoutRange->user->section->department->division->nama : '',
                    'status' => $status, // Gunakan status dari checkout jika tersedia
                ];
            }
        }


        $noCheckData = shift::with(['user.section.department.division', 'user.role'])
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
                DB::raw('IFNULL(DATE_FORMAT(MAX(absensico.waktuco), "%H:%i"), "NO OUT") as waktuco'),

            )
            ->whereNull('absensici.waktuci')
            ->whereNull('absensico.waktuco')
            ->where('kategorishift.date', '<=', $today)
            ->where('kategorishift.shift1', '!=', 'OFF');

        // Tambahkan filter tanggal jika $startDate dan $endDate tidak kosong
        if (!empty($startDate) && !empty($endDate)) {
            $noCheckData->whereBetween('kategorishift.date', [$startDate, $endDate]);
        }

        // Tambahkan filter NPK jika terdapat NPK yang dipilih
        if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
            $selectedNPK = $request->selectedNpk;
            $noCheckData->whereIn('kategorishift.npk', $selectedNPK);
        }

        $noCheckData = $noCheckData
            ->groupBy('kategorishift.npk', 'kategorishift.date', 'kategorishift.shift1')
            ->get();


        $noCheckData = shift::with(['user.section.department.division', 'user.role'])
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
            ->where('kategorishift.shift1', '!=', 'OFF');

        // Tambahkan filter tanggal jika $startDate dan $endDate tidak kosong
        if (!empty($startDate) && !empty($endDate)) {
            $noCheckData->whereBetween('kategorishift.date', [$startDate, $endDate]);
        }

        // Tambahkan filter NPK jika terdapat NPK yang dipilih
        if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
            $selectedNPK = $request->selectedNpk;
            $noCheckData->whereIn('kategorishift.npk', $selectedNPK);
        }

        $noCheckData = $noCheckData
            ->groupBy('kategorishift.npk', 'kategorishift.date', 'kategorishift.shift1')
            ->get();

        foreach ($noCheckData as $noCheck) {
            $key = "{$noCheck->npk}-{$noCheck->tanggal}";
            $role = $noCheck->user ? $noCheck->user->role : null;

            // Ambil shift terakhir berdasarkan date atau kolom lain yang relevan
            $latestShift = shift::where('npk', $noCheck->npk)
                ->where('date', $noCheck->tanggal)
                ->orderBy('date', 'desc')
                ->latest('created_at') // Jika ada kolom updated_at, tambahkan latest berdasarkan kolom ini
                ->first();
            $shift1 = $latestShift ? $latestShift->shift1 : null;

            // Dapatkan waktu saat ini
            $currentTime = now();
            $shiftStartTime = null;

            // Tentukan waktu mulai shift jika formatnya valid
            if ($shift1 && !in_array($shift1, ['Dinas Luar Stand By', 'OFF']) && strpos($shift1, ' - ') !== false) {
                $shiftTimes = explode(' - ', $shift1);
                if (count($shiftTimes) == 2 && preg_match('/^\d{2}:\d{2}$/', $shiftTimes[0])) {
                    $shiftStartTime = Carbon::createFromFormat('H:i', $shiftTimes[0]);
                } else {
                    $shiftStartTime = null;
                    Log::warning("Format shift tidak valid: " . $shift1);
                }
            }

            // Tentukan status berdasarkan kondisi yang relevan
            if ($shift1 === "OFF") {
                $status = "OFF";
            } else  if ($shift1 === "Dinas Luar Stand By Off") {
                $status = "Dinas Luar Stand By";
            } elseif ($role && in_array($role->id, [5, 8])) {
                $status = 'Tepat Waktu';
            } elseif (!isset($results[$key])) {
                $status = ($shift1 === "Dinas Luar Stand By") ? "Dinas Luar Stand By" : "Mangkir";
            } elseif ($shiftStartTime && $currentTime->gt($shiftStartTime) && $noCheck->waktuci === 'NO IN' && $noCheck->waktuco === 'NO OUT') {
                $status = "Mangkir";
            }


            // Isi array results jika belum ada entri untuk key ini
            if (!isset($results[$key])) {
                $results[$key] = [
                    'nama' => $noCheck->user ? $noCheck->user->nama : '',
                    'npk' => $noCheck->npk,
                    'tanggal' => $noCheck->tanggal,
                    'waktuci' => ($shift1 === "OFF" || $shift1 === "Dinas Luar Stand By Off") ? '----' : 'NO IN',
                    'waktuco' => ($shift1 === "OFF" || $shift1 === "Dinas Luar Stand By Off") ? '----' : 'NO OUT',
                    'shift1' => $shift1,
                    'role' => $role,
                    'section_nama' => $noCheck->user && $noCheck->user->section ? $noCheck->user->section->nama : '',
                    'department_nama' => $noCheck->user && $noCheck->user->section && $noCheck->user->section->department ? $noCheck->user->section->department->nama : '',
                    'division_nama' => $noCheck->user && $noCheck->user->section && $noCheck->user->section->department && $noCheck->user->section->department->division ? $noCheck->user->section->department->division->nama : '',
                    'status' =>  $status,
                ];
            }
        }
        $finalResults = collect(array_values($results))->sortBy('tanggal');
        foreach ($finalResults as $key => $row) {
            $npk = $row['npk'];
            $tanggalMulai = $row['tanggal'] ?? null;

            if (!$tanggalMulai) {
                Log::error("Tanggal tidak ditemukan untuk NPK: $npk");
                continue;
            }

            $user = User::where('npk', $npk)->first();
            $npkSistem = $user->npk_sistem ?? 'tidak ditemukan';

            // Cuti Model
            $cutiModels = CutiModel::where('npk', $npk)
                ->where(function ($query) use ($tanggalMulai) {
                    $query->where('tanggal_mulai', '<=', $tanggalMulai)
                        ->where(function ($query) use ($tanggalMulai) {
                            $query->whereRaw('COALESCE(tanggal_selesai, tanggal_mulai) >= ?', [$tanggalMulai]); // Gunakan COALESCE
                        });
                })
                ->whereIn('approved_by', [2, 3, 4, 5, 8, 10])
                ->get();

            $cutiCount = $cutiModels->count();
            $kategoriCuti = $cutiModels->pluck('kategori')->first();

            // Penyimpangan Model
            $penyimpangan = Penyimpanganmodel::where('npk', $npk)
                ->where(function ($query) use ($tanggalMulai) {
                    $query->where('tanggal_mulai', '<=', $tanggalMulai)
                        ->where(function ($query) use ($tanggalMulai) {
                            $query->whereRaw('COALESCE(tanggal_selesai, tanggal_mulai) >= ?', [$tanggalMulai]); // Gunakan COALESCE
                        });
                })
                ->whereIn('approved_by', [2, 3, 4, 5, 8, 10])
                ->first();

            $penyimpanganCount = $penyimpangan ? 1 : 0;
            $kategoriPenyimpangan = $penyimpangan->kategori ?? null;

            // Implementasi logika untuk status dan API Time
            $apiTime = null;

            // Menambahkan tombol Cuti untuk setiap tanggal dalam rentang cuti
            if ($cutiCount > 0) {
                foreach ($cutiModels as $cuti) {
                    $apiTime .= ' <button class="btn btn-info view-cuti" data-npk="' . $npk . '" data-tanggal="' . $cuti->tanggal_mulai . '">Lihat Cuti</button>';
                }
            }

            // Menambahkan tombol Penyimpangan jika ada
            if ($penyimpanganCount > 0) {
                $apiTime .= '<button class="btn btn-warning view-penyimpangan" data-npk="' . $npk . '" data-tanggal="' . $penyimpangan->tanggal_mulai . '">Lihat Penyimpangan</button>';
            }

            // Memperbarui data
            $finalResults->put($key, array_merge($row, [
                'has_penyimpangan' => $penyimpanganCount > 0,
                'has_cuti' => $cutiCount > 0,
                'api_time' => $apiTime,
                'npk_sistem' => $npkSistem,
                'waktuci' => ($row['shift1'] === 'OFF') ? ($row['waktuci'] ?? '----') : ($row['waktuci'] ?? 'NO IN'),
                'waktuco' => ($row['shift1'] === 'OFF') ? ($row['waktuco'] ?? '----') : ($row['waktuco'] ?? 'NO OUT'),
                'status' => !empty($kategoriCuti) ? $kategoriCuti : (!empty($kategoriPenyimpangan) ? $kategoriPenyimpangan : ($row['shift1'] === 'OFF' ? 'OFF' : $row['status'])),
            ]));
        }
        $data = [];
        foreach ($finalResults as $item) {

            if ($item['waktuci'] === '----' && $item['waktuco'] === '----' && $item['shift1'] === 'OFF') {
                continue;
            }
            if (!empty($startDate) && !empty($endDate)) {
                if ($item['tanggal'] >= $startDate && $item['tanggal'] <= $endDate) {
                    $data[] = $item;
                }
            } else {
                $data[] = $item;
            }
        }

        // dd($data);
        return response()->json([
            "data" => $data,
        ]);
    }


    // public function getData(Request $request)
    // {
    //     set_time_limit(300);
    //     $today = date('Y-m-d');
    //     $startDate = $request->input('startDate');
    //     $endDate = $request->input('endDate');


    //     $checkinQuery = Absensici::with(['user', 'shift'])
    //         ->select('npk', 'tanggal', DB::raw('MIN(waktuci) as waktuci'))
    //         ->groupBy('npk', 'tanggal');

    //     if (!empty($startDate) && !empty($endDate)) {
    //         $checkinQuery->whereBetween('tanggal', [$startDate, $endDate]);
    //     }
    //     if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
    //         $selectedNPK = $request->selectedNpk;
    //         $checkinQuery->whereIn('npk', $selectedNPK);
    //     }

    //     $checkinResults = $checkinQuery->get();

    //     $checkoutQuery = Absensico::with(['user', 'shift'])
    //         ->select('npk', 'tanggal', DB::raw('MAX(waktuco) as waktuco'))
    //         ->groupBy('npk', 'tanggal');

    //     if (!empty($startDate) && !empty($endDate)) {
    //         $checkoutQuery->whereBetween('tanggal', [$startDate, $endDate]);
    //     }
    //     if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
    //         $selectedNPK = $request->selectedNpk;
    //         $checkoutQuery->whereIn('npk', $selectedNPK);
    //     }

    //     $checkoutResults = $checkoutQuery->get();
    //     $results = [];

    //     $checkoutQueryrange = Absensico::with(['user', 'shift'])
    //         ->select('npk', 'tanggal', 'waktuco')
    //         ->whereBetween('waktuco', ['00:00:00', '10:00:00'])  // Filter waktuco dalam rentang 00:00 - 10:00
    //         ->groupBy('npk', 'tanggal', 'waktuco')
    //         ->get();

    //     foreach ($checkinResults as $checkin) {
    //         $key = "{$checkin->npk}-{$checkin->tanggal}";

    //         // Ambil informasi section, department, dan division
    //         $section = $checkin->user ? $checkin->user->section : null;
    //         $department = $section ? $section->department : null;
    //         $division = $department ? $department->division : null;

    //         // Ambil shift
    //         $latestShift = shift::where('npk', $checkin->npk)
    //             ->where('date', $checkin->tanggal)
    //             ->latest()
    //             ->first();
    //         $shift1 = $latestShift ? $latestShift->shift1 : null;

    //         $role = $checkin->user ? $checkin->user->role : null;

    //         // Cek jika role adalah 5 atau 8, maka status langsung 'Tepat Waktu'
    //         if ($role && in_array($role->id, [5, 8])) {
    //             $status = 'Tepat Waktu';
    //         } elseif ($latestShift) {
    //             // Jika tidak, cek apakah terlambat atau tepat waktu berdasarkan shift
    //             $shiftIn = explode(' - ', str_replace('.', ':', $shift1))[0];
    //             $shiftInFormatted = date('H:i:s', strtotime($shiftIn));
    //             $status = $checkin->waktuci > $shiftInFormatted ? 'Terlambat' : 'Tepat Waktu';
    //         }

    //         // Menggunakan Carbon untuk memastikan tanggal checkout adalah 1 hari setelah tanggal checkin

    //         // Update hasil
    //         $results[$key] = [
    //             'nama' => $checkin->user ? $checkin->user->nama : '',
    //             'npk' => $checkin->npk,
    //             'tanggal' => $checkin->tanggal,
    //             'waktuci' => $checkin->waktuci,
    //             'waktuco' => null,  // Update waktu checkout dengan hasil yang ditemukan
    //             'shift1' => $shift1,
    //             'section_nama' => $section ? $section->nama : '',
    //             'department_nama' => $department ? $department->nama : '',
    //             'division_nama' => $division ? $division->nama : '',
    //             'status' => ($role && in_array($role->id, [4, 5, 8])) ? $status : ($shift1 === null ? 'Mangkir' : $status),
    //         ];
    //     }
    //     // Proses sebelumnya untuk $checkoutResults
    //     foreach ($checkoutResults as $checkout) {
    //         $key = "{$checkout->npk}-{$checkout->tanggal}";

    //         // Tentukan status default
    //         $status = 'NO IN';

    //         // Jika user dengan role tertentu (misal 5 atau 8), berikan status "Tepat Waktu" secara default
    //         if ($role && in_array($role->id, [5, 8])) {
    //             $status = 'Tepat Waktu';
    //         }

    //         $latestShift = Shift::where('npk', $checkout->npk)
    //             ->where('date', $checkout->tanggal)
    //             ->latest()
    //             ->first();
    //         $shift1 = $latestShift ? $latestShift->shift1 : null;

    //         // Cek apakah waktu checkout berada di antara 00:00:00 - 10:00:00
    //         if ($checkout->waktuco >= '00:00:00' && $checkout->waktuco <= '10:00:00') {
    //             $checkout->waktuco = null;
    //             if ($role && in_array($role->id, [5, 8])) {
    //                 $status = 'Tepat Waktu';
    //             } else {
    //                 $status = 'Mangkir';
    //             }
    //         }

    //         foreach ($checkoutQueryrange as $checkoutRange) {
    //             $tanggalMinusOneDay = date('Y-m-d', strtotime($checkoutRange->tanggal . ' -1 day'));

    //             if (isset($results["{$checkoutRange->npk}-{$tanggalMinusOneDay}"])) {

    //                 if ($checkoutRange->npk == $checkout->npk) {
    //                     // $checkout->waktuco = $checkoutRange->waktuco;
    //                     $waktucoLembur = $checkoutRange->waktuco;
    //                     $tanggalLembur =  $tanggalMinusOneDay;
    //                     $npkLembur =  $checkoutRange->npk;
    //                     $keyLembur = "{$npkLembur}-{$tanggalLembur}";
    //                     // $checkout->tanggal = $tanggalMinusOneDay;
    //                     $key = "{$checkoutRange->npk}-{$tanggalMinusOneDay}";
    //                 }
    //             }
    //         }

    //         // Update results
    //         if (isset($results[$key])) {
    //             if ($checkout->waktuco && $results[$key]['waktuco'] != $checkout->waktuco) {
    //                 $results[$key]['waktuco'] = $checkout->waktuco;
    //                 if ($waktucoLembur) {
    //                     $results[$key]['waktuco'] = $waktucoLembur;
    //                 } else {
    //                     $results[$key]['waktuco'] = $checkout->waktuco;
    //                 }
    //             }



    //             $key = "{$checkout->npk}-{$checkout->tanggal}";
    //             // $results[$key] = [
    //             //     'nama' => $checkout->user ? $checkout->user->nama : '',
    //             //     'npk' => $checkout->npk,
    //             //     'tanggal' => $checkout->tanggal,
    //             //     'waktuci' => null,
    //             //     'waktuco' =>  $checkout->waktuco,
    //             //     'shift1' => $shift1,
    //             //     'section_nama' => $checkout->user && $checkout->user->section ? $checkout->user->section->nama : '',
    //             //     'department_nama' => $checkout->user && $checkout->user->section && $checkout->user->section->department ? $checkout->user->section->department->nama : '',
    //             //     'division_nama' => $checkout->user && $checkout->user->section && $checkout->user->section->department && $checkout->user->section->department->division ? $checkout->user->section->department->division->nama : '',
    //             //     'status' => $status,
    //             // ];
    //         } else {
    //             $results[$key] = [
    //                 'nama' => $checkout->user ? $checkout->user->nama : '',
    //                 'npk' => $checkout->npk,
    //                 'tanggal' => $checkout->tanggal,
    //                 'waktuci' => null,
    //                 'waktuco' =>  $checkout->waktuco,
    //                 'shift1' => $shift1,
    //                 'section_nama' => $checkout->user && $checkout->user->section ? $checkout->user->section->nama : '',
    //                 'department_nama' => $checkout->user && $checkout->user->section && $checkout->user->section->department ? $checkout->user->section->department->nama : '',
    //                 'division_nama' => $checkout->user && $checkout->user->section && $checkout->user->section->department && $checkout->user->section->department->division ? $checkout->user->section->department->division->nama : '',
    //                 'status' => $status,
    //             ];
    //         }
    //     }




    //     $noCheckData = shift::with(['user.section.department.division', 'user.role'])
    //         ->leftJoin('absensici', function ($join) {
    //             $join->on('absensici.npk', '=', 'kategorishift.npk')
    //                 ->on('absensici.tanggal', '=', 'kategorishift.date');
    //         })
    //         ->leftJoin('absensico', function ($join) {
    //             $join->on('absensico.npk', '=', 'kategorishift.npk')
    //                 ->on('absensico.tanggal', '=', 'kategorishift.date');
    //         })
    //         ->select(
    //             'kategorishift.npk',
    //             'kategorishift.date as tanggal',
    //             'kategorishift.shift1',
    //             DB::raw('IFNULL(DATE_FORMAT(MIN(absensici.waktuci), "%H:%i"), "NO IN") as waktuci'),
    //             DB::raw('IFNULL(DATE_FORMAT(MAX(absensico.waktuco), "%H:%i"), "NO OUT") as waktuco')
    //         )
    //         ->whereNull('absensici.waktuci')
    //         ->whereNull('absensico.waktuco')
    //         ->where('kategorishift.date', '<=', $today)
    //         ->where('kategorishift.shift1', '!=', 'OFF');

    //     // Tambahkan filter tanggal jika $startDate dan $endDate tidak kosong
    //     if (!empty($startDate) && !empty($endDate)) {
    //         $noCheckData->whereBetween('kategorishift.date', [$startDate, $endDate]);
    //     }

    //     // Tambahkan filter NPK jika terdapat NPK yang dipilih
    //     if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
    //         $selectedNPK = $request->selectedNpk;
    //         $noCheckData->whereIn('kategorishift.npk', $selectedNPK);
    //     }

    //     $noCheckData = $noCheckData
    //         ->groupBy('kategorishift.npk', 'kategorishift.date', 'kategorishift.shift1')
    //         ->get();


    //     $noCheckData = shift::with(['user.section.department.division', 'user.role'])
    //         ->leftJoin('absensici', function ($join) {
    //             $join->on('absensici.npk', '=', 'kategorishift.npk')
    //                 ->on('absensici.tanggal', '=', 'kategorishift.date');
    //         })
    //         ->leftJoin('absensico', function ($join) {
    //             $join->on('absensico.npk', '=', 'kategorishift.npk')
    //                 ->on('absensico.tanggal', '=', 'kategorishift.date');
    //         })
    //         ->select(
    //             'kategorishift.npk',
    //             'kategorishift.date as tanggal',
    //             'kategorishift.shift1',
    //             DB::raw('IFNULL(DATE_FORMAT(MIN(absensici.waktuci), "%H:%i"), "NO IN") as waktuci'),
    //             DB::raw('IFNULL(DATE_FORMAT(MAX(absensico.waktuco), "%H:%i"), "NO OUT") as waktuco')
    //         )
    //         ->whereNull('absensici.waktuci')
    //         ->whereNull('absensico.waktuco')
    //         ->where('kategorishift.date', '<=', $today)
    //         ->where('kategorishift.shift1', '!=', 'OFF');

    //     // Tambahkan filter tanggal jika $startDate dan $endDate tidak kosong
    //     if (!empty($startDate) && !empty($endDate)) {
    //         $noCheckData->whereBetween('kategorishift.date', [$startDate, $endDate]);
    //     }

    //     // Tambahkan filter NPK jika terdapat NPK yang dipilih
    //     if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
    //         $selectedNPK = $request->selectedNpk;
    //         $noCheckData->whereIn('kategorishift.npk', $selectedNPK);
    //     }

    //     $noCheckData = $noCheckData
    //         ->groupBy('kategorishift.npk', 'kategorishift.date', 'kategorishift.shift1')
    //         ->get();

    //     foreach ($noCheckData as $noCheck) {
    //         $key = "{$noCheck->npk}-{$noCheck->tanggal}";
    //         $role = $noCheck->user ? $noCheck->user->role : null;

    //         // Ambil shift terakhir berdasarkan date atau kolom lain yang relevan
    //         $latestShift = shift::where('npk', $noCheck->npk)
    //             ->where('date', $noCheck->tanggal)
    //             ->orderBy('date', 'desc')
    //             ->latest('created_at') // Jika ada kolom updated_at, tambahkan latest berdasarkan kolom ini
    //             ->first();
    //         $shift1 = $latestShift ? $latestShift->shift1 : null;

    //         // Dapatkan waktu saat ini
    //         $currentTime = now();
    //         $shiftStartTime = null;

    //         // Tentukan waktu mulai shift jika formatnya valid
    //         if ($shift1 && !in_array($shift1, ['Dinas Luar Stand By', 'OFF']) && strpos($shift1, ' - ') !== false) {
    //             $shiftTimes = explode(' - ', $shift1);
    //             if (count($shiftTimes) == 2 && preg_match('/^\d{2}:\d{2}$/', $shiftTimes[0])) {
    //                 $shiftStartTime = Carbon::createFromFormat('H:i', $shiftTimes[0]);
    //             } else {
    //                 $shiftStartTime = null;
    //                 Log::warning("Format shift tidak valid: " . $shift1);
    //             }
    //         }

    //         // Tentukan status berdasarkan kondisi yang relevan
    //         if ($role && in_array($role->id, [5, 8])) {
    //             $status = 'Tepat Waktu';
    //         } elseif (!isset($results[$key])) {
    //             $status = ($shift1 === "Dinas Luar Stand By") ? "Dinas Luar Stand By" : "Mangkir";
    //         } elseif ($shiftStartTime && $currentTime->gt($shiftStartTime) && $noCheck->waktuci === 'NO IN' && $noCheck->waktuco === 'NO OUT') {
    //             $status = "Mangkir";
    //         }

    //         // Isi array results jika belum ada entri untuk key ini
    //         if (!isset($results[$key])) {
    //             $results[$key] = [
    //                 'nama' => $noCheck->user ? $noCheck->user->nama : '',
    //                 'npk' => $noCheck->npk,
    //                 'tanggal' => $noCheck->tanggal,
    //                 'waktuci' => 'NO IN',
    //                 'waktuco' => 'NO OUT',
    //                 'shift1' => $shift1,
    //                 'role' => $role,
    //                 'section_nama' => $noCheck->user && $noCheck->user->section ? $noCheck->user->section->nama : '',
    //                 'department_nama' => $noCheck->user && $noCheck->user->section && $noCheck->user->section->department ? $noCheck->user->section->department->nama : '',
    //                 'division_nama' => $noCheck->user && $noCheck->user->section && $noCheck->user->section->department && $noCheck->user->section->department->division ? $noCheck->user->section->department->division->nama : '',
    //                 'status' =>  $status,
    //             ];
    //         }
    //     }
    //     $finalResults = collect(array_values($results))->sortBy('tanggal');
    //     foreach ($finalResults as $key => $row) {
    //         $npk = $row['npk'];
    //         $tanggalMulai = $row['tanggal'] ?? null;

    //         if (!$tanggalMulai) {
    //             Log::error("Tanggal tidak ditemukan untuk NPK: $npk");
    //             continue;
    //         }

    //         $user = User::where('npk', $npk)->first();
    //         $npkSistem = $user->npk_sistem ?? 'tidak ditemukan';

    //         // Cuti Model
    //         $cutiModels = CutiModel::where('npk', $npk)
    //             ->where(function ($query) use ($tanggalMulai) {
    //                 $query->where('tanggal_mulai', '<=', $tanggalMulai)
    //                     ->where(function ($query) use ($tanggalMulai) {
    //                         $query->whereRaw('COALESCE(tanggal_selesai, tanggal_mulai) >= ?', [$tanggalMulai]); // Gunakan COALESCE
    //                     });
    //             })
    //             ->whereIn('approved_by', [2, 3, 4, 5, 8, 10])
    //             ->get();

    //         $cutiCount = $cutiModels->count();
    //         $kategoriCuti = $cutiModels->pluck('kategori')->first();

    //         // Penyimpangan Model
    //         $penyimpangan = Penyimpanganmodel::where('npk', $npk)
    //             ->where(function ($query) use ($tanggalMulai) {
    //                 $query->where('tanggal_mulai', '<=', $tanggalMulai)
    //                     ->where(function ($query) use ($tanggalMulai) {
    //                         $query->whereRaw('COALESCE(tanggal_selesai, tanggal_mulai) >= ?', [$tanggalMulai]); // Gunakan COALESCE
    //                     });
    //             })
    //             ->whereIn('approved_by', [2, 3, 4, 5, 8, 10])
    //             ->first();

    //         $penyimpanganCount = $penyimpangan ? 1 : 0;
    //         $kategoriPenyimpangan = $penyimpangan->kategori ?? null;

    //         // Implementasi logika untuk status dan API Time
    //         $apiTime = null;

    //         // Menambahkan tombol Cuti untuk setiap tanggal dalam rentang cuti
    //         if ($cutiCount > 0) {
    //             foreach ($cutiModels as $cuti) {
    //                 $apiTime .= ' <button class="btn btn-info view-cuti" data-npk="' . $npk . '" data-tanggal="' . $cuti->tanggal_mulai . '">Lihat Cuti</button>';
    //             }
    //         }

    //         // Menambahkan tombol Penyimpangan jika ada
    //         if ($penyimpanganCount > 0) {
    //             $apiTime .= '<button class="btn btn-warning view-penyimpangan" data-npk="' . $npk . '" data-tanggal="' . $penyimpangan->tanggal_mulai . '">Lihat Penyimpangan</button>';
    //         }

    //         // Memperbarui data
    //         $finalResults->put($key, array_merge($row, [
    //             'has_penyimpangan' => $penyimpanganCount > 0,
    //             'has_cuti' => $cutiCount > 0,
    //             'api_time' => $apiTime,
    //             'npk_sistem' => $npkSistem,
    //             'waktuci' => $row['waktuci'] ?? 'NO IN',
    //             'waktuco' => $row['waktuco'] ?? 'NO OUT',
    //             'status' => !empty($kategoriCuti) ? $kategoriCuti : (!empty($kategoriPenyimpangan) ? $kategoriPenyimpangan : $row['status']),
    //         ]));
    //     }

    //     $data = [];
    //     foreach ($finalResults as $item) {
    //         $data[] = $item;
    //     }

    //     return response()->json([
    //         "data" => $data,
    //     ]);
    // }

    // public function getData(Request $request)
    // {
    //     set_time_limit(300);
    //     $today = date('Y-m-d');
    //     $startDate = $request->input('startDate');
    //     $endDate = $request->input('endDate');


    //     $checkinQuery = Absensici::with(['user', 'shift'])
    //         ->select('npk', 'tanggal', DB::raw('MIN(waktuci) as waktuci'))
    //         ->groupBy('npk', 'tanggal');

    //     if (!empty($startDate) && !empty($endDate)) {
    //         $checkinQuery->whereBetween('tanggal', [$startDate, $endDate]);
    //     }
    //     if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
    //         $selectedNPK = $request->selectedNpk;
    //         $checkinQuery->whereIn('npk', $selectedNPK);
    //     }

    //     $checkinResults = $checkinQuery->get();

    //     $checkoutQuery = Absensico::with(['user', 'shift'])
    //         ->select('npk', 'tanggal', DB::raw('MAX(waktuco) as waktuco'))
    //         ->groupBy('npk', 'tanggal');

    //     if (!empty($startDate) && !empty($endDate)) {
    //         $checkoutQuery->whereBetween('tanggal', [$startDate, $endDate]);
    //     }
    //     if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
    //         $selectedNPK = $request->selectedNpk;
    //         $checkoutQuery->whereIn('npk', $selectedNPK);
    //     }

    //     $checkoutResults = $checkoutQuery->get();
    //     $results = [];



    //     foreach ($checkinResults as $checkin) {
    //         $key = "{$checkin->npk}-{$checkin->tanggal}";

    //         // Ambil informasi section, department, dan division
    //         $section = $checkin->user ? $checkin->user->section : null;
    //         $department = $section ? $section->department : null;
    //         $division = $department ? $department->division : null;

    //         // Ambil shift
    //         $latestShift = Shift::where('npk', $checkin->npk)
    //             ->where('date', $checkin->tanggal)
    //             ->latest()
    //             ->first();
    //         $shift1 = $latestShift ? $latestShift->shift1 : null;

    //         $role = $checkin->user ? $checkin->user->role : null;

    //         // Cek jika role adalah 5 atau 8, maka status langsung 'Tepat Waktu'
    //         if ($role && in_array($role->id, [5, 8])) {
    //             $status = 'Tepat Waktu';
    //         } elseif ($latestShift) {
    //             // Jika tidak, cek apakah terlambat atau tepat waktu berdasarkan shift
    //             $shiftIn = explode(' - ', str_replace('.', ':', $shift1))[0];
    //             $shiftInFormatted = date('H:i:s', strtotime($shiftIn));
    //             $status = $checkin->waktuci > $shiftInFormatted ? 'Terlambat' : 'Tepat Waktu';
    //         }

    //         // Menggunakan Carbon untuk memastikan tanggal checkout adalah 1 hari setelah tanggal checkin
    //         $nextDay = Carbon::parse($checkin->tanggal)->addDay()->format('Y-m-d');

    //         // Cari waktuco pada tanggal berikutnya (1 hari setelah tanggal checkin) antara pukul 00:00 dan 10:00
    //         $nextDayCheckout = Absensico::where('npk', $checkin->npk)
    //             ->whereDate('tanggal', $nextDay)  // Memastikan tanggalnya tepat 1 hari setelah tanggal checkin
    //             ->whereBetween(DB::raw('HOUR(waktuco)'), [0, 10])
    //             ->orderBy('tanggal', 'asc')
    //             ->first();

    //         // Jika tidak ada waktu checkout, tetapkan waktuco sebagai null
    //         $waktuco = $nextDayCheckout ? $nextDayCheckout->waktuco : null;

    //         // Update hasil
    //         $results[$key] = [
    //             'nama' => $checkin->user ? $checkin->user->nama : '',
    //             'npk' => $checkin->npk,
    //             'tanggal' => $checkin->tanggal,
    //             'waktuci' => $checkin->waktuci ? $checkin->waktuci : 'NO IN',
    //             'waktuco' => $waktuco,  // Update waktu checkout dengan hasil yang ditemukan
    //             'shift1' => $shift1,
    //             'section_nama' => $section ? $section->nama : '',
    //             'department_nama' => $department ? $department->nama : '',
    //             'division_nama' => $division ? $division->nama : '',
    //             'status' => ($role && in_array($role->id, [4, 5, 8])) ? $status : ($shift1 === null ? 'Mangkir' : $status),
    //         ];
    //     }



    //     // foreach ($checkoutResults as $checkout) {
    //     //     $key = "{$checkout->npk}-{$checkout->tanggal}";
    //     //     $role = $checkout->user ? $checkout->user->role : null;

    //     //     // Tentukan status default
    //     //     $status = 'NO IN';

    //     //     // Jika user dengan role tertentu (misal 5 atau 8), berikan status "Tepat Waktu" secara default
    //     //     if ($role && in_array($role->id, [5, 8])) {
    //     //         $status = 'Tepat Waktu';
    //     //     }

    //     //     $latestShift = Shift::where('npk', $checkout->npk)
    //     //         ->where('date', $checkout->tanggal)
    //     //         ->latest()
    //     //         ->first();
    //     //     $shift1 = $latestShift ? $latestShift->shift1 : null;

    //     //     // Cek apakah ada check-in untuk tanggal ini
    //     //     if (isset($results[$key])) {
    //     //         // Jika sudah ada waktu check-in dan waktuco sudah ada, jangan update waktuco di checkoutResults
    //     //         if (isset($results[$key]['waktuco']) && $results[$key]['waktuco'] !== null) {
    //     //             // Tidak update waktuco jika sudah ada
    //     //             $results[$key]['waktuco'] = $results[$key]['waktuco'];
    //     //         } else {
    //     //             // Jika waktu check-out antara jam 00:00 hingga 10:00, set status ke "NO OUT" dan waktuco menjadi "NO OUT"
    //     //             $checkoutTime = strtotime($checkout->waktuco);
    //     //             if ($checkoutTime >= strtotime('00:00') && $checkoutTime <= strtotime('10:00')) {
    //     //                 $status = 'NO OUT';
    //     //                 $results[$key]['waktuco'] = 'NO OUT';  // Set waktuco menjadi "NO OUT"
    //     //             } else {
    //     //                 $results[$key]['waktuco'] = $checkout->waktuco;
    //     //             }
    //     //         }
    //     //     } else {
    //     //         $checkoutTime = strtotime($checkout->waktuco);
    //     //         if ($checkoutTime >= strtotime('00:00') && $checkoutTime <= strtotime('10:00')) {
    //     //             $status = 'NO OUT';
    //     //             $results[$key]['waktuco'] = 'NO OUT';  // Set waktuco menjadi "NO OUT"
    //     //         } else {
    //     //             $results[$key]['waktuco'] = $checkout->waktuco;
    //     //         }

    //     //         // Tambahkan data checkout
    //     //         $results[$key] = [
    //     //             'nama' => $checkout->user ? $checkout->user->nama : '',
    //     //             'npk' => $checkout->npk,
    //     //             'tanggal' => $checkout->tanggal,
    //     //             'waktuci' => null, // Tidak ada check-in
    //     //             'waktuco' => $results[$key]['waktuco'],  // Menetapkan waktuco yang sesuai
    //     //             'shift1' => $shift1,
    //     //             'section_nama' => $checkout->user && $checkout->user->section ? $checkout->user->section->nama : '',
    //     //             'department_nama' => $checkout->user && $checkout->user->section && $checkout->user->section->department ? $checkout->user->section->department->nama : '',
    //     //             'division_nama' => $checkout->user && $checkout->user->section && $checkout->user->section->department && $checkout->user->section->department->division ? $checkout->user->section->department->division->nama : '',
    //     //             'status' => $status,
    //     //         ];
    //     //     }
    //     // }



    //     // $noCheckData = shift::with(['user.section.department.division', 'user.role'])
    //     //     ->leftJoin('absensici', function ($join) {
    //     //         $join->on('absensici.npk', '=', 'kategorishift.npk')
    //     //             ->on('absensici.tanggal', '=', 'kategorishift.date');
    //     //     })
    //     //     ->leftJoin('absensico', function ($join) {
    //     //         $join->on('absensico.npk', '=', 'kategorishift.npk')
    //     //             ->on('absensico.tanggal', '=', 'kategorishift.date');
    //     //     })
    //     //     ->select(
    //     //         'kategorishift.npk',
    //     //         'kategorishift.date as tanggal',
    //     //         'kategorishift.shift1',
    //     //         DB::raw('IFNULL(DATE_FORMAT(MIN(absensici.waktuci), "%H:%i"), "NO IN") as waktuci'),
    //     //         DB::raw('IFNULL(DATE_FORMAT(MAX(absensico.waktuco), "%H:%i"), "NO OUT") as waktuco')
    //     //     )
    //     //     ->whereNull('absensici.waktuci')
    //     //     ->whereNull('absensico.waktuco')
    //     //     ->where('kategorishift.date', '<=', $today)
    //     //     ->where('kategorishift.shift1', '!=', 'OFF');

    //     // // Tambahkan filter tanggal jika $startDate dan $endDate tidak kosong
    //     // if (!empty($startDate) && !empty($endDate)) {
    //     //     $noCheckData->whereBetween('kategorishift.date', [$startDate, $endDate]);
    //     // }

    //     // // Tambahkan filter NPK jika terdapat NPK yang dipilih
    //     // if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
    //     //     $selectedNPK = $request->selectedNpk;
    //     //     $noCheckData->whereIn('kategorishift.npk', $selectedNPK);
    //     // }

    //     // $noCheckData = $noCheckData
    //     //     ->groupBy('kategorishift.npk', 'kategorishift.date', 'kategorishift.shift1')
    //     //     ->get();


    //     // $noCheckData = shift::with(['user.section.department.division', 'user.role'])
    //     //     ->leftJoin('absensici', function ($join) {
    //     //         $join->on('absensici.npk', '=', 'kategorishift.npk')
    //     //             ->on('absensici.tanggal', '=', 'kategorishift.date');
    //     //     })
    //     //     ->leftJoin('absensico', function ($join) {
    //     //         $join->on('absensico.npk', '=', 'kategorishift.npk')
    //     //             ->on('absensico.tanggal', '=', 'kategorishift.date');
    //     //     })
    //     //     ->select(
    //     //         'kategorishift.npk',
    //     //         'kategorishift.date as tanggal',
    //     //         'kategorishift.shift1',
    //     //         DB::raw('IFNULL(DATE_FORMAT(MIN(absensici.waktuci), "%H:%i"), "NO IN") as waktuci'),
    //     //         DB::raw('IFNULL(DATE_FORMAT(MAX(absensico.waktuco), "%H:%i"), "NO OUT") as waktuco')
    //     //     )
    //     //     ->whereNull('absensici.waktuci')
    //     //     ->whereNull('absensico.waktuco')
    //     //     ->where('kategorishift.date', '<=', $today)
    //     //     ->where('kategorishift.shift1', '!=', 'OFF');

    //     // // Tambahkan filter tanggal jika $startDate dan $endDate tidak kosong
    //     // if (!empty($startDate) && !empty($endDate)) {
    //     //     $noCheckData->whereBetween('kategorishift.date', [$startDate, $endDate]);
    //     // }

    //     // // Tambahkan filter NPK jika terdapat NPK yang dipilih
    //     // if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
    //     //     $selectedNPK = $request->selectedNpk;
    //     //     $noCheckData->whereIn('kategorishift.npk', $selectedNPK);
    //     // }

    //     // $noCheckData = $noCheckData
    //     //     ->groupBy('kategorishift.npk', 'kategorishift.date', 'kategorishift.shift1')
    //     //     ->get();

    //     // foreach ($noCheckData as $noCheck) {
    //     //     $key = "{$noCheck->npk}-{$noCheck->tanggal}";
    //     //     $role = $noCheck->user ? $noCheck->user->role : null;

    //     //     // Ambil shift terakhir berdasarkan date atau kolom lain yang relevan
    //     //     $latestShift = shift::where('npk', $noCheck->npk)
    //     //         ->where('date', $noCheck->tanggal)
    //     //         ->orderBy('date', 'desc')
    //     //         ->latest('created_at') // Jika ada kolom updated_at, tambahkan latest berdasarkan kolom ini
    //     //         ->first();
    //     //     $shift1 = $latestShift ? $latestShift->shift1 : null;

    //     //     // Dapatkan waktu saat ini
    //     //     $currentTime = now();
    //     //     $shiftStartTime = null;

    //     //     // Tentukan waktu mulai shift jika formatnya valid
    //     //     if ($shift1 && !in_array($shift1, ['Dinas Luar Stand By', 'OFF']) && strpos($shift1, ' - ') !== false) {
    //     //         $shiftTimes = explode(' - ', $shift1);
    //     //         if (count($shiftTimes) == 2 && preg_match('/^\d{2}:\d{2}$/', $shiftTimes[0])) {
    //     //             $shiftStartTime = Carbon::createFromFormat('H:i', $shiftTimes[0]);
    //     //         } else {
    //     //             $shiftStartTime = null;
    //     //             Log::warning("Format shift tidak valid: " . $shift1);
    //     //         }
    //     //     }

    //     //     // Tentukan status berdasarkan kondisi yang relevan
    //     //     if ($role && in_array($role->id, [5, 8])) {
    //     //         $status = 'Tepat Waktu';
    //     //     } elseif (!isset($results[$key])) {
    //     //         $status = ($shift1 === "Dinas Luar Stand By") ? "Dinas Luar Stand By" : "Mangkir";
    //     //     } elseif ($shiftStartTime && $currentTime->gt($shiftStartTime) && $noCheck->waktuci === 'NO IN' && $noCheck->waktuco === 'NO OUT') {
    //     //         $status = "Mangkir";
    //     //     }

    //     //     // Isi array results jika belum ada entri untuk key ini
    //     //     if (!isset($results[$key])) {
    //     //         $results[$key] = [
    //     //             'nama' => $noCheck->user ? $noCheck->user->nama : '',
    //     //             'npk' => $noCheck->npk,
    //     //             'tanggal' => $noCheck->tanggal,
    //     //             'waktuci' => 'NO IN',
    //     //             'waktuco' => 'NO OUT',
    //     //             'shift1' => $shift1,
    //     //             'role' => $role,
    //     //             'section_nama' => $noCheck->user && $noCheck->user->section ? $noCheck->user->section->nama : '',
    //     //             'department_nama' => $noCheck->user && $noCheck->user->section && $noCheck->user->section->department ? $noCheck->user->section->department->nama : '',
    //     //             'division_nama' => $noCheck->user && $noCheck->user->section && $noCheck->user->section->department && $noCheck->user->section->department->division ? $noCheck->user->section->department->division->nama : '',
    //     //             'status' =>  $status,
    //     //         ];
    //     //     }
    //     // }
    //     $finalResults = collect(array_values($results))->sortBy('tanggal');
    //     foreach ($finalResults as $key => $row) {
    //         $npk = $row['npk'];
    //         $tanggalMulai = $row['tanggal'] ?? null;

    //         if (!$tanggalMulai) {
    //             Log::error("Tanggal tidak ditemukan untuk NPK: $npk");
    //             continue;
    //         }

    //         $user = User::where('npk', $npk)->first();
    //         $npkSistem = $user->npk_sistem ?? 'tidak ditemukan';

    //         // Cuti Model
    //         $cutiModels = CutiModel::where('npk', $npk)
    //             ->where(function ($query) use ($tanggalMulai) {
    //                 $query->where('tanggal_mulai', '<=', $tanggalMulai)
    //                     ->where(function ($query) use ($tanggalMulai) {
    //                         $query->whereRaw('COALESCE(tanggal_selesai, tanggal_mulai) >= ?', [$tanggalMulai]); // Gunakan COALESCE
    //                     });
    //             })
    //             ->whereIn('approved_by', [2, 3, 4, 5, 8, 10])
    //             ->get();

    //         $cutiCount = $cutiModels->count();
    //         $kategoriCuti = $cutiModels->pluck('kategori')->first();

    //         // Penyimpangan Model
    //         $penyimpangan = Penyimpanganmodel::where('npk', $npk)
    //             ->where(function ($query) use ($tanggalMulai) {
    //                 $query->where('tanggal_mulai', '<=', $tanggalMulai)
    //                     ->where(function ($query) use ($tanggalMulai) {
    //                         $query->whereRaw('COALESCE(tanggal_selesai, tanggal_mulai) >= ?', [$tanggalMulai]); // Gunakan COALESCE
    //                     });
    //             })
    //             ->whereIn('approved_by', [2, 3, 4, 5, 8, 10])
    //             ->first();

    //         $penyimpanganCount = $penyimpangan ? 1 : 0;
    //         $kategoriPenyimpangan = $penyimpangan->kategori ?? null;

    //         // Implementasi logika untuk status dan API Time
    //         $apiTime = null;

    //         // Menambahkan tombol Cuti untuk setiap tanggal dalam rentang cuti
    //         if ($cutiCount > 0) {
    //             foreach ($cutiModels as $cuti) {
    //                 $apiTime .= ' <button class="btn btn-info view-cuti" data-npk="' . $npk . '" data-tanggal="' . $cuti->tanggal_mulai . '">Lihat Cuti</button>';
    //             }
    //         }

    //         // Menambahkan tombol Penyimpangan jika ada
    //         if ($penyimpanganCount > 0) {
    //             $apiTime .= '<button class="btn btn-warning view-penyimpangan" data-npk="' . $npk . '" data-tanggal="' . $penyimpangan->tanggal_mulai . '">Lihat Penyimpangan</button>';
    //         }

    //         // Memperbarui data
    //         $finalResults->put($key, array_merge($row, [
    //             'has_penyimpangan' => $penyimpanganCount > 0,
    //             'has_cuti' => $cutiCount > 0,
    //             'api_time' => $apiTime,
    //             'npk_sistem' => $npkSistem,
    //             'waktuci' => $row['waktuci'] ?? 'NO IN',
    //             'waktuco' => $row['waktuco'] ?? 'NO OUT',
    //             'status' => !empty($kategoriCuti) ? $kategoriCuti : (!empty($kategoriPenyimpangan) ? $kategoriPenyimpangan : $row['status']),
    //         ]));
    //     }

    //     $data = [];
    //     foreach ($finalResults as $item) {
    //         $data[] = $item;
    //     }

    //     return response()->json([
    //         "data" => $data,
    //     ]);
    // }
    // public function getData(Request $request)
    // {
    //     set_time_limit(300);
    //     $today = date('Y-m-d');
    //     $startDate = $request->input('startDate');
    //     $endDate = $request->input('endDate');


    //     $checkinQuery = Absensici::with(['user', 'shift'])
    //         ->select('npk', 'tanggal', DB::raw('MIN(waktuci) as waktuci'))
    //         ->groupBy('npk', 'tanggal');

    //     if (!empty($startDate) && !empty($endDate)) {
    //         $checkinQuery->whereBetween('tanggal', [$startDate, $endDate]);
    //     }
    //     if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
    //         $selectedNPK = $request->selectedNpk;
    //         $checkinQuery->whereIn('npk', $selectedNPK);
    //     }

    //     $checkinResults = $checkinQuery->get();

    //     $checkoutQuery = Absensico::with(['user', 'shift'])
    //         ->select('npk', 'tanggal', DB::raw('MAX(waktuco) as waktuco'))
    //         ->groupBy('npk', 'tanggal');

    //     if (!empty($startDate) && !empty($endDate)) {
    //         $checkoutQuery->whereBetween('tanggal', [$startDate, $endDate]);
    //     }
    //     if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
    //         $selectedNPK = $request->selectedNpk;
    //         $checkoutQuery->whereIn('npk', $selectedNPK);
    //     }

    //     $checkoutResults = $checkoutQuery->get();
    //     $results = [];


    //     foreach ($checkinResults as $checkin) {
    //         $key = "{$checkin->npk}-{$checkin->tanggal}";

    //         // Ambil informasi section, department, dan division
    //         $section = $checkin->user ? $checkin->user->section : null;
    //         $department = $section ? $section->department : null;
    //         $division = $department ? $department->division : null;

    //         // Ambil shift
    //         $latestShift = shift::where('npk', $checkin->npk)
    //             ->where('date', $checkin->tanggal)
    //             ->latest()
    //             ->first();
    //         $shift1 = $latestShift ? $latestShift->shift1 : null;

    //         $role = $checkin->user ? $checkin->user->role : null;

    //         // Cek jika role adalah 5 atau 8, maka status langsung 'Tepat Waktu'
    //         if ($role && in_array($role->id, [5, 8])) {
    //             $status = 'Tepat Waktu';
    //         } elseif ($latestShift) {
    //             // Jika tidak, cek apakah terlambat atau tepat waktu berdasarkan shift
    //             $shiftIn = explode(' - ', str_replace('.', ':', $shift1))[0];
    //             $shiftInFormatted = date('H:i:s', strtotime($shiftIn));
    //             $status = $checkin->waktuci > $shiftInFormatted ? 'Terlambat' : 'Tepat Waktu';
    //         }
    //         $results[$key] = [
    //             'nama' => $checkin->user ? $checkin->user->nama : '',
    //             'npk' => $checkin->npk,
    //             'tanggal' => $checkin->tanggal,
    //             'waktuci' => $checkin->waktuci,
    //             'waktuco' => null,  // Update waktu checkout dengan hasil yang ditemukan
    //             'shift1' => $shift1,
    //             'section_nama' => $section ? $section->nama : '',
    //             'department_nama' => $department ? $department->nama : '',
    //             'division_nama' => $division ? $division->nama : '',
    //             'status' => ($role && in_array($role->id, [4, 5, 8])) ? $status : ($shift1 === null ? 'Mangkir' : $status),
    //         ];
    //     }
    //     foreach ($checkoutResults as $checkout) {
    //         $key = "{$checkout->npk}-{$checkout->tanggal}";
    //         $role = $checkout->user ? $checkout->user->role : null;

    //         // Tentukan status default
    //         $status = 'NO IN';

    //         // Jika user dengan role tertentu (misal 5 atau 8), berikan status "Tepat Waktu" secara default
    //         if ($role && in_array($role->id, [5, 8])) {
    //             $status = 'Tepat Waktu';
    //         }

    //         $latestShift = Shift::where('npk', $checkout->npk)
    //             ->where('date', $checkout->tanggal)
    //             ->latest()
    //             ->first();
    //         $shift1 = $latestShift ? $latestShift->shift1 : null;

    //         // Cek apakah ada check-in untuk tanggal ini
    //         if (isset($results[$key])) {
    //             // Update waktu check-out untuk hari ini
    //             $results[$key]['waktuco'] = $checkout->waktuco;
    //         } else {
    //             // Jika tidak ada data check-in, tetap tambahkan data checkout dengan status "NO IN"
    //             $results[$key] = [
    //                 'nama' => $checkout->user ? $checkout->user->nama : '',
    //                 'npk' => $checkout->npk,
    //                 'tanggal' => $checkout->tanggal,
    //                 'waktuci' => null, // Tidak ada check-in
    //                 'waktuco' => $checkout->waktuco,
    //                 'shift1' => $shift1,
    //                 'section_nama' => $checkout->user && $checkout->user->section ? $checkout->user->section->nama : '',
    //                 'department_nama' => $checkout->user && $checkout->user->section && $checkout->user->section->department ? $checkout->user->section->department->nama : '',
    //                 'division_nama' => $checkout->user && $checkout->user->section && $checkout->user->section->department && $checkout->user->section->department->division ? $checkout->user->section->department->division->nama : '',
    //                 'status' => $status,
    //             ];
    //         }

    //         // Menambahkan logika untuk tanggal checkout jika waktunya antara 00:00 dan 10:00
    //         if ($checkout->waktuco && Carbon::parse($checkout->waktuco)->hour < 10) {
    //             // Jika waktu checkout antara jam 00:00 dan 10:00, kurangi 1 hari
    //             $day = Carbon::parse($checkout->tanggal)->subDay()->format('Y-m-d');  // Mengurangi 1 hari

    //             // Query untuk mendapatkan waktu checkout di hari sebelumnya antara pukul 00:00 dan 10:00
    //             $dayCheckout = Absensico::where('npk', $checkout->npk)
    //                 ->whereDate('tanggal', $day)
    //                 ->whereBetween('waktuco', ['00:00:00', '10:00:00'])
    //                 ->orderBy('tanggal', 'asc')
    //                 ->first();

    //             // Ambil waktuco dari query jika ada
    //             $waktuco = $dayCheckout ? $dayCheckout->waktuco : null;

    //             if ($waktuco) {
    //                 $previousKey = "{$checkout->npk}-{$day}";
    //                 if (isset($results[$previousKey])) {
    //                     $results[$previousKey]['waktuco'] = $waktuco;
    //                 } else {
    //                     $results[$previousKey] = [
    //                         'nama' => $checkout->user ? $checkout->user->nama : '',
    //                         'npk' => $checkout->npk,
    //                         'tanggal' => $day,
    //                         'waktuci' => null,
    //                         'waktuco' => $waktuco,
    //                         'shift1' => null,
    //                         'section_nama' => $checkout->user && $checkout->user->section ? $checkout->user->section->nama : '',
    //                         'department_nama' => $checkout->user && $checkout->user->section && $checkout->user->section->department ? $checkout->user->section->department->nama : '',
    //                         'division_nama' => $checkout->user && $checkout->user->section && $checkout->user->section->department && $checkout->user->section->department->division ? $checkout->user->section->department->division->nama : '',
    //                         'status' => ($role && in_array($role->id, [4, 5, 8])) ? $status : ($shift1 === null ? 'Mangkir' : $status),
    //                     ];
    //                 }
    //             }
    //         }
    //     }


    //     $noCheckData = shift::with(['user.section.department.division', 'user.role'])
    //         ->leftJoin('absensici', function ($join) {
    //             $join->on('absensici.npk', '=', 'kategorishift.npk')
    //                 ->on('absensici.tanggal', '=', 'kategorishift.date');
    //         })
    //         ->leftJoin('absensico', function ($join) {
    //             $join->on('absensico.npk', '=', 'kategorishift.npk')
    //                 ->on('absensico.tanggal', '=', 'kategorishift.date');
    //         })
    //         ->select(
    //             'kategorishift.npk',
    //             'kategorishift.date as tanggal',
    //             'kategorishift.shift1',
    //             DB::raw('IFNULL(DATE_FORMAT(MIN(absensici.waktuci), "%H:%i"), "NO IN") as waktuci'),
    //             DB::raw('IFNULL(DATE_FORMAT(MAX(absensico.waktuco), "%H:%i"), "NO OUT") as waktuco')
    //         )
    //         ->whereNull('absensici.waktuci')
    //         ->whereNull('absensico.waktuco')
    //         ->where('kategorishift.date', '<=', $today)
    //         ->where('kategorishift.shift1', '!=', 'OFF');

    //     // Tambahkan filter tanggal jika $startDate dan $endDate tidak kosong
    //     if (!empty($startDate) && !empty($endDate)) {
    //         $noCheckData->whereBetween('kategorishift.date', [$startDate, $endDate]);
    //     }

    //     // Tambahkan filter NPK jika terdapat NPK yang dipilih
    //     if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
    //         $selectedNPK = $request->selectedNpk;
    //         $noCheckData->whereIn('kategorishift.npk', $selectedNPK);
    //     }

    //     $noCheckData = $noCheckData
    //         ->groupBy('kategorishift.npk', 'kategorishift.date', 'kategorishift.shift1')
    //         ->get();


    //     $noCheckData = shift::with(['user.section.department.division', 'user.role'])
    //         ->leftJoin('absensici', function ($join) {
    //             $join->on('absensici.npk', '=', 'kategorishift.npk')
    //                 ->on('absensici.tanggal', '=', 'kategorishift.date');
    //         })
    //         ->leftJoin('absensico', function ($join) {
    //             $join->on('absensico.npk', '=', 'kategorishift.npk')
    //                 ->on('absensico.tanggal', '=', 'kategorishift.date');
    //         })
    //         ->select(
    //             'kategorishift.npk',
    //             'kategorishift.date as tanggal',
    //             'kategorishift.shift1',
    //             DB::raw('IFNULL(DATE_FORMAT(MIN(absensici.waktuci), "%H:%i"), "NO IN") as waktuci'),
    //             DB::raw('IFNULL(DATE_FORMAT(MAX(absensico.waktuco), "%H:%i"), "NO OUT") as waktuco')
    //         )
    //         ->whereNull('absensici.waktuci')
    //         ->whereNull('absensico.waktuco')
    //         ->where('kategorishift.date', '<=', $today)
    //         ->where('kategorishift.shift1', '!=', 'OFF');

    //     // Tambahkan filter tanggal jika $startDate dan $endDate tidak kosong
    //     if (!empty($startDate) && !empty($endDate)) {
    //         $noCheckData->whereBetween('kategorishift.date', [$startDate, $endDate]);
    //     }

    //     // Tambahkan filter NPK jika terdapat NPK yang dipilih
    //     if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
    //         $selectedNPK = $request->selectedNpk;
    //         $noCheckData->whereIn('kategorishift.npk', $selectedNPK);
    //     }

    //     $noCheckData = $noCheckData
    //         ->groupBy('kategorishift.npk', 'kategorishift.date', 'kategorishift.shift1')
    //         ->get();

    //     foreach ($noCheckData as $noCheck) {
    //         $key = "{$noCheck->npk}-{$noCheck->tanggal}";
    //         $role = $noCheck->user ? $noCheck->user->role : null;

    //         // Ambil shift terakhir berdasarkan date atau kolom lain yang relevan
    //         $latestShift = shift::where('npk', $noCheck->npk)
    //             ->where('date', $noCheck->tanggal)
    //             ->orderBy('date', 'desc')
    //             ->latest('created_at') // Jika ada kolom updated_at, tambahkan latest berdasarkan kolom ini
    //             ->first();
    //         $shift1 = $latestShift ? $latestShift->shift1 : null;

    //         // Dapatkan waktu saat ini
    //         $currentTime = now();
    //         $shiftStartTime = null;

    //         // Tentukan waktu mulai shift jika formatnya valid
    //         if ($shift1 && !in_array($shift1, ['Dinas Luar Stand By', 'OFF']) && strpos($shift1, ' - ') !== false) {
    //             $shiftTimes = explode(' - ', $shift1);
    //             if (count($shiftTimes) == 2 && preg_match('/^\d{2}:\d{2}$/', $shiftTimes[0])) {
    //                 $shiftStartTime = Carbon::createFromFormat('H:i', $shiftTimes[0]);
    //             } else {
    //                 $shiftStartTime = null;
    //                 Log::warning("Format shift tidak valid: " . $shift1);
    //             }
    //         }

    //         // Tentukan status berdasarkan kondisi yang relevan
    //         if ($role && in_array($role->id, [5, 8])) {
    //             $status = 'Tepat Waktu';
    //         } elseif (!isset($results[$key])) {
    //             $status = ($shift1 === "Dinas Luar Stand By") ? "Dinas Luar Stand By" : "Mangkir";
    //         } elseif ($shiftStartTime && $currentTime->gt($shiftStartTime) && $noCheck->waktuci === 'NO IN' && $noCheck->waktuco === 'NO OUT') {
    //             $status = "Mangkir";
    //         }

    //         // Isi array results jika belum ada entri untuk key ini
    //         if (!isset($results[$key])) {
    //             $results[$key] = [
    //                 'nama' => $noCheck->user ? $noCheck->user->nama : '',
    //                 'npk' => $noCheck->npk,
    //                 'tanggal' => $noCheck->tanggal,
    //                 'waktuci' => 'NO IN',
    //                 'waktuco' => 'NO OUT',
    //                 'shift1' => $shift1,
    //                 'role' => $role,
    //                 'section_nama' => $noCheck->user && $noCheck->user->section ? $noCheck->user->section->nama : '',
    //                 'department_nama' => $noCheck->user && $noCheck->user->section && $noCheck->user->section->department ? $noCheck->user->section->department->nama : '',
    //                 'division_nama' => $noCheck->user && $noCheck->user->section && $noCheck->user->section->department && $noCheck->user->section->department->division ? $noCheck->user->section->department->division->nama : '',
    //                 'status' =>  $status,
    //             ];
    //         }
    //     }
    //     $finalResults = collect(array_values($results))->sortBy('tanggal');
    //     foreach ($finalResults as $key => $row) {
    //         $npk = $row['npk'];
    //         $tanggalMulai = $row['tanggal'] ?? null;

    //         if (!$tanggalMulai) {
    //             Log::error("Tanggal tidak ditemukan untuk NPK: $npk");
    //             continue;
    //         }

    //         $user = User::where('npk', $npk)->first();
    //         $npkSistem = $user->npk_sistem ?? 'tidak ditemukan';

    //         // Cuti Model
    //         $cutiModels = CutiModel::where('npk', $npk)
    //             ->where(function ($query) use ($tanggalMulai) {
    //                 $query->where('tanggal_mulai', '<=', $tanggalMulai)
    //                     ->where(function ($query) use ($tanggalMulai) {
    //                         $query->whereRaw('COALESCE(tanggal_selesai, tanggal_mulai) >= ?', [$tanggalMulai]); // Gunakan COALESCE
    //                     });
    //             })
    //             ->whereIn('approved_by', [2, 3, 4, 5, 8, 10])
    //             ->get();

    //         $cutiCount = $cutiModels->count();
    //         $kategoriCuti = $cutiModels->pluck('kategori')->first();

    //         // Penyimpangan Model
    //         $penyimpangan = Penyimpanganmodel::where('npk', $npk)
    //             ->where(function ($query) use ($tanggalMulai) {
    //                 $query->where('tanggal_mulai', '<=', $tanggalMulai)
    //                     ->where(function ($query) use ($tanggalMulai) {
    //                         $query->whereRaw('COALESCE(tanggal_selesai, tanggal_mulai) >= ?', [$tanggalMulai]); // Gunakan COALESCE
    //                     });
    //             })
    //             ->whereIn('approved_by', [2, 3, 4, 5, 8, 10])
    //             ->first();

    //         $penyimpanganCount = $penyimpangan ? 1 : 0;
    //         $kategoriPenyimpangan = $penyimpangan->kategori ?? null;

    //         // Implementasi logika untuk status dan API Time
    //         $apiTime = null;

    //         // Menambahkan tombol Cuti untuk setiap tanggal dalam rentang cuti
    //         if ($cutiCount > 0) {
    //             foreach ($cutiModels as $cuti) {
    //                 $apiTime .= ' <button class="btn btn-info view-cuti" data-npk="' . $npk . '" data-tanggal="' . $cuti->tanggal_mulai . '">Lihat Cuti</button>';
    //             }
    //         }

    //         // Menambahkan tombol Penyimpangan jika ada
    //         if ($penyimpanganCount > 0) {
    //             $apiTime .= '<button class="btn btn-warning view-penyimpangan" data-npk="' . $npk . '" data-tanggal="' . $penyimpangan->tanggal_mulai . '">Lihat Penyimpangan</button>';
    //         }

    //         // Memperbarui data
    //         $finalResults->put($key, array_merge($row, [
    //             'has_penyimpangan' => $penyimpanganCount > 0,
    //             'has_cuti' => $cutiCount > 0,
    //             'api_time' => $apiTime,
    //             'npk_sistem' => $npkSistem,
    //             'waktuci' => $row['waktuci'] ?? 'NO IN',
    //             'waktuco' => $row['waktuco'] ?? 'NO OUT',
    //             'status' => !empty($kategoriCuti) ? $kategoriCuti : (!empty($kategoriPenyimpangan) ? $kategoriPenyimpangan : $row['status']),
    //         ]));
    //     }

    //     $data = [];
    //     foreach ($finalResults as $item) {
    //         $data[] = $item;
    //     }

    //     return response()->json([
    //         "data" => $data,
    //     ]);
    // }

    // public function getData(Request $request)
    // {
    //     set_time_limit(300);
    //     $today = date('Y-m-d');
    //     $startDate = $request->input('startDate');
    //     $endDate = $request->input('endDate');


    //     $checkinQuery = Absensici::with(['user', 'shift'])
    //         ->select('npk', 'tanggal', DB::raw('MIN(waktuci) as waktuci'))
    //         ->groupBy('npk', 'tanggal');

    //     if (!empty($startDate) && !empty($endDate)) {
    //         $checkinQuery->whereBetween('tanggal', [$startDate, $endDate]);
    //     }
    //     if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
    //         $selectedNPK = $request->selectedNpk;
    //         $checkinQuery->whereIn('npk', $selectedNPK);
    //     }

    //     $checkinResults = $checkinQuery->get();

    //     $checkoutQuery = Absensico::with(['user', 'shift'])
    //         ->select('npk', 'tanggal', DB::raw('MAX(waktuco) as waktuco'))
    //         ->groupBy('npk', 'tanggal');

    //     if (!empty($startDate) && !empty($endDate)) {
    //         $checkoutQuery->whereBetween('tanggal', [$startDate, $endDate]);
    //     }
    //     if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
    //         $selectedNPK = $request->selectedNpk;
    //         $checkoutQuery->whereIn('npk', $selectedNPK);
    //     }
    //     $checkoutResults = $checkoutQuery->get();
    //     $results = [];

    //     // Proses check-in
    //     foreach ($checkinResults as $checkin) {
    //         $key = "{$checkin->npk}-{$checkin->tanggal}";

    //         // Ambil informasi section, department, dan division
    //         $section = $checkin->user ? $checkin->user->section : null;
    //         $department = $section ? $section->department : null;
    //         $division = $department ? $department->division : null;

    //         // Ambil shift
    //         $latestShift = shift::where('npk', $checkin->npk)
    //             ->where('date', $checkin->tanggal)
    //             ->latest()
    //             ->first();
    //         $shift1 = $latestShift ? $latestShift->shift1 : null;

    //         $role = $checkin->user ? $checkin->user->role : null;

    //         // Tentukan status check-in
    //         $status = 'Mangkir'; // Default status

    //         if ($role && in_array($role->id, [5, 8])) {
    //             $status = 'Tepat Waktu';
    //         } elseif ($latestShift) {
    //             $shiftIn = explode(' - ', str_replace('.', ':', $shift1))[0];
    //             $shiftInFormatted = date('H:i:s', strtotime($shiftIn));
    //             $status = $checkin->waktuci && $checkin->waktuci > $shiftInFormatted ? 'Terlambat' : 'Tepat Waktu';
    //         }

    //         // Pastikan entri check-in tetap ada meskipun waktuci null
    //         $results[$key] = [
    //             'nama' => $checkin->user ? $checkin->user->nama : '',
    //             'npk' => $checkin->npk,
    //             'tanggal' => $checkin->tanggal,
    //             'waktuci' => $checkin->waktuci, // Akan null jika tidak ada check-in
    //             'waktuco' => null, // Waktu checkout belum ada
    //             'shift1' => $shift1,
    //             'section_nama' => $section ? $section->nama : '',
    //             'department_nama' => $department ? $department->nama : '',
    //             'division_nama' => $division ? $division->nama : '',
    //             'status' => ($role && in_array($role->id, [4, 5, 8])) ? $status : ($shift1 === null ? 'Mangkir' : $status),
    //         ];
    //     }

    //     // Proses check-out
    //     foreach ($checkoutResults as $checkout) {
    //         $key = "{$checkout->npk}-{$checkout->tanggal}";
    //         $role = $checkout->user ? $checkout->user->role : null;

    //         $status = 'NO IN'; // Default status
    //         if ($role && in_array($role->id, [5, 8])) {
    //             $status = 'Tepat Waktu';
    //         }

    //         $latestShift = shift::where('npk', $checkout->npk)
    //             ->where('date', $checkout->tanggal)
    //             ->latest()
    //             ->first();
    //         $shift1 = $latestShift ? $latestShift->shift1 : null;

    //         // Cek apakah ada check-in untuk tanggal ini
    //         if (isset($results[$key])) {
    //             // Jika waktu checkout berada di antara jam 00:00 sampai 10:00 pagi, maka pindah ke hari sebelumnya
    //             if (date('H', strtotime($checkout->waktuco)) < 10) {
    //                 // Hari sebelumnya
    //                 $previousDay = date('Y-m-d', strtotime("{$checkout->tanggal} -1 day"));
    //                 $previousKey = "{$checkout->npk}-{$previousDay}";

    //                 // Jika ada data untuk hari sebelumnya, masukkan waktu checkout
    //                 if (isset($results[$previousKey])) {
    //                     $results[$previousKey]['waktuco'] = $checkout->waktuco;
    //                 } else {
    //                     // Jika tidak ada entri di hari sebelumnya, buat entri baru untuk tanggal sebelumnya
    //                     $results[$previousKey] = [
    //                         'nama' => $checkout->user ? $checkout->user->nama : '',
    //                         'npk' => $checkout->npk,
    //                         'tanggal' => $previousDay,
    //                         'waktuci' => null, // Tidak ada check-in
    //                         'waktuco' => $checkout->waktuco,
    //                         'shift1' => null,
    //                         'section_nama' => $checkout->user && $checkout->user->section ? $checkout->user->section->nama : '',
    //                         'department_nama' => $checkout->user && $checkout->user->section && $checkout->user->section->department ? $checkout->user->section->department->nama : '',
    //                         'division_nama' => $checkout->user && $checkout->user->section && $checkout->user->section->department && $checkout->user->section->department->division ? $checkout->user->section->department->division->nama : '',
    //                         'status' => ($role && in_array($role->id, [4, 5, 8])) ? $status : ($shift1 === null ? 'Mangkir' : $status),
    //                     ];
    //                 }
    //             } else {
    //                 // Update waktu check-out untuk hari ini
    //                 $results[$key]['waktuco'] = $checkout->waktuco;
    //             }
    //         }
    //     }




    //     $noCheckData = shift::with(['user.section.department.division', 'user.role'])
    //         ->leftJoin('absensici', function ($join) {
    //             $join->on('absensici.npk', '=', 'kategorishift.npk')
    //                 ->on('absensici.tanggal', '=', 'kategorishift.date');
    //         })
    //         ->leftJoin('absensico', function ($join) {
    //             $join->on('absensico.npk', '=', 'kategorishift.npk')
    //                 ->on('absensico.tanggal', '=', 'kategorishift.date');
    //         })
    //         ->select(
    //             'kategorishift.npk',
    //             'kategorishift.date as tanggal',
    //             'kategorishift.shift1',
    //             DB::raw('IFNULL(DATE_FORMAT(MIN(absensici.waktuci), "%H:%i"), "NO IN") as waktuci'),
    //             DB::raw('IFNULL(DATE_FORMAT(MAX(absensico.waktuco), "%H:%i"), "NO OUT") as waktuco')
    //         )
    //         ->whereNull('absensici.waktuci')
    //         ->whereNull('absensico.waktuco')
    //         ->where('kategorishift.date', '<=', $today)
    //         ->where('kategorishift.shift1', '!=', 'OFF');

    //     // Tambahkan filter tanggal jika $startDate dan $endDate tidak kosong
    //     if (!empty($startDate) && !empty($endDate)) {
    //         $noCheckData->whereBetween('kategorishift.date', [$startDate, $endDate]);
    //     }

    //     // Tambahkan filter NPK jika terdapat NPK yang dipilih
    //     if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
    //         $selectedNPK = $request->selectedNpk;
    //         $noCheckData->whereIn('kategorishift.npk', $selectedNPK);
    //     }

    //     $noCheckData = $noCheckData
    //         ->groupBy('kategorishift.npk', 'kategorishift.date', 'kategorishift.shift1')
    //         ->get();


    //     $noCheckData = shift::with(['user.section.department.division', 'user.role'])
    //         ->leftJoin('absensici', function ($join) {
    //             $join->on('absensici.npk', '=', 'kategorishift.npk')
    //                 ->on('absensici.tanggal', '=', 'kategorishift.date');
    //         })
    //         ->leftJoin('absensico', function ($join) {
    //             $join->on('absensico.npk', '=', 'kategorishift.npk')
    //                 ->on('absensico.tanggal', '=', 'kategorishift.date');
    //         })
    //         ->select(
    //             'kategorishift.npk',
    //             'kategorishift.date as tanggal',
    //             'kategorishift.shift1',
    //             DB::raw('IFNULL(DATE_FORMAT(MIN(absensici.waktuci), "%H:%i"), "NO IN") as waktuci'),
    //             DB::raw('IFNULL(DATE_FORMAT(MAX(absensico.waktuco), "%H:%i"), "NO OUT") as waktuco')
    //         )
    //         ->whereNull('absensici.waktuci')
    //         ->whereNull('absensico.waktuco')
    //         ->where('kategorishift.date', '<=', $today)
    //         ->where('kategorishift.shift1', '!=', 'OFF');

    //     // Tambahkan filter tanggal jika $startDate dan $endDate tidak kosong
    //     if (!empty($startDate) && !empty($endDate)) {
    //         $noCheckData->whereBetween('kategorishift.date', [$startDate, $endDate]);
    //     }

    //     // Tambahkan filter NPK jika terdapat NPK yang dipilih
    //     if ($request->has('selectedNpk') && !empty($request->selectedNpk)) {
    //         $selectedNPK = $request->selectedNpk;
    //         $noCheckData->whereIn('kategorishift.npk', $selectedNPK);
    //     }

    //     $noCheckData = $noCheckData
    //         ->groupBy('kategorishift.npk', 'kategorishift.date', 'kategorishift.shift1')
    //         ->get();

    //     foreach ($noCheckData as $noCheck) {
    //         $key = "{$noCheck->npk}-{$noCheck->tanggal}";
    //         $role = $noCheck->user ? $noCheck->user->role : null;

    //         // Ambil shift terakhir berdasarkan date atau kolom lain yang relevan
    //         $latestShift = shift::where('npk', $noCheck->npk)
    //             ->where('date', $noCheck->tanggal)
    //             ->orderBy('date', 'desc')
    //             ->latest('created_at') // Jika ada kolom updated_at, tambahkan latest berdasarkan kolom ini
    //             ->first();
    //         $shift1 = $latestShift ? $latestShift->shift1 : null;

    //         // Dapatkan waktu saat ini
    //         $currentTime = now();
    //         $shiftStartTime = null;

    //         // Tentukan waktu mulai shift jika formatnya valid
    //         if ($shift1 && !in_array($shift1, ['Dinas Luar Stand By', 'OFF']) && strpos($shift1, ' - ') !== false) {
    //             $shiftTimes = explode(' - ', $shift1);
    //             if (count($shiftTimes) == 2 && preg_match('/^\d{2}:\d{2}$/', $shiftTimes[0])) {
    //                 $shiftStartTime = Carbon::createFromFormat('H:i', $shiftTimes[0]);
    //             } else {
    //                 $shiftStartTime = null;
    //                 Log::warning("Format shift tidak valid: " . $shift1);
    //             }
    //         }

    //         // Tentukan status berdasarkan kondisi yang relevan
    //         if ($role && in_array($role->id, [5, 8])) {
    //             $status = 'Tepat Waktu';
    //         } elseif (!isset($results[$key])) {
    //             $status = ($shift1 === "Dinas Luar Stand By") ? "Dinas Luar Stand By" : "Mangkir";
    //         } elseif ($shiftStartTime && $currentTime->gt($shiftStartTime) && $noCheck->waktuci === 'NO IN' && $noCheck->waktuco === 'NO OUT') {
    //             $status = "Mangkir";
    //         }

    //         // Isi array results jika belum ada entri untuk key ini
    //         if (!isset($results[$key])) {
    //             $results[$key] = [
    //                 'nama' => $noCheck->user ? $noCheck->user->nama : '',
    //                 'npk' => $noCheck->npk,
    //                 'tanggal' => $noCheck->tanggal,
    //                 'waktuci' => 'NO IN',
    //                 'waktuco' => 'NO OUT',
    //                 'shift1' => $shift1,
    //                 'role' => $role,
    //                 'section_nama' => $noCheck->user && $noCheck->user->section ? $noCheck->user->section->nama : '',
    //                 'department_nama' => $noCheck->user && $noCheck->user->section && $noCheck->user->section->department ? $noCheck->user->section->department->nama : '',
    //                 'division_nama' => $noCheck->user && $noCheck->user->section && $noCheck->user->section->department && $noCheck->user->section->department->division ? $noCheck->user->section->department->division->nama : '',
    //                 'status' =>  $status,
    //             ];
    //         }
    //     }
    //     $finalResults = collect(array_values($results))->sortBy('tanggal');
    //     foreach ($finalResults as $key => $row) {
    //         $npk = $row['npk'];
    //         $tanggalMulai = $row['tanggal'] ?? null;

    //         if (!$tanggalMulai) {
    //             Log::error("Tanggal tidak ditemukan untuk NPK: $npk");
    //             continue;
    //         }

    //         $user = User::where('npk', $npk)->first();
    //         $npkSistem = $user->npk_sistem ?? 'tidak ditemukan';

    //         // Cuti Model
    //         $cutiModels = CutiModel::where('npk', $npk)
    //             ->where(function ($query) use ($tanggalMulai) {
    //                 $query->where('tanggal_mulai', '<=', $tanggalMulai)
    //                     ->where(function ($query) use ($tanggalMulai) {
    //                         $query->whereRaw('COALESCE(tanggal_selesai, tanggal_mulai) >= ?', [$tanggalMulai]); // Gunakan COALESCE
    //                     });
    //             })
    //             ->whereIn('approved_by', [2, 3, 4, 5, 8, 10])
    //             ->get();

    //         $cutiCount = $cutiModels->count();
    //         $kategoriCuti = $cutiModels->pluck('kategori')->first();

    //         // Penyimpangan Model
    //         $penyimpangan = Penyimpanganmodel::where('npk', $npk)
    //             ->where(function ($query) use ($tanggalMulai) {
    //                 $query->where('tanggal_mulai', '<=', $tanggalMulai)
    //                     ->where(function ($query) use ($tanggalMulai) {
    //                         $query->whereRaw('COALESCE(tanggal_selesai, tanggal_mulai) >= ?', [$tanggalMulai]); // Gunakan COALESCE
    //                     });
    //             })
    //             ->whereIn('approved_by', [2, 3, 4, 5, 8, 10])
    //             ->first();

    //         $penyimpanganCount = $penyimpangan ? 1 : 0;
    //         $kategoriPenyimpangan = $penyimpangan->kategori ?? null;

    //         // Implementasi logika untuk status dan API Time
    //         $apiTime = null;

    //         // Menambahkan tombol Cuti untuk setiap tanggal dalam rentang cuti
    //         if ($cutiCount > 0) {
    //             foreach ($cutiModels as $cuti) {
    //                 $apiTime .= ' <button class="btn btn-info view-cuti" data-npk="' . $npk . '" data-tanggal="' . $cuti->tanggal_mulai . '">Lihat Cuti</button>';
    //             }
    //         }

    //         // Menambahkan tombol Penyimpangan jika ada
    //         if ($penyimpanganCount > 0) {
    //             $apiTime .= '<button class="btn btn-warning view-penyimpangan" data-npk="' . $npk . '" data-tanggal="' . $penyimpangan->tanggal_mulai . '">Lihat Penyimpangan</button>';
    //         }

    //         // Memperbarui data
    //         $finalResults->put($key, array_merge($row, [
    //             'has_penyimpangan' => $penyimpanganCount > 0,
    //             'has_cuti' => $cutiCount > 0,
    //             'api_time' => $apiTime,
    //             'npk_sistem' => $npkSistem,
    //             'waktuci' => $row['waktuci'] ?? 'NO IN',
    //             'waktuco' => $row['waktuco'] ?? 'NO OUT',
    //             'status' => !empty($kategoriCuti) ? $kategoriCuti : (!empty($kategoriPenyimpangan) ? $kategoriPenyimpangan : $row['status']),
    //         ]));
    //     }

    //     $data = [];
    //     foreach ($finalResults as $item) {
    //         $data[] = $item;
    //     }

    //     return response()->json([
    //         "data" => $data,
    //     ]);
    // }
    public function getKaryawan(Request $request)
    {
        $user = Auth::user();
        $status = $request->query('status', 1);
        $roleId = $user->role_id;
        $sectionId = $user->section_id;
        $departmentId = $user->department_id;

        $departmentFilter = $request->query('department_id');
        $sectionFilter = $request->query('section_id');

        $query = User::select('nama', 'npk')->where('status', $status);

        if ($roleId == 2) {
            $query->where('section_id', $sectionId);
        } else if ($roleId == 9) {
            $query->where('department_id', $departmentId);
        }

        if ($departmentFilter) {
            $query->where('department_id', $departmentFilter);
        }
        if ($sectionFilter) {
            $query->where('section_id', $sectionFilter);
        }

        $userData = $query->get();

        return response()->json([
            'userData' => $userData
        ]);
    }

    public function getDepartments(Request $request)
    {
        // Ambil data user yang sedang login
        $user = Auth::user();
        $departmentId = $user->department_id; // ID departemen user
        $roleId = $user->role_id; // ID role user

        // Ambil departemen sesuai dengan department_id user atau semua departemen jika role_id 1 atau 6
        if (in_array($roleId, [1, 6])) {
            $departments = DepartmentModel::all(['id', 'nama']); // Ambil semua departemen
        } else {
            $departments = DepartmentModel::where('id', $departmentId)->get(['id', 'nama']); // Filter berdasarkan department_id
        }

        // Ambil section hanya jika department_id dipilih
        $sections = collect();

        if ($request->has('department_id') && $request->department_id != '') {
            $sections = SectionModel::where('department_id', $request->department_id)->get(['id', 'nama']);
        } else if ($roleId != 9) {
            $sections = SectionModel::all(['id', 'nama']); // Ambil semua section untuk role selain 9
        }

        return response()->json([
            'departments' => $departments,
            'sections' => $sections
        ]);
    }




    public function getPenyimpangan(Request $request)
    {
        $no = 1;

        $npk = $request->get('npk');
        $tanggal = $request->get('tanggal');

        $penyimpangan = PenyimpanganModel::where('npk', $npk)
            ->where('tanggal_mulai', $tanggal)
            ->get();

        // Looping untuk manipulasi data yang diambil
        foreach ($penyimpangan as $item) {
            // Cek berdasarkan nilai approved_by
            if ($item->approved_by == 4) {
                $item->approved_by = '<span class="badge badge-success">Approved by Department</span>';
            } elseif ($item->approved_by == 3) {
                $item->approved_by = '<span class="badge badge-success">Approved by Section</span>';
            } elseif ($item->approved_by == 5) {
                $item->approved_by = '<span class="badge badge-success">Approved by Division</span>';
            } elseif ($item->rejected_by == 5) {
                $item->approved_by = '<span class="badge badge-danger">Rejected by Division</span>';
            } elseif ($item->rejected_by == 4) {
                $item->approved_by = '<span class="badge badge-danger">Rejected by Department</span>';
            } elseif ($item->rejected_by == 3) {
                $item->approved_by = '<span class="badge badge-danger">Rejected by Section</span>';
            } else {
                $item->status = $item->sent == 1 ? '<span class="badge badge-secondary">Need Approval</span>' : 'Not Sent';
            }

            if (!empty($item->foto)) {
                $item->file_upload = !empty($item->foto) ? '<button class="btn btn-primary btn-sm" onclick="showImage(\'' . asset('storage/' . $item->foto) . '\')">Lihat Foto</button>' : '';
            } else {
                $item->file_upload = ' ';
            }
            $item->no = $no++;
        }

        return response()->json([
            'data' => $penyimpangan,
        ]);
    }

    public function getCuti(Request $request)
    {
        $no = 1;

        $npk = $request->get('npk');
        $tanggal = $request->get('tanggal');

        $cuti = CutiModel::where('npk', $npk)
            ->where('tanggal_mulai', $tanggal)
            ->get();

        // Looping untuk manipulasi data yang diambil
        foreach ($cuti as $item) {
            // Cek berdasarkan nilai approved_by
            if ($item->approved_by == 4) {
                $item->approved_by = '<span class="badge badge-success">Approved by Department</span>';
            } elseif ($item->approved_by == 3) {
                $item->approved_by = '<span class="badge badge-success">Approved by Section</span>';
            } elseif ($item->approved_by == 5) {
                $item->approved_by = '<span class="badge badge-success">Approved by Division</span>';
            } elseif ($item->rejected_by == 5) {
                $item->approved_by = '<span class="badge badge-danger">Rejected by Division</span>';
            } elseif ($item->rejected_by == 4) {
                $item->approved_by = '<span class="badge badge-danger">Rejected by Department</span>';
            } elseif ($item->rejected_by == 3) {
                $item->approved_by = '<span class="badge badge-danger">Rejected by Section</span>';
            } else {
                $item->status = $item->sent == 1 ? '<span class="badge badge-secondary">Need Approval</span>' : 'Not Sent';
            }

            if (!empty($item->foto)) {
                $item->file_upload = !empty($item->foto) ? '<button class="btn btn-primary btn-sm" onclick="showImage(\'' . asset('storage/' . $item->foto) . '\')">Lihat Foto</button>' : '';
            } else {
                $item->file_upload = ' ';
            }
            $item->no = $no++;
        }

        return response()->json([
            'data' => $cuti,
        ]);
    }


    public function storeCheckin(Request $request)
    {

        $request->validate([

            'npk' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'waktuci' => 'required|date_format:H:i',
        ]);


        absensici::create([

            'npk' => $request->npk,
            'tanggal' => $request->tanggal,
            'waktuci' => $request->waktuci,
        ]);


        return response()->json(['success' => 'Check-in berhasil ditambahkan!']);
    }


    public function storeCheckout(Request $request)
    {
        $request->validate([

            'npk' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'waktuco' => 'required|date_format:H:i',
        ]);

        absensico::create([

            'npk' => $request->npk,
            'tanggal' => $request->tanggal,
            'waktuco' => $request->waktuco,
        ]);

        return response()->json(['success' => 'Check-in berhasil ditambahkan!']);
    }

    public function upload(Request $request)
    {
        set_time_limit(300);
        $request->validate([
            'file' => 'required|mimes:txt|max:250', // Max size in kilobytes
        ]);

        $file = $request->file('file');
        if (!$file->isValid()) {
            return redirect()->back()->withErrors('File upload gagal. Silakan coba lagi.');
        }

        // Simpan file ke storage
        $filePath = $file->storeAs('uploads', $file->getClientOriginalName());

        if (!$filePath) {
            return redirect()->back()->withErrors('Gagal mengunggah file.');
        }

        $fileContent = file(storage_path('app/' . $filePath));

        $batchSize = 100;
        $totalLines = count($fileContent);

        for ($offset = 0; $offset < $totalLines; $offset += $batchSize) {
            // Ambil batch sesuai offset dan batchSize
            $batch = array_slice($fileContent, $offset, $batchSize);

            foreach ($batch as $line) {
                $data = str_getcsv($line, "\t"); // Parsing baris CSV dengan delimiter tab

                if (count($data) >= 5) {
                    $npk_sistem = $data[1];
                    $tanggal = $data[2];
                    $status = $data[3];
                    $time = $data[4];

                    // Mengonversi format tanggal
                    $date = DateTime::createFromFormat('d.m.Y', $tanggal);
                    if ($date) {
                        $formattedDate = $date->format('Y-m-d');
                    } else {
                        Log::error('Format tanggal tidak valid', ['date' => $tanggal]);
                        continue;
                    }

                    // Cari user berdasarkan npk_sistem
                    $user = User::where('npk_sistem', $npk_sistem)
                        ->where('status', 1) // Pastikan hanya mengambil yang statusnya aktif
                        ->first();

                    if ($user) {
                        // Simpan atau update ke dalam tabel yang sesuai dengan mengisi npk otomatis
                        if ($status == 'P10') {
                            Absensici::create([
                                'npk_sistem' => $npk_sistem,
                                'tanggal' => $formattedDate,
                                'waktuci' => $time,
                                'npk' => $user->npk, // Isi npk dari relasi User
                            ]);
                        } elseif ($status == 'P20') {
                            Absensico::create([
                                'npk_sistem' => $npk_sistem,
                                'tanggal' => $formattedDate,
                                'waktuco' => $time,
                                'npk' => $user->npk, // Isi npk dari relasi User
                            ]);
                        }
                    } else {
                        Log::error('User dengan npk_sistem tidak ditemukan', ['npk_sistem' => $npk_sistem]);
                    }
                } else {
                    Log::warning('Data tidak lengkap dalam baris', ['line' => $line]);
                }
            }
        }

        return redirect()->back()->with('success', 'File berhasil diunggah dan diproses.');
    }

    public function exportAbsensi(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $search = $request->input('search'); // Ambil parameter search

        return Excel::download(new RekapAbsensiExport($startDate, $endDate, $search), 'absensi.xlsx');
    }

    public function uploadapi(Request $request)
    {
        // Validasi file yang diunggah
        $request->validate([
            'file' => 'required|mimes:txt,csv' // Memperbolehkan file CSV juga
        ]);

        $file = $request->file('file');
        if (!$file->isValid()) {
            return redirect()->back()->withErrors('File upload failed. Please try again.');
        }

        $fileContent = file($file->getRealPath());

        foreach ($fileContent as $line) {
            $data = str_getcsv($line, "\t"); // Menganggap file menggunakan tab sebagai pemisah

            if (count($data) >= 5) {
                $npk = $data[1];
                $tanggal = $data[2];
                $status = $data[3];
                $time = $data[4];

                // Mengonversi format tanggal dari dd.mm.yyyy ke yyyy-mm-dd
                $date = DateTime::createFromFormat('d.m.Y', $tanggal);
                if ($date) {
                    $formattedDate = $date->format('Y-m-d'); // Mengonversi ke yyyy-mm-dd
                } else {
                    // Log error jika tanggal tidak valid
                    Log::error('Invalid date format', ['date' => $tanggal]);
                    continue;
                }

                if ($status == 'P10') {
                    Absensici::updateOrCreate(
                        ['npk' => $npk, 'tanggal' => $formattedDate],
                        ['waktuci' => $time]
                    );
                } elseif ($status == 'P20') {
                    Absensico::updateOrCreate(
                        ['npk' => $npk, 'tanggal' => $formattedDate],
                        ['waktuco' => $time]
                    );
                }
            } else {
                Log::warning('Insufficient data in line', ['line' => $line]);
            }
        }

        return response()->json(['success' => 'Data berhasil diupload!']);
    }

    // Tambahkan method baru di rekapController
    // public function getDataByNpkTanggal($npk, $tanggal)
    // {
    //     $checkinData = Absensici::with(['user', 'shift', 'user.section.department.division'])
    //         ->where('npk', $npk)
    //         ->where('tanggal', $tanggal)
    //         ->first();

    //     $checkoutData = Absensico::where('npk', $npk)
    //         ->where('tanggal', $tanggal)
    //         ->first();

    //     $data = [
    //         'npk' => $checkinData->npk,
    //         'tanggal' => $checkinData->tanggal,
    //         'waktuci' => $checkinData->waktuci,
    //         'waktuco' => $checkoutData ? $checkoutData->waktuco : 'NO OUT',
    //         'nama' => $checkinData->user->nama,
    //         'section_nama' => optional($checkinData->user->section)->nama,
    //         'department_nama' => optional($checkinData->user->section->department)->nama,
    //         'division_nama' => optional($checkinData->user->section->department->division)->nama,
    //         'shift1' => optional($checkinData->shift)->shift1,
    //     ];

    //     return response()->json($data);
    // }

    public function updateData(Request $request, $npk, $tanggal)
    {
        $validated = $request->validate([
            'waktuci' => 'nullable',
            'waktuco' => 'nullable',
        ]);

        $checkin = Absensici::where('npk', $npk)->where('tanggal', $tanggal)->first();
        $checkout = Absensico::where('npk', $npk)->where('tanggal', $tanggal)->first();

        if ($checkin) {
            $checkin->waktuci = $validated['waktuci'];
            $checkin->save();
        }

        if ($checkout) {
            $checkout->waktuco = $validated['waktuco'];
            $checkout->save();
        }

        return response()->json(['message' => 'Data berhasil diperbarui']);
    }


    public function delete(Request $request)
    {
        // Validasi input
        $request->validate([
            'npk' => 'required|string',
            'tanggal' => 'required|date',
            'hapusabsen' => 'required|in:in,out'
        ]);

        // Tentukan model dan kolom yang akan dihapus berdasarkan 'hapusabsen'
        if ($request->hapusabsen == 'in') {
            $model = Absensici::class;
            $columnToDelete = 'waktuci';
        } else {
            $model = Absensico::class;
            $columnToDelete = 'waktuco';
        }

        // Hapus data di model yang sesuai berdasarkan tanggal dan kolom yang dipilih
        $deleted = $model::where('tanggal', $request->tanggal)
            ->whereNotNull($columnToDelete) // Pastikan kolom waktu absen tidak null
            ->delete(); // Hapus data

        // Mengembalikan respons JSON
        if ($deleted) {
            return response()->json(['success' => true, 'message' => 'Absen berhasil dihapus']);
        } else {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus absen']);
        }
    }
}
