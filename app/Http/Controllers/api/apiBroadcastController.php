<?php

namespace App\Http\Controllers\api;

use Carbon\Carbon;

use App\Models\User;
use App\Models\shift;
use App\Models\absensici;
use App\Models\absensico;
use App\Models\CutiModel;
use App\Models\PenyimpanganModel;
use Illuminate\Http\Request;
use App\Models\peringatanModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class apiBroadcastController extends Controller
{
    public function checkLateAndAbsent()
    {
        set_time_limit(0); // Menghindari time-out untuk proses panjang
    
        // Ambil tanggal hari kemarin
        $yesterday = Carbon::yesterday();
    
        // Jalankan query check-in, check-out, dan data tanpa absensi
        $checkinResults = $this->getCheckinData();
        $checkoutResults = $this->getCheckoutData();
        $noCheckResults = $this->getNoCheckData();
    
        // Gabungkan semua hasil
        $mergedResults = $checkinResults->merge($checkoutResults)->merge($noCheckResults);
    
        // Periksa data cuti dan penyimpangan
        $results = $this->processAdditionalData($mergedResults);
    
        dd($results);
        // Urutkan hasil berdasarkan tanggal
        return $results->sortBy('tanggal')->values();
    }

    private function getCheckinData()
    {
        return AbsensiCi::with('user')
            ->select('npk', 'tanggal', DB::raw('MIN(waktuci) as waktuci'))
            ->groupBy('npk', 'tanggal')
            ->get()
            ->mapWithKeys(function ($checkin) {
                $key = "{$checkin->npk}-{$checkin->tanggal}";
                return [$key => [
                    'nama' => $checkin->user->nama ?? 'Tidak Diketahui',
                    'npk' => $checkin->npk,
                    'tanggal' => $checkin->tanggal,
                    'waktuci' => $checkin->waktuci,
                    'waktuco' => null,
                    'status' => $this->determineStatus($checkin->user, $checkin->waktuci),
                ]];
            });
    }

    private function getCheckoutData()
    {
        return AbsensiCo::with('user')
            ->select('npk', 'tanggal', DB::raw('MAX(waktuco) as waktuco'))
            ->groupBy('npk', 'tanggal')
            ->get()
            ->mapWithKeys(function ($checkout) {
                $key = "{$checkout->npk}-{$checkout->tanggal}";
                return [$key => [
                    'nama' => $checkout->user->nama ?? 'Tidak Diketahui',
                    'npk' => $checkout->npk,
                    'tanggal' => $checkout->tanggal,
                    'waktuci' => null,
                    'waktuco' => $checkout->waktuco,
                    'status' => 'NO IN',
                ]];
            });
    }

    private function getNoCheckData()
    {
        $yesterday = Carbon::yesterday();
        return Shift::with('user')
            ->leftJoin('absensici', function ($join) {
                $join->on('absensici.npk', '=', 'shift.npk')
                    ->on('absensici.tanggal', '=', 'shift.date');
            })
            ->leftJoin('absensico', function ($join) {
                $join->on('absensico.npk', '=', 'shift.npk')
                    ->on('absensico.tanggal', '=', 'shift.date');
            })
            ->select(
                'shift.npk',
                'shift.date as tanggal',
                DB::raw('IFNULL(DATE_FORMAT(MIN(absensici.waktuci), "%H:%i"), "NO IN") as waktuci'),
                DB::raw('IFNULL(DATE_FORMAT(MAX(absensico.waktuco), "%H:%i"), "NO OUT") as waktuco')
            )
            ->whereNull('absensici.waktuci')
            ->whereNull('absensico.waktuco')
            ->where('shift.date', '=', $yesterday)
            ->get()
            ->mapWithKeys(function ($noCheck) {
                $key = "{$noCheck->npk}-{$noCheck->tanggal}";
                return [$key => [
                    'nama' => $noCheck->user->nama ?? 'Tidak Diketahui',
                    'npk' => $noCheck->npk,
                    'tanggal' => $noCheck->tanggal,
                    'waktuci' => $noCheck->waktuci,
                    'waktuco' => $noCheck->waktuco,
                    'status' => 'No Absence',
                ]];
            });
    }

    private function processAdditionalData($mergedResults)
    {
        return $mergedResults->map(function ($row) {
            $npk = $row['npk'];
            $tanggal = $row['tanggal'] ?? null;
    
            // Cek data cuti
            $cutiModels = CutiModel::where('npk', $npk)
                ->where(function ($query) use ($tanggal) {
                    $query->where('tanggal_mulai', '<=', $tanggal)
                        ->whereRaw('COALESCE(tanggal_selesai, tanggal_mulai) >= ?', [$tanggal]);
                })
                ->whereIn('approved_by', [2, 3, 4, 5])
                ->get();
    
            $kategoriCuti = $cutiModels->pluck('kategori')->first();
    
            // Cek data penyimpangan
            $penyimpangan = PenyimpanganModel::where('npk', $npk)
                ->where(function ($query) use ($tanggal) {
                    $query->where('tanggal_mulai', '<=', $tanggal)
                        ->whereRaw('COALESCE(tanggal_selesai, tanggal_mulai) >= ?', [$tanggal]);
                })
                ->whereIn('approved_by', [2, 3, 4, 5])
                ->first();
    
            $kategoriPenyimpangan = $penyimpangan->kategori ?? null;
    
            // Update data dengan status
            $row['status'] = $kategoriCuti ?: ($kategoriPenyimpangan ?: $row['status']);
            return $row;
        });
    }

    private function determineStatus($user, $checkinTime)
    {
        if (!$user || !$checkinTime) {
            return 'No Shift';
        }
        $role = $user->role->id ?? null;
        return $role && in_array($role, [5, 8]) ? 'Tepat Waktu' : 'Terlambat';
    }

    public function getData(Request $request)
    {
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

        $results = [];

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

            $status = 'No Shift';
            $role = $checkin->user ? $checkin->user->role : null;

            // Cek jika role adalah 5 atau 8, maka status langsung 'Tepat Waktu'
            if ($role && in_array($role->id, [5, 8])) {
                $status = 'Tepat Waktu';
            } elseif ($latestShift) {
                // Jika tidak, cek apakah terlambat atau tepat waktu berdasarkan shift
                $shiftIn = explode(' - ', str_replace('.', ':', $shift1))[0];
                $shiftInFormatted = date('H:i:s', strtotime($shiftIn));
                $status = $checkin->waktuci > $shiftInFormatted ? 'Terlambat' : 'Tepat Waktu';
            }

            // Masukkan data check-in
            $results[$key] = [
                'nama' => $checkin->user ? $checkin->user->nama : '',
                'npk' => $checkin->npk,
                'tanggal' => $checkin->tanggal,
                'waktuci' => $checkin->waktuci,
                'shift1' => $shift1,
                'section_nama' => $section ? $section->nama : '',
                'department_nama' => $department ? $department->nama : '',
                'division_nama' => $division ? $division->nama : '',
                'status' => $status
            ];
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
                }
            }

            // Tentukan status berdasarkan kondisi yang relevan
            if ($role && in_array($role->id, [5, 8])) {
                $status = 'Tepat Waktu';
            } elseif (!isset($results[$key])) {
                $status = ($shift1 === "Dinas Luar Stand By") ? "Dinas Luar Stand By" : "Mangkir";
            } elseif ($shiftStartTime && $currentTime->gt($shiftStartTime) && $noCheck->waktuci === 'NO IN') {
                $status = "Mangkir";
            }

            // Isi array results jika belum ada entri untuk key ini
            if (!isset($results[$key])) {
                $results[$key] = [
                    'nama' => $noCheck->user ? $noCheck->user->nama : '',
                    'npk' => $noCheck->npk,
                    'tanggal' => $noCheck->tanggal,
                    'waktuci' => 'NO IN',
                    'shift1' => $shift1,
                    'role' => $role,
                    'section_nama' => $noCheck->user && $noCheck->user->section ? $noCheck->user->section->nama : '',
                    'department_nama' => $noCheck->user && $noCheck->user->section && $noCheck->user->section->department ? $noCheck->user->section->department->nama : '',
                    'division_nama' => $noCheck->user && $noCheck->user->section && $noCheck->user->section->department && $noCheck->user->section->department->division ? $noCheck->user->section->department->division->nama : '',
                    'status' => $status
                ];
            }
        }
        $finalResults = collect(array_values($results))->sortBy('tanggal');
        foreach ($finalResults as $key => $row) {
            $npk = $row['npk'];
            $tanggalMulai = $row['tanggal'] ?? null;

            if (!$tanggalMulai) {
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
                ->whereIn('approved_by', [2, 3, 4, 5])
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
                ->whereIn('approved_by', [2, 3, 4, 5])
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
                'waktuci' => $row['waktuci'] ?? 'NO IN',
                'status' => !empty($kategoriCuti) ? $kategoriCuti : (!empty($kategoriPenyimpangan) ? $kategoriPenyimpangan : $row['status']),
            ]));
        }

        $data = [];
        foreach ($finalResults as $item) {
            $data[] = $item;
        }

        return response()->json([
            "data" => $data,
        ]);
    }
}
