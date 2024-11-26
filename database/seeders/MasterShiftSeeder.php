<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterShift;

class MasterShiftSeeder extends Seeder
{
    public function run()
    {
        MasterShift::insert([
            ['shift_name' => 'Shift 1', 'waktu' => '06:00 - 15:00'],
            ['shift_name' => 'Shift 2', 'waktu' => '07:00 - 16:00'],
            ['shift_name' => 'Shift 3', 'waktu' => '14:00 - 23:00'],
            ['shift_name' => 'Shift 4', 'waktu' => '13:00 - 22:00'],
            ['shift_name' => 'Shift 5', 'waktu' => '21:00 - 06:00'],
            ['shift_name' => 'Shift 6', 'waktu' => '22:00 - 07:00'],
            ['shift_name' => 'Shift 7', 'waktu' => '23:00 - 08:00'],
            ['shift_name' => 'Shift 8', 'waktu' => '06:00 - 15:20'],
            ['shift_name' => 'Shift 9', 'waktu' => '07:00 - 16:30'],
            ['shift_name' => 'Shift 10', 'waktu' => '15:00 - 00:00'],
            ['shift_name' => 'Shift 11', 'waktu' => '16:00 - 01:00'],
            ['shift_name' => 'Shift 12', 'waktu' => '08:00 - 17:20'],
            ['shift_name' => 'Shift 13', 'waktu' => '09:00 - 18:20'],
            ['shift_name' => 'Shift 14', 'waktu' => '08:00 - 17:00'],
            ['shift_name' => 'Shift 15', 'waktu' => 'OFF'],
            ['shift_name' => 'Shift 16', 'waktu' => 'Dinas Luar Stand By'],
        ]);
    }
}
