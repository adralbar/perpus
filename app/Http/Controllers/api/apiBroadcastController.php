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
        try {
            $yesterday = '2024-12-12';

            $checkinResults = $this->getCheckinData($yesterday) ?? collect([]);
            $checkoutResults = $this->getCheckoutData($yesterday) ?? collect([]);
            $noCheckResults = $this->getNoCheckData($yesterday) ?? collect([]);

            $mergedResults = $checkinResults->merge($checkoutResults)->merge($noCheckResults);
            $results = $this->processAdditionalData($mergedResults);
            $sortedResults = $results->sortBy('tanggal')->values();

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


    private function getCheckinData($yesterday)
    {
        return AbsensiCi::with(['user', 'shift'])
            ->select('npk', 'tanggal', DB::raw('MIN(waktuci) as waktuci'))
            ->whereDate('tanggal', '=', $yesterday)
            ->groupBy('npk', 'tanggal')
            ->get()
            ->mapWithKeys(function ($checkin) {
                $key = "{$checkin->npk}-{$checkin->tanggal}";
                return [
                    $key => [
                        'nama' => $checkin->user->nama ?? 'Tidak Diketahui',
                        'npk' => $checkin->npk,
                        'tanggal' => $checkin->tanggal,
                        'waktuci' => $checkin->waktuci,
                        'waktuco' => null,
                        'status' => $this->determineStatus($checkin),
                    ]
                ];
            });
    }

    private function getCheckoutData($yesterday)
    {
        return AbsensiCo::with(['user', 'shift'])
            ->select('npk', 'tanggal', DB::raw('MAX(waktuco) as waktuco'))
            ->whereDate('tanggal', '=', $yesterday)
            ->groupBy('npk', 'tanggal')
            ->get()
            ->mapWithKeys(function ($checkout) {
                $key = "{$checkout->npk}-{$checkout->tanggal}";
                return [
                    $key => [
                        'nama' => $checkout->user->nama ?? 'Tidak Diketahui',
                        'npk' => $checkout->npk,
                        'tanggal' => $checkout->tanggal,
                        'waktuci' => null,
                        'waktuco' => $checkout->waktuco,
                        'status' => 'NO IN',
                    ]
                ];
            });
    }

    private function getNoCheckData($yesterday)
    {
        return shift::with(['user.section.department.division', 'user.role'])
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
            ->where('kategorishift.date', '=', $yesterday)
            ->where('kategorishift.shift1', '!=', 'OFF')
            ->groupBy('kategorishift.npk', 'kategorishift.date', 'kategorishift.shift1') // Add GROUP BY
            ->get()
            ->mapWithKeys(function ($noCheck) {
                $key = "{$noCheck->npk}-{$noCheck->tanggal}";
                return [
                    $key => [
                        'nama' => $noCheck->user->nama ?? 'Tidak Diketahui',
                        'npk' => $noCheck->npk,
                        'tanggal' => $noCheck->tanggal,
                        'waktuci' => $noCheck->waktuci,
                        'waktuco' => $noCheck->waktuco,
                        'status' => 'Mangkir',
                    ]
                ];
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
                ->whereIn('approved_by', [3, 4, 5])
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

    private function determineStatus($checkin)
    {
        // Ambil shift
        $latestShift = shift::where('npk', $checkin->npk)
            ->where('date', $checkin->tanggal)
            ->latest()
            ->first();
        $shift1 = $latestShift ? $latestShift->shift1 : null;

        // Default status
        $status = 'No Shift';

        // Ambil role pengguna
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
        // dd($role);
        return $status;
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
                        'destination' => '000',
                        'message' => $message,
                    ];
                    $apiGateway = new apiGatewayController();
                    $apiGateway->sendMessage($data);
                }
            }
        }
    }
}
