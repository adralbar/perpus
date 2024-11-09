<?php

namespace Database\Seeders;

use App\Models\RoleModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleData = [
            [
                'nama' => 'superadmin'
            ],
            [
                'nama' => 'admin'
            ],
            [
                'nama' => 'section head'
            ],
            [
                'nama' => 'department head'
            ],
            [
                'nama' => 'division head'
            ],
            [
                'nama' => 'hrd'
            ],
            [
                'nama' => 'karyawan'
            ],
            [
                'nama' => 'Direksi'
            ],
            [
                'nama' => 'Admin Department'
            ],
        ];
        foreach ($roleData as $key => $val) {
            RoleModel::create($val);
        };
    }
}
