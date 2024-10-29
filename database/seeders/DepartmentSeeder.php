<?php

namespace Database\Seeders;

use App\Models\DepartmentModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $DepartmentData = [
            [
                'nama' => 'PPIC',
                'division_id' => '1'
            ],
            [
                'nama' => 'PRODUCTION',
                'division_id' => '1'
            ],
            [
                'nama' => 'ENGINEERING',
                'division_id' => '2'
            ],
            [
                'nama' => 'ADMIN',
                'division_id' => '3'
            ],
            [
                'nama' => 'QUALITY',
                'division_id' => '1'
            ],
            [
                'nama' => 'MANAGEMENT',
                'division_id' => '3'
            ],
            [
                'nama' => 'ADMINISTRATOR',
                'division_id' => '3'
            ],
            [
                'nama' => 'AUTOMATION & UTILITY',
                'division_id' => '1'
            ],
            [
                'nama' => 'MAINTENANCE',
                'division_id' => '2'
            ],
            [
                'nama' => 'MOLD ENGINEERING',
                'division_id' => '2'
            ],
            [
                'nama' => 'LOGISTIC',
                'division_id' => '2'
            ],
            [
                'nama' => 'SARANA',
                'division_id' => '2'
            ],
            [
                'nama' => 'TECHNICAL SUPPORT',
                'division_id' => '1'
            ],
            [
                'nama' => 'WAREHOUSE',
                'division_id' => '2'
            ],
            [
                'nama' => 'FINANCE & ACCOUNTING',
                'division_id' => '2'
            ],
            [
                'nama' => 'HC & GA',
                'division_id' => '3'
            ],
            [
                'nama' => 'MARKETING',
                'division_id' => '2'
            ],
            [
                'nama' => 'MOLD & TOOLING DEVELOPMENT',
                'division_id' => '2'
            ],
            [
                'nama' => 'PROCUREMENT',
                'division_id' => '3'
            ],
            [
                'nama' => 'VENDOR MANAGEMENT',
                'division_id' => '3'
            ],
        ];
        foreach($DepartmentData as $key => $val){
            DepartmentModel::create($val);
        }
    }
}
