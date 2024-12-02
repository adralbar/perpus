<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserUpdate extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       

        User::create([
            'npk_sistem' => '91758',
            'npk' => 'H827',
            'nama' => 'Fajar Bahtiar',
            'division_id' => 1,
            'department_id' => 1,
            'section_id' => 44,
            'no_telp' => '6281234567891',
            'password' => Hash::make('1234')
        ]);

        User::create([
            'npk_sistem' => '91759',
            'npk' => 'H828',
            'nama' => 'Jejen Jaelani',
            'division_id' => 1,
            'department_id' => 1,
            'section_id' => 44,
            'no_telp' => '6281234567892',
            'password' => Hash::make('1234')
        ]);

        User::create([
            'npk_sistem' => '91760',
            'npk' => 'H829',
            'nama' => 'Asep Mulyadi',
            'division_id' => 1,
            'department_id' => 1,
            'section_id' => 44,
            'no_telp' => '6281234567893',
            'password' => Hash::make('1234')
        ]);

        User::create([
            'npk_sistem' => '91761',
            'npk' => 'H830',
            'nama' => 'Roby Imam Maulana',
            'division_id' => 1,
            'department_id' => 1,
            'section_id' => 44,
            'no_telp' => '6281234567894',
            'password' => Hash::make('1234')
        ]);

        User::create([
            'npk_sistem' => '91762',
            'npk' => 'M1028',
            'nama' => 'Jovan Firdiansyah',
            'division_id' => 1,
            'department_id' => 1,
            'section_id' => 44,
            'no_telp' => '6281234567895',
            'password' => Hash::make('1234')
        ]);

        User::create([
            'npk_sistem' => '91763',
            'npk' => 'M1029',
            'nama' => 'Tiar Agustiawan',
            'division_id' => 1,
            'department_id' => 1,
            'section_id' => 44,
            'no_telp' => '6281234567896',
            'password' => Hash::make('1234')
        ]);

        User::create([
            'npk_sistem' => '91764',
            'npk' => 'M1030',
            'nama' => 'Akbar Rizki Nur Komar',
            'division_id' => 1,
            'department_id' => 1,
            'section_id' => 44,
            'no_telp' => '6281234567897',
            'password' => Hash::make('1234')
        ]);

        User::create([
            'npk_sistem' => '91765',
            'npk' => 'M1031',
            'nama' => 'Nazif Afifi',
            'division_id' => 1,
            'department_id' => 1,
            'section_id' => 44,
            'no_telp' => '6281234567898',
            'password' => Hash::make('1234')
        ]);

        User::create([
            'npk_sistem' => '91766',
            'npk' => 'M1032',
            'nama' => 'Rikho Agustian',
            'division_id' => 1,
            'department_id' => 2,
            'section_id' => 25,
            'no_telp' => '6281234567899',
            'password' => Hash::make('1234')
        ]);

        User::create([
            'npk_sistem' => '91767',
            'npk' => 'M1033',
            'nama' => 'Taufiq Hardiansyah',
            'division_id' => 1,
            'department_id' => 2,
            'section_id' => 25,
            'no_telp' => '6281234567800',
            'password' => Hash::make('1234')
        ]);

        User::create([
            'npk_sistem' => '91768',
            'npk' => 'M1034',
            'nama' => 'Abi Setiya',
            'division_id' => 1,
            'department_id' => 2,
            'section_id' => 25,
            'no_telp' => '6281234567801',
            'password' => Hash::make('1234')
        ]);

        User::create([
            'npk_sistem' => '91769',
            'npk' => 'M1035',
            'nama' => 'Aa Jati Maulana',
            'division_id' => 1,
            'department_id' => 2,
            'section_id' => 25,
            'no_telp' => '6281234567802',
            'password' => Hash::make('1234')
        ]);

        User::create([
            'npk_sistem' => '91770',
            'npk' => 'M1036',
            'nama' => 'M. Ikhwan',
            'division_id' => 1,
            'department_id' => 2,
            'section_id' => 25,
            'no_telp' => '6281234567803',
            'password' => Hash::make('1234')
        ]);

        User::create([
            'npk_sistem' => '91771',
            'npk' => 'M1037',
            'nama' => 'Rey Tegar Raditiya',
            'division_id' => 1,
            'department_id' => 2,
            'section_id' => 25,
            'no_telp' => '6281234567804',
            'password' => Hash::make('1234')
        ]);

        User::create([
            'npk_sistem' => '91772',
            'npk' => 'M1038',
            'nama' => 'Rio Saputra',
            'division_id' => 1,
            'department_id' => 2,
            'section_id' => 25,
            'no_telp' => '6281234567805',
            'password' => Hash::make('1234')
        ]);

        User::create([
            'npk_sistem' => '91773',
            'npk' => 'M1039',
            'nama' => 'Upung Ependi',
            'division_id' => 1,
            'department_id' => 2,
            'section_id' => 25,
            'no_telp' => '6281234567806',
            'password' => Hash::make('1234')
        ]);
    }
}
