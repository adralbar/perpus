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

        // Menambahkan data dummy ke absensici dan absensico
        foreach (range(2000, 9999) as $index) {
            $npk = $faker->unique()->numberBetween(100, 999); // NPK 3 digit
            $tanggal = $this->generateRandomDate(); // Generate tanggal antara Januari 2024 - Desember 2024
            $waktuci = $this->generateRandomTime(); // Generate waktu check-in antara 06:50:00 - 07:20:00
            $waktuco = $this->generateRandomCheckoutTime(); // Generate waktu checkout antara 16:50:00 - 18:30:00

            // Insert data ke tabel absensici
            DB::table('absensici')->insert([
                'nama' => $faker->name,
                'npk' => $npk,
                'tanggal' => $tanggal,
                'waktuci' => $waktuci
            ]);

            // Insert data ke tabel absensico
            DB::table('absensico')->insert([
                'nama' => $faker->name,
                'npk' => $npk,
                'tanggal' => $tanggal,
                'waktuco' => $waktuco
            ]);
        }
    }

    /**
     * Generate a random date between January 2024 and December 2024.
     *
     * @return string
     */
    private function generateRandomDate()
    {
        $start = strtotime('2024-01-01');
        $end = strtotime('2024-12-31');
        $randomDate = date('Y-m-d', mt_rand($start, $end));

        return $randomDate;
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
}
