<?php

namespace Database\Seeders;

use App\Models\DivisionModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisionData = [
            [
                'nama' => 'PLANT'
            ],
            [
                'nama' => 'BUSSINES DEV. & ENG'
            ],
            [
                'nama' => 'ADMIN'
            ],
        ];
        foreach($divisionData as $key => $val) {
            DivisionModel::create($val);
        };
    }
}
