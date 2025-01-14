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
    public function checkLateAndAbsent(Request $request)
    {
        set_time_limit(300);
        try {
            $selectedDay = $request->query('day', Carbon::yesterday()->toDateString());

            try {
                $carbonDate = Carbon::parse($selectedDay)->toDateString();
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid date format. Use Y-m-d format.'
                ], 400);
            }

            $checkinResults = $this->getCheckinData($carbonDate) ?? collect([]);
            $checkoutResults = $this->getCheckoutData($carbonDate) ?? collect([]);
            $noCheckResults = $this->getNoCheckData($carbonDate) ?? collect([]);

            // Gabungkan data dari semua koleksi
            $mergedResults = $this->mergeAttendanceData($checkinResults, $checkoutResults, $noCheckResults);

            $sortedResults = $mergedResults->sortBy('tanggal')->values();

            $user = User::all();
            $this->sendWarningMessage($user, $sortedResults);

            return response()->json([
                'success' => true,
                'message' => 'Late and absent data retrieved successfully',
                'data' => $sortedResults
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function mergeAttendanceData($checkinResults, $checkoutResults, $noCheckResults)
    {
        // Gabungkan semua hasil ke dalam satu koleksi
        $allResults = collect();

        // Tambahkan data check-in
        foreach ($checkinResults as $checkin) {
            $key = "{$checkin['npk']}-{$checkin['tanggal']}";
            $allResults->put($key, [
                'nama' => $checkin['nama'],
                'npk' => $checkin['npk'],
                'tanggal' => $checkin['tanggal'],
                'waktuci' => $checkin['waktuci'] ?? 'NO IN',
                'waktuco' => null,
                'shift' => $checkin['shift'] ?? null,
                'status' => $checkin['status'] ?? null,
            ]);
        }

        // Tambahkan data check-out
        foreach ($checkoutResults as $checkout) {
            $key = "{$checkout['npk']}-{$checkout['tanggal']}";
            if ($allResults->has($key)) {
                $allResults->get($key)['waktuco'] = $checkout['waktuco'] ?? 'NO OUT';
            } else {
                $allResults->put($key, [
                    'nama' => $checkout['nama'],
                    'npk' => $checkout['npk'],
                    'tanggal' => $checkout['tanggal'],
                    'waktuci' => null,
                    'waktuco' => $checkout['waktuco'] ?? 'NO OUT',
                    'shift' => $checkout['shift'] ?? null,
                    'status' => $checkout['status'] ?? null,
                ]);
            }
        }

        // Tambahkan data no-check
        foreach ($noCheckResults as $noCheck) {
            $key = "{$noCheck['npk']}-{$noCheck['tanggal']}";
            if (!$allResults->has($key)) {
                $allResults->put($key, [
                    'nama' => $noCheck['nama'],
                    'npk' => $noCheck['npk'],
                    'tanggal' => $noCheck['tanggal'],
                    'waktuci' => 'NO IN',
                    'waktuco' => 'NO OUT',
                    'shift' => $noCheck['shift'] ?? null,
                    'status' => $noCheck['status'] ?? 'Mangkir',
                ]);
            }
        }

        return $allResults->values();
    }


    private function getCheckinData($selectedDay)
    {
        return AbsensiCi::with(['user', 'shift'])
            ->select('npk', 'tanggal', DB::raw('MIN(waktuci) as waktuci'))
            ->whereDate('tanggal', '=', $selectedDay)
            ->groupBy('npk', 'tanggal')
            ->get()
            ->mapWithKeys(function ($checkin) {
                $key = "{$checkin->npk}-{$checkin->tanggal}";

                // Ambil shift
                $latestShift = shift::where('npk', $checkin->npk)
                    ->where('date', $checkin->tanggal)
                    ->latest()
                    ->first();
                $shift1 = $latestShift ? $latestShift->shift1 : null;

                // Ambil role pengguna
                $role = $checkin->user ? $checkin->user->role : null;

                $status = 'No Shift';
                if ($role && in_array($role->id, [5, 8])) {
                    $status = 'Tepat Waktu';
                } elseif ($latestShift) {
                    if (strtoupper($shift1) === 'OFF') {
                        $status = 'OFF';
                    } else {
                        $shiftIn = explode(' - ', str_replace('.', ':', $shift1))[0];
                        $shiftInFormatted = date('H:i:s', strtotime($shiftIn));
                        $status = $checkin->waktuci > $shiftInFormatted ? 'Terlambat' : 'Tepat Waktu';
                    }
                }

                return [
                    $key => [
                        'nama' => $checkin->user ? $checkin->user->nama : '',
                        'npk' => $checkin->npk,
                        'tanggal' => $checkin->tanggal,
                        'waktuci' => $checkin->waktuci,
                        'waktuco' => null, // Update jika ada data checkout
                        'shift' => $shift1,
                        'status' => ($role && in_array($role->id, [4, 5, 8])) ? $status : ($shift1 === null ? 'Mangkir' : $status),
                    ]
                ];
            });
    }


    private function getCheckoutData($selectedDay)
    {
        return AbsensiCo::with(['user', 'shift'])
            ->select('npk', 'tanggal', DB::raw('MAX(waktuco) as waktuco'))
            ->whereDate('tanggal', '=', $selectedDay)
            ->groupBy('npk', 'tanggal')
            ->get()
            ->mapWithKeys(function ($checkout) {
                // Ambil shift terbaru
                $latestShift = Shift::where('npk', $checkout->npk)
                    ->where('date', $checkout->tanggal)
                    ->latest()
                    ->first();
                $shift1 = $latestShift ? $latestShift->shift1 : null;

                // Tentukan status default
                $status = 'NO IN';

                $role = $checkout->user ? $checkout->user->role : null;
                if ($role && in_array($role->id, [5, 8])) {
                    $status = 'Tepat Waktu';
                }

                $key = "{$checkout->npk}-{$checkout->tanggal}";
                return [
                    $key => [
                        'nama' => $checkout->user->nama ?? 'Tidak Diketahui',
                        'npk' => $checkout->npk,
                        'tanggal' => $checkout->tanggal,
                        'waktuci' => null,
                        'waktuco' => $checkout->waktuco,
                        'shift' => $shift1,
                        'status' => $status,
                    ]
                ];
            });
    }


    private function getNoCheckData($selectedDay)
    {
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
            ->where('kategorishift.date', '=', $selectedDay)
            ->where('kategorishift.shift1', '!=', 'OFF')
            ->groupBy('kategorishift.npk', 'kategorishift.date', 'kategorishift.shift1')
            ->get();

        $results = [];

        foreach ($noCheckData as $noCheck) {
            $key = "{$noCheck->npk}-{$noCheck->tanggal}";
            $role = $noCheck->user ? $noCheck->user->role : null;

            $latestShift = shift::where('npk', $noCheck->npk)
                ->where('date', $noCheck->tanggal)
                ->orderBy('date', 'desc')
                ->latest('created_at')
                ->first();

            $shift1 = $latestShift ? $latestShift->shift1 : null;
            $currentTime = now();
            $shiftStartTime = null;

            if ($shift1 && !in_array($shift1, ['Dinas Luar Stand By', 'OFF']) && strpos($shift1, ' - ') !== false) {
                $shiftTimes = explode(' - ', $shift1);
                if (count($shiftTimes) == 2 && preg_match('/^\d{2}:\d{2}$/', $shiftTimes[0])) {
                    $shiftStartTime = Carbon::createFromFormat('H:i', $shiftTimes[0]);
                } else {
                    $shiftStartTime = null;
                    Log::warning("Format shift tidak valid: " . $shift1);
                }
            }

            if ($shift1 === "OFF") {
                $status = "OFF";
            } elseif ($shift1 === "Dinas Luar Stand By Off") {
                $status = "Dinas Luar Stand By";
            } elseif ($role && in_array($role->id, [5, 8])) {
                $status = 'Tepat Waktu';
            } elseif (!isset($results[$key])) {
                $status = ($shift1 === "Dinas Luar Stand By") ? "Dinas Luar Stand By" : "Mangkir";
            } elseif ($shiftStartTime && $currentTime->gt($shiftStartTime) && $noCheck->waktuci === 'NO IN' && $noCheck->waktuco === 'NO OUT') {
                $status = "Mangkir";
            }

            if (!isset($results[$key])) {
                $results[$key] = [
                    'nama' => $noCheck->user ? $noCheck->user->nama : 'Tidak Diketahui',
                    'npk' => $noCheck->npk,
                    'tanggal' => $noCheck->tanggal,
                    'waktuci' => ($shift1 === "OFF" || $shift1 === "Dinas Luar Stand By Off") ? '----' : 'NO IN',
                    'waktuco' => ($shift1 === "OFF" || $shift1 === "Dinas Luar Stand By Off") ? '----' : 'NO OUT',
                    'shift' => $shift1,
                    'status' => $status,
                ];
            }
        }

        return $results;
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
                ->whereIn('approved_by', [2, 3, 4, 5, 8, 10])
                ->get();

            $kategoriCuti = $cutiModels->pluck('kategori')->first();

            // Cek data penyimpangan
            $penyimpangan = PenyimpanganModel::where('npk', $npk)
                ->where(function ($query) use ($tanggal) {
                    $query->where('tanggal_mulai', '<=', $tanggal)
                        ->whereRaw('COALESCE(tanggal_selesai, tanggal_mulai) >= ?', [$tanggal]);
                })
                ->whereIn('approved_by', [2, 3, 4, 5, 8, 10])
                ->first();

            $kategoriPenyimpangan = $penyimpangan->kategori ?? null;

            // Update data dengan status
            $row['status'] = $kategoriCuti ?: ($kategoriPenyimpangan ?: $row['status']);
            return $row;
        });
    }


    private function sendWarningMessage($users, $sortedResults)
    {
        foreach ($users as $user) {
            $userResults = $sortedResults->where('npk', $user->npk);
            foreach ($userResults as $result) {
                $message = "";
                $tanggal = $result['tanggal'] ?? 'Tidak diketahui';
                if ($result['status'] === 'Terlambat') {
                    $waktuTerlambat = $result['waktuci'] ?? 'Tidak diketahui';
                    $message = "Anda terlambat pada tanggal {$tanggal} pukul {$waktuTerlambat}. Mohon perbaiki absensi Anda.";
                } elseif ($result['status'] === 'Mangkir') {
                    $message = "Anda tidak hadir pada tanggal {$tanggal}. Mohon perbaiki absensi Anda.";
                }
                if ($message) {
                    $data = [
                        'destination' => $user->no_telp,
                        'message' => $message,
                    ];
                    $apiGateway = new apiGatewayController();
                    $apiGateway->sendMessage($data);
                }
            }
        }
    }
}
