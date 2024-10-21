<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;

class AbsensiciSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mendapatkan semua npk dari tabel users
        $users = User::select('npk')->get();

        // Manual range tanggal (misal dari 1 Oktober 2024 sampai 7 Oktober 2024)
        $dateRange = [
            'start' => '2024-10-26',
            'end'   => '2024-12-25',
        ];

        // Manual range waktu (misal dari jam 16:00:00 sampai 18:00:00)
        $timeRange = [
            'start' => '06:30:00',
            'end'   => '07:30:00',
        ];

        // Looping setiap pengguna dan mengisi absen
        foreach ($users as $user) {
            // Looping tanggal berdasarkan range yang diberikan
            $startDate = Carbon::parse($dateRange['start']);
            $endDate = Carbon::parse($dateRange['end']);

            while ($startDate->lte($endDate)) {
                // Generate waktu random antara start dan end
                $randomTime = Carbon::createFromTimeString($timeRange['start'])
                    ->addMinutes(rand(0, Carbon::createFromTimeString($timeRange['end'])->diffInMinutes($timeRange['start'])));

                // Insert data ke tabel absensico
                DB::table('absensici')->insert([
                    'npk'      => $user->npk,
                    'tanggal'  => $startDate->format('Y-m-d'),
                    'waktuci'  => $randomTime->format('H:i:s'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Lanjut ke tanggal berikutnya
                $startDate->addDay();
            }
        }
    }
}
