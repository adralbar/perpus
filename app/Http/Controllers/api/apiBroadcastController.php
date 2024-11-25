<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use App\Models\absensici;
use App\Models\peringatanModel;
use App\Models\shift;
use App\Models\User;
use Carbon\Carbon;
class apiBroadcastController extends Controller
{
    public function checkLateAndAbsent()
    {
        set_time_limit(0);

        // Ambil semua pengguna mulai dari id 42
        $users = User::where('id', '>=', 42)->get();

        // Ambil tanggal hari kemarin
        $yesterday = Carbon::yesterday();

        foreach ($users as $user) {
            // Ambil semua shift untuk pengguna berdasarkan `npk` yang terjadi kemarin
            $shifts = shift::where('npk', $user->npk)
                ->whereDate('date', $yesterday) // Filter shift yang terjadi kemarin
                ->get();

            foreach ($shifts as $shift) {
                // Pisahkan waktu awal shift dari `shift1` (format "07:00 - 16:00")
                $shiftStart = explode(' - ', $shift->shift1)[0];

                // Periksa absensi pada tanggal shift
                $absensi = absensici::where('npk', $user->npk)
                    ->whereDate('tanggal', $yesterday) // Pastikan absensi juga terjadi kemarin
                    ->first();

                // Cek keterlambatan atau absensi
                if ($absensi) {
                    // Jika ada absensi, cek keterlambatan
                    if ($absensi->waktuci > $shiftStart) {
                        $kategori = 'telat';

                        // Cek apakah ada duplikasi peringatan untuk pengguna ini
                        $existingWarning = peringatanModel::where('user_id', $user->id)
                            ->where('kategori', $kategori)
                            ->orderBy('created_at', 'desc')
                            ->first();

                        if ($existingWarning) {
                            if ($existingWarning->jumlah < 3) {
                                // Perbarui jumlah peringatan
                                $existingWarning->jumlah++;
                                $existingWarning->save();

                                // Kirim peringatan jika jumlah mencapai 3
                                if ($existingWarning->jumlah == 3) {
                                    $this->sendWarningMessage($user, $kategori);
                                }
                            } else {
                                // Jika sudah lebih dari 3, buat peringatan baru
                                peringatanModel::create([
                                    'user_id' => $user->id,
                                    'kategori' => $kategori,
                                    'jumlah' => 1,
                                ]);
                            }
                        } else {
                            // Jika belum ada peringatan, buat baru
                            peringatanModel::create([
                                'user_id' => $user->id,
                                'kategori' => $kategori,
                                'jumlah' => 1,
                            ]);
                        }
                    }
                } else {
                    // Jika tidak ada absensi, hitung sebagai mangkir
                    $kategori = 'mangkir';

                    // Cek apakah ada duplikasi peringatan untuk pengguna ini
                    $existingWarning = peringatanModel::where('user_id', $user->id)
                        ->where('kategori', $kategori)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    if ($existingWarning) {
                        if ($existingWarning->jumlah < 3) {
                            // Perbarui jumlah peringatan
                            $existingWarning->jumlah++;
                            $existingWarning->save();

                            // Kirim peringatan jika jumlah mencapai 3
                            if ($existingWarning->jumlah == 3) {
                                $this->sendWarningMessage($user, $kategori);
                            }
                        } else {
                            // Jika sudah lebih dari 3, buat peringatan baru
                            peringatanModel::create([
                                'user_id' => $user->id,
                                'kategori' => $kategori,
                                'jumlah' => 1,
                            ]);
                        }
                    } else {
                        // Jika belum ada peringatan, buat baru
                        peringatanModel::create([
                            'user_id' => $user->id,
                            'kategori' => $kategori,
                            'jumlah' => 1,
                        ]);
                    }
                }
            }
        }

        return response()->json(['message' => 'Peringatan untuk hari kemarin telah diproses.']);
    }


    private function sendWarningMessage($user, $kategori)
    {
        $data = [
            'destination' => $user->no_telp,
            'message' => "Anda telah {$kategori} sebanyak 3 kali. Mohon perbaiki absensi Anda.",
        ];

        $apiGateway = new apiGatewayController();
        return $apiGateway->sendMessage($data);
    }
}