<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AbsensiciSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Menghapus data lama sebelum menambahkan data baru
        DB::table('absensici')->truncate();
        DB::table('absensico')->truncate();
        DB::table('pcd_login_logs')->truncate();
        DB::table('pcd_master_users')->truncate();

        // Menambahkan data dummy ke pcd_master_users dengan 1 npk
        $npk = $faker->unique()->numberBetween(1000, 9999); // NPK 4 digit
        $nama = $faker->name;

        $userId = DB::table('pcd_master_users')->insertGetId([
            'nama' => $nama,
            'npk' => $npk,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Generate data absen untuk hampir setiap hari dalam satu tahun
        $startDate = strtotime('2024-01-01');
        $endDate = strtotime('2024-12-31');

        // Iterasi dari tanggal mulai hingga tanggal akhir
        while ($startDate <= $endDate) {
            $tanggal = date('Y-m-d', $startDate);
            $waktuci = $this->generateRandomTime();
            $waktuco = $this->generateRandomCheckoutTime();

            // Insert data ke tabel absensici
            DB::table('absensici')->insert([
                'nama' => $nama,
                'npk' => $npk,
                'tanggal' => $tanggal,
                'waktuci' => $waktuci
            ]);

            // Insert data ke tabel absensico
            DB::table('absensico')->insert([
                'nama' => $nama,
                'npk' => $npk,
                'tanggal' => $tanggal,
                'waktuco' => $waktuco
            ]);

            // Mengatur waktu created_at di pcd_login_logs
            $createdAt = $this->generateLoginLogTimestamp($waktuci);

            // Insert data ke tabel pcd_login_logs
            DB::table('pcd_login_logs')->insert([
                'user_id' => $userId,
                'station_id' => $faker->numberBetween(1, 5), // Assuming station_id is between 1 and 5
                'status' => $faker->word,
                'created_at' => $createdAt,
                'updated_at' => now(),
            ]);

            // Increment the date by one day
            $startDate = strtotime("+1 day", $startDate);
        }
    }

    /**
     * Generate a random check-in time between 06:50:00 and 07:20:00.
     *
     * @return string
     */
    private function generateRandomTime()
    {
        $start = strtotime('06:50:00');
        $end = strtotime('07:20:00');
        $randomTime = date('H:i:s', mt_rand($start, $end));

        return $randomTime;
    }

    /**
     * Generate a random checkout time between 16:50:00 and 18:30:00.
     *
     * @return string
     */
    private function generateRandomCheckoutTime()
    {
        $start = strtotime('16:50:00');
        $end = strtotime('18:30:00');
        $randomTime = date('H:i:s', mt_rand($start, $end));

        return $randomTime;
    }

    /**
     * Generate a timestamp for pcd_login_logs that is approximately 10 minutes after the check-in time.
     *
     * @param string $waktuci
     * @return string
     */
    private function generateLoginLogTimestamp($waktuci)
    {
        $checkInTime = strtotime($waktuci);
        $timeOffset = rand(10 * 60, 20 * 60); // Random offset between 10 and 20 minutes
        $createdAt = date('Y-m-d H:i:s', $checkInTime + $timeOffset);

        return $createdAt;
    }
}
