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
            'npk_sistem' => 'EA2401079',
            'npk' => 'H903',
            'nama' => 'HASAN BASRI',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => 'EA2401090',
            'npk' => 'H904',
            'nama' => 'M.NASRUL FAHMI',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => 'EA2402092',
            'npk' => 'H905',
            'nama' => 'FAUZI ACHMAD',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => 'EA2407096',
            'npk' => 'H906',
            'nama' => 'YAHYA',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => 'EA2407097',
            'npk' => 'H907',
            'nama' => 'ROBI',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => 'EA2408101',
            'npk' => 'H908',
            'nama' => 'MARSEL HERMAWAN',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => 'EA2408103',
            'npk' => 'H909',
            'nama' => 'RIXY FERDIANSYAH',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => 'EA2408105',
            'npk' => 'H910',
            'nama' => 'RIAN DARMALA',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => 'EA2401081',
            'npk' => 'H911',
            'nama' => 'AHMAD MUHAJIR',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => 'EA2401082',
            'npk' => 'H912',
            'nama' => 'BONY ARYANTY',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => 'EA2401084',
            'npk' => 'H913',
            'nama' => 'AHMAD',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => 'EA2401085',
            'npk' => 'H914',
            'nama' => 'ARDIANSYAH SAPUTRA',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => 'EA2401086',
            'npk' => 'H915',
            'nama' => 'BAMBANG SUNARDI',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => 'EA2401088',
            'npk' => 'H916',
            'nama' => 'SURYADI',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => 'EA2401089',
            'npk' => 'H917',
            'nama' => 'WAHYU PANJI ISWANDI',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2206004',
            'npk' => 'H918',
            'nama' => 'TAJUDIN',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2206005',
            'npk' => 'H919',
            'nama' => 'MUHAMAD FAIZAL RAMDANI',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2206008',
            'npk' => 'H920',
            'nama' => 'RAFIQ AVONA ANWAR',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2207012',
            'npk' => 'H921',
            'nama' => 'SUPRIYADI',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2207016',
            'npk' => 'H922',
            'nama' => 'SULISTYO PRATIKO PUTRA',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2207022',
            'npk' => 'H923',
            'nama' => 'RIDWAN',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2207024',
            'npk' => 'H924',
            'nama' => 'YANUAR RISKI SAPUTRA',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2207026',
            'npk' => 'H925',
            'nama' => 'FAJAR RAMADHAN',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2207038',
            'npk' => 'H926',
            'nama' => 'MUSTOPA',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2207041',
            'npk' => 'H927',
            'nama' => 'CHOES RENGGO ADITYA',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2209065',
            'npk' => 'H928',
            'nama' => 'ARIF SAEFUDIN ',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2209066',
            'npk' => 'H929',
            'nama' => 'SIGIT HARYANTO',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2209070',
            'npk' => 'H930',
            'nama' => 'RAIHAN KURNIAWAN',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2209071',
            'npk' => 'H931',
            'nama' => 'FAUZY ILHAM',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2209074',
            'npk' => 'H932',
            'nama' => 'CANGGIH PRAMONO',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2209075',
            'npk' => 'H933',
            'nama' => 'HENDRY ALPIAN ',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2209076',
            'npk' => 'H934',
            'nama' => 'WISNU ADI SAPUTRO',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2301080',
            'npk' => 'H935',
            'nama' => 'R. DIDIK KURNIADI ',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2302096',
            'npk' => 'H936',
            'nama' => 'HERMAN PRIATNO',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2305116',
            'npk' => 'H937',
            'nama' => 'AFERI YANSAH ',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2305118',
            'npk' => 'H938',
            'nama' => 'FARHAN ZAENURI ',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2307123',
            'npk' => 'H939',
            'nama' => 'AHMAD RIZKI MAULANA',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2307129',
            'npk' => 'H940',
            'nama' => 'HABIBI MAULANA ',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2307132',
            'npk' => 'H941',
            'nama' => 'MUHAMMAD AZHAR HABIB',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2307141',
            'npk' => 'H942',
            'nama' => 'AFIQ ARWANI',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2307143',
            'npk' => 'H943',
            'nama' => 'AKMAL',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2307144',
            'npk' => 'H944',
            'nama' => 'BAYU SUMANTRI ',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2308148',
            'npk' => 'H945',
            'nama' => 'MUHAMMAD DARMA PANUNTUN',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2311152',
            'npk' => 'H946',
            'nama' => 'AQMAL MAULANA YUSUF',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2311153',
            'npk' => 'H947',
            'nama' => 'HENDRA',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2311155',
            'npk' => 'H948',
            'nama' => 'MUHAMMAD FIKI ',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2311157',
            'npk' => 'H949',
            'nama' => 'MUHAMMAD ALFIN RAMADHAN ',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2311158',
            'npk' => 'H950',
            'nama' => 'GALIH MUSTOPA',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);

        User::create([
            'npk_sistem' => '2501161',
            'npk' => 'H951',
            'nama' => 'GILANG NURYANTO ',
            'password' => bcrypt('1234'),
            'no_telp' => '081329964278',
            'section_id' => '51',
            'department_id' => '22',
            'division_id' => '1',
            'role_id' => '7',  // default
        ]);
    }
}
