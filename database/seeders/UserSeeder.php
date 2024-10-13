<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usersData = [
            [
                'npk' => 'M231',
                'nama' => 'Arif superadmin',
                'password' => bcrypt('1234'),
                'no_telp' => '081329964278',
                'section_id' => '1',
                'department_id' => '1',
                'division_id' => '1',
                'role_id' => '1',
            ],
            [
                'npk' => 'A439',
                'nama' => 'Arif admin',
                'password' => bcrypt('1234'),
                'no_telp' => '081329964278',
                'section_id' => '1',
                'department_id' => '1',
                'division_id' => '1',
                'role_id' => '2',
            ],
            [
                'npk' => '8762',
                'nama' => 'Arif section',
                'password' => bcrypt('1234'),
                'no_telp' => '081329964278',
                'section_id' => '1',
                'department_id' => '1',
                'division_id' => '1',
                'role_id' => '3',
            ],
            [
                'npk' => '4278',
                'nama' => 'Arif department',
                'password' => bcrypt('1234'),
                'no_telp' => '081329964278',
                'section_id' => '1',
                'department_id' => '1',
                'division_id' => '1',
                'role_id' => '4',
            ],
            [
                'npk' => '0976',
                'nama' => 'Arif division',
                'password' => bcrypt('1234'),
                'no_telp' => '081329964278',
                'section_id' => '1',
                'department_id' => '1',
                'division_id' => '1',
                'role_id' => '5',
            ],
            [
                'npk' => 'T876',
                'nama' => 'Arif hrd',
                'password' => bcrypt('1234'),
                'no_telp' => '081329964278',
                'section_id' => '1',
                'department_id' => '1',
                'division_id' => '1',
                'role_id' => '6',
            ],
            // Karyawan Dept. Technical Support Baru
            [
                'npk' => '9920',
                'nama' => 'Eko sja',
                'password' => bcrypt('1234'),
                'no_telp' => '08267719290',
                'section_id' => '35',
                'department_id' => '13',
                'division_id' => '1',
            ],
            [
                'npk' => '9921',
                'nama' => 'Yusup saja',
                'password' => bcrypt('1234'),
                'no_telp' => '08267719291',
                'section_id' => '35',
                'department_id' => '13',
                'division_id' => '1',
            ],
            [
                'npk' => '9922',
                'nama' => 'Albar saja',
                'password' => bcrypt('1234'),
                'no_telp' => '08267719292',
                'section_id' => '35',
                'department_id' => '13',
                'division_id' => '1',
            ],
            [
                'npk' => '9923',
                'nama' => 'Seno saja',
                'password' => bcrypt('1234'),
                'no_telp' => '08267719293',
                'section_id' => '34',
                'department_id' => '13',
                'division_id' => '1',
            ],
            [
                'npk' => '9924',
                'nama' => 'Bayu saja',
                'password' => bcrypt('1234'),
                'no_telp' => '08267719294',
                'section_id' => '34',
                'department_id' => '13',
                'division_id' => '1',
            ],
            [
                'npk' => '9925',
                'nama' => 'Wira saja',
                'password' => bcrypt('1234'),
                'no_telp' => '08267719295',
                'section_id' => '34',
                'department_id' => '13',
                'division_id' => '1',
            ],
            [
                'npk' => '9926',
                'nama' => 'Tio saja',
                'password' => bcrypt('1234'),
                'no_telp' => '08267719296',
                'section_id' => '36',
                'department_id' => '13',
                'division_id' => '1',
            ],
            [
                'npk' => '9927',
                'nama' => 'Subagio saja',
                'password' => bcrypt('1234'),
                'no_telp' => '08267719297',
                'section_id' => '36',
                'department_id' => '13',
                'division_id' => '1',
            ],
            [
                'npk' => '9928',
                'nama' => 'Samsul saja',
                'password' => bcrypt('1234'),
                'no_telp' => '08267719298',
                'section_id' => '36',
                'department_id' => '13',
                'division_id' => '1',
            ],

            // Admin
            [
                'npk' => 'adminPE',
                'nama' => 'admin plant engineering',
                'password' => bcrypt('1234'),
                'no_telp' => '08267719112',
                'section_id' => '36',
                'department_id' => '13',
                'division_id' => '1',
                'role_id' => '2',
            ],
            [
                'npk' => 'adminDigi',
                'nama' => 'admin Digitalisasi',
                'password' => bcrypt('1234'),
                'no_telp' => '08267719121',
                'section_id' => '35',
                'department_id' => '13',
                'division_id' => '1',
                'role_id' => '2',
            ],
            [
                'npk' => 'adminAuto',
                'nama' => 'admin Automasi',
                'password' => bcrypt('1234'),
                'no_telp' => '08267719211',
                'section_id' => '34',
                'department_id' => '13',
                'division_id' => '1',
                'role_id' => '2',
            ],

            // Section
            [
                'npk' => 'sectionPE',
                'nama' => 'section plant engineering',
                'password' => bcrypt('1234'),
                'no_telp' => '08267718112',
                'section_id' => '36',
                'department_id' => '13',
                'division_id' => '1',
                'role_id' => '3',
            ],
            [
                'npk' => 'sectionDigi',
                'nama' => 'admin Digitalisasi',
                'password' => bcrypt('1234'),
                'no_telp' => '08267718121',
                'section_id' => '35',
                'department_id' => '13',
                'division_id' => '1',
                'role_id' => '3',
            ],
            [
                'npk' => 'sectionAuto',
                'nama' => 'section Automasi',
                'password' => bcrypt('1234'),
                'no_telp' => '08267718211',
                'section_id' => '34',
                'department_id' => '13',
                'division_id' => '1',
                'role_id' => '3',
            ],

            // Karyawan Dept. Production
            [
                'npk' => '8920',
                'nama' => 'priam sitohang',
                'password' => bcrypt('1234'),
                'no_telp' => '08267788290',
                'section_id' => '30',
                'department_id' => '2',
                'division_id' => '1',
            ],
            [
                'npk' => '8921',
                'nama' => 'alex mansur',
                'password' => bcrypt('1234'),
                'no_telp' => '08267788291',
                'section_id' => '30',
                'department_id' => '2',
                'division_id' => '1',
            ],
            [
                'npk' => '8922',
                'nama' => 'jaka sitombul',
                'password' => bcrypt('1234'),
                'no_telp' => '08267788292',
                'section_id' => '30',
                'department_id' => '2',
                'division_id' => '1',
            ],
            [
                'npk' => '8923',
                'nama' => 'Dio amandio',
                'password' => bcrypt('1234'),
                'no_telp' => '08267788293',
                'section_id' => '30',
                'department_id' => '2',
                'division_id' => '1',
            ],
            [
                'npk' => '8924',
                'nama' => 'Rian Barbarian',
                'password' => bcrypt('1234'),
                'no_telp' => '08267788294',
                'section_id' => '29',
                'department_id' => '2',
                'division_id' => '1',
            ],
            [
                'npk' => '8925',
                'nama' => 'Sidiq waseso',
                'password' => bcrypt('1234'),
                'no_telp' => '08267788295',
                'section_id' => '29',
                'department_id' => '2',
                'division_id' => '1',
            ],
            [
                'npk' => '8926',
                'nama' => 'Doughlas muto',
                'password' => bcrypt('1234'),
                'no_telp' => '08267788296',
                'section_id' => '29',
                'department_id' => '2',
                'division_id' => '1',
            ],
            [
                'npk' => '8927',
                'nama' => 'moena sitaluang',
                'password' => bcrypt('1234'),
                'no_telp' => '08267788297',
                'section_id' => '29',
                'department_id' => '2',
                'division_id' => '1',
            ],
            [
                'npk' => '8928',
                'nama' => 'zeremia silalabung',
                'password' => bcrypt('1234'),
                'no_telp' => '08267788298',
                'section_id' => '29',
                'department_id' => '2',
                'division_id' => '1',
            ],
            //ACCOUNTING & TAX
            [
                'npk' => '0706',
                'nama' => 'Hendra Gunawan',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964278',
                'section_id' => '4', // ACCOUNTING & TAX
                'department_id' => '15', // FINANCE & ACCOUNTING
                'division_id' => '3', // ADMIN
                'role_id' => null, // Kosongkan role_id
            ],
            [
                'npk' => '1637',
                'nama' => 'Fatimah Nurrahmah',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964279',
                'section_id' => '4', // ACCOUNTING & TAX
                'department_id' => '15', // FINANCE & ACCOUNTING
                'division_id' => '3', // ADMIN
                'role_id' => null, // Kosongkan role_id
            ],
            //AUTOMATION & SYSTEM ANALYST
            [
                'npk' => '0124',
                'nama' => 'Yusuf',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964280',
                'section_id' => '13', // AUTOMATION & SYSTEM ANALYST
                'department_id' => '13', // TECHNICAL SUPPORT
                'division_id' => '1', // PLANT
                'role_id' => null, // Kosongkan role_id
            ],
            [
                'npk' => '0675',
                'nama' => 'Mohamad Faisal Alamsyah',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964281',
                'section_id' => '13', // AUTOMATION & SYSTEM ANALYST
                'department_id' => '13', // TECHNICAL SUPPORT
                'division_id' => '1', // PLANT
                'role_id' => null, // Kosongkan role_id
            ],
            [
                'npk' => '1007',
                'nama' => 'Rizal Teguh Rahayu',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964282',
                'section_id' => '13', // AUTOMATION & SYSTEM ANALYST
                'department_id' => '13', // TECHNICAL SUPPORT
                'division_id' => '1', // PLANT
                'role_id' => null, // Kosongkan role_id
            ],
            [
                'npk' => '1647',
                'nama' => 'Fatdli Bramastha',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964283',
                'section_id' => '13', // AUTOMATION & SYSTEM ANALYST
                'department_id' => '13', // TECHNICAL SUPPORT
                'division_id' => '1', // PLANT
                'role_id' => null, // Kosongkan role_id
            ],
            [
                'npk' => 'H527',
                'nama' => 'Andi Junianto',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964284',
                'section_id' => '13', // AUTOMATION & SYSTEM ANALYST
                'department_id' => '13', // TECHNICAL SUPPORT
                'division_id' => '1', // PLANT
                'role_id' => null, // Kosongkan role_id
            ],
            [
                'npk' => 'H683',
                'nama' => 'Haekan Firdaus Mujib Chandra',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964285',
                'section_id' => '13', // AUTOMATION & SYSTEM ANALYST
                'department_id' => '13', // TECHNICAL SUPPORT
                'division_id' => '1', // PLANT
                'role_id' => null, // Kosongkan role_id
            ],
            //CS
            [
                'npk' => 'H102',
                'nama' => 'Nursid Herdiansah',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964286',
                'section_id' => '7', // CS
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'H103',
                'nama' => 'Sahroni',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964287',
                'section_id' => '7', // CS
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'H105',
                'nama' => 'Sukarno',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964288',
                'section_id' => '7', // CS
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'H101',
                'nama' => 'Murjaya',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964289',
                'section_id' => '7', // CS
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'H100',
                'nama' => 'Hamdan Permana',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964290',
                'section_id' => '7', // CS
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'H64',
                'nama' => 'Erif Purnama',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964291',
                'section_id' => '7', // CS
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'H63',
                'nama' => 'Darman',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964292',
                'section_id' => '7', // CS
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'H185',
                'nama' => 'Hasannudin',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964293',
                'section_id' => '7', // CS
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'H187',
                'nama' => 'Hermanudin',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964294',
                'section_id' => '7', // CS
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'H186',
                'nama' => 'Yaman',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964295',
                'section_id' => '7', // CS
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'H474',
                'nama' => 'Ajat Supriatna',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964296',
                'section_id' => '7', // CS
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'H545',
                'nama' => 'Adwi Rasta Prasetya',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964297',
                'section_id' => '7', // CS
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'H606',
                'nama' => 'Asep Sunarya',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964298',
                'section_id' => '7', // CS
                'department_id' => '16', // HC & GA
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H642',
                'nama' => 'Maryana',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964299',
                'section_id' => '7', // CS
                'department_id' => '16', // HC & GA
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H755',
                'nama' => 'Diah Amalia',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964300',
                'section_id' => '7', // CS
                'department_id' => '16', // HC & GA
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H756',
                'nama' => 'Wisnu Andika',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964301',
                'section_id' => '7', // CS
                'department_id' => '16', // HC & GA
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            //DIGITALISASI
            [
                'npk' => '0011',
                'nama' => 'Agustinus Progo Sutrisno',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964302',
                'section_id' => '8', // DIGITALISASI
                'department_id' => '15', // TECHNICAL SUPPORT
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '1653',
                'nama' => 'Yoga Nugraha',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964303',
                'section_id' => '8', // DIGITALISASI
                'department_id' => '15', // TECHNICAL SUPPORT
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '1664',
                'nama' => 'Roni Paslan',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964304',
                'section_id' => '8', // DIGITALISASI
                'department_id' => '15', // TECHNICAL SUPPORT
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '1665',
                'nama' => 'Harby Anwardi',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964305',
                'section_id' => '8', // DIGITALISASI
                'department_id' => '15', // TECHNICAL SUPPORT
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H701',
                'nama' => 'Toharoh',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964306',
                'section_id' => '8', // DIGITALISASI
                'department_id' => '15', // TECHNICAL SUPPORT
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'P177',
                'nama' => 'Adrian Rafe Albar',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964307',
                'section_id' => '8', // DIGITALISASI
                'department_id' => '15', // TECHNICAL SUPPORT
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'P178',
                'nama' => 'Arip Dwi Sulistyo',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964308',
                'section_id' => '8', // DIGITALISASI
                'department_id' => '15', // TECHNICAL SUPPORT
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'P179',
                'nama' => 'Hafidh Muhammad Yusuf',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964309',
                'section_id' => '8', // DIGITALISASI
                'department_id' => '15', // TECHNICAL SUPPORT
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            //driver
            [
                'npk' => 'H691',
                'nama' => 'Teguh Pujiono',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964310',
                'section_id' => '8', // DRIVER
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'H306',
                'nama' => 'Nana Mulyana',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964311',
                'section_id' => '8', // DRIVER
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'H692',
                'nama' => 'Andre Antoni',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964312',
                'section_id' => '8', // DRIVER
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'H693',
                'nama' => 'Hery Suranto',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964313',
                'section_id' => '8', // DRIVER
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'H689',
                'nama' => 'Asep',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964314',
                'section_id' => '8', // DRIVER
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'H789',
                'nama' => 'Budiyanto',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964315',
                'section_id' => '8', // DRIVER
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],

            //ENVIRONTMENT, HEALTHY & SAFETY & ISO
            [
                'npk' => '1573',
                'nama' => 'Misbahul Anam',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964316',
                'section_id' => '9', // ENVIRONTMENT, HEALTHY & SAFETY & ISO
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => '1603',
                'nama' => 'Renti Iswarindra',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964317',
                'section_id' => '9', // ENVIRONTMENT, HEALTHY & SAFETY & ISO
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'P136',
                'nama' => 'Suci Rahmatulah',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964318',
                'section_id' => '9', // ENVIRONTMENT, HEALTHY & SAFETY & ISO
                'department_id' => '16', // HC & GA
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            //FINANCE & ACCOUNTING	FINANCE & ACCOUNTING
            [
                'npk' => '1059',
                'nama' => 'Afdian Eka Prasetya',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964319',
                'section_id' => '5', // FINANCE & ACCOUNTING
                'department_id' => '15', // FINANCE & ACCOUNTING
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => '1671',
                'nama' => 'Wong Andrean Wijaya',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964320',
                'section_id' => '5', // FINANCE & ACCOUNTING
                'department_id' => '15', // FINANCE & ACCOUNTING
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],

            //FINANCIAL PLANNING & ANALYSIS
            [
                'npk' => '0521',
                'nama' => 'Fikri Utomo',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964321',
                'section_id' => '3', // FINANCIAL PLANNING & ANALYSIS
                'department_id' => '15', // FINANCE & ACCOUNTING
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => '1013',
                'nama' => 'Alami Daranita',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964322',
                'section_id' => '3', // FINANCIAL PLANNING & ANALYSIS
                'department_id' => '15', // FINANCE & ACCOUNTING
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'H448',
                'nama' => 'Anisa',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964323',
                'section_id' => '3', // FINANCIAL PLANNING & ANALYSIS
                'department_id' => '15', // FINANCE & ACCOUNTING
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'P167',
                'nama' => 'Siti Rohmah',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964324',
                'section_id' => '3', // FINANCIAL PLANNING & ANALYSIS
                'department_id' => '15', // FINANCE & ACCOUNTING
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'P169',
                'nama' => 'Ferlianty Nurulita',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964325',
                'section_id' => '3', // FINANCIAL PLANNING & ANALYSIS
                'department_id' => '15', // FINANCE & ACCOUNTING
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'P170',
                'nama' => 'Maryanti',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964326',
                'section_id' => '3', // FINANCIAL PLANNING & ANALYSIS
                'department_id' => '15', // FINANCE & ACCOUNTING
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],

            //HC & GA
            [
                'npk' => '1659',
                'nama' => 'Antonius Hasibuan',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964327',
                'section_id' => '10', // HC & GA
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => '1667',
                'nama' => 'Rian Effendi',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964328',
                'section_id' => '10', // HC & GA
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            //HR & COMP. BENEFITS
            [
                'npk' => '0125',
                'nama' => 'Tribuana Tunggal Dhewi',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964329',
                'section_id' => '11', // HR & COMP. BENEFITS
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => '1591',
                'nama' => 'Ridwan Pamungkas',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964330',
                'section_id' => '11', // HR & COMP. BENEFITS
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'P166',
                'nama' => 'Shelvy Berliani',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964331',
                'section_id' => '11', // HR & COMP. BENEFITS
                'department_id' => '16', // HC & GA
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            //INVENTORY & COST CONTROL
            [
                'npk' => '1571',
                'nama' => 'Syifa Faujiah',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964332',
                'section_id' => '6', // INVENTORY & COST CONTROL
                'department_id' => '15', // FINANCE & ACCOUNTING
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => '1658',
                'nama' => 'Diyo Sabdo Sukmono',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964333',
                'section_id' => '6', // INVENTORY & COST CONTROL
                'department_id' => '15', // FINANCE & ACCOUNTING
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            //IT
            [
                'npk' => 'IT',
                'nama' => 'Prayogo Hadi Satrio',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964337',
                'section_id' => '12', // IT
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            //KLINIK
            [
                'npk' => 'H564',
                'nama' => 'Irfan Maulana',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964334',
                'section_id' => '13', // KLINIK
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'H521',
                'nama' => 'Deka Kurniawan Hendrayanto',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964335',
                'section_id' => '13', // KLINIK
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'H566',
                'nama' => 'Singgih Syahrial.dr',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964336',
                'section_id' => '13', // KLINIK
                'department_id' => '16', // HC & GA
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            //MAINTENANCE & UTILITY
            [
                'npk' => 'H541',
                'nama' => 'Zafanya Fernando S.',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964338',
                'section_id' => '9', // MAINTENANCE & UTILITY
                'department_id' => '2', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '1662',
                'nama' => 'Mohammad Faisal',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964339',
                'section_id' => '9', // MAINTENANCE & UTILITY
                'department_id' => '2', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H577',
                'nama' => 'Irwanudin',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964340',
                'section_id' => '9', // MAINTENANCE & UTILITY
                'department_id' => '2', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H608',
                'nama' => 'Angga Prasetya',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964341',
                'section_id' => '9', // MAINTENANCE & UTILITY
                'department_id' => '2', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H637',
                'nama' => 'Natan Galih Wicaksono',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964342',
                'section_id' => '9', // MAINTENANCE & UTILITY
                'department_id' => '2', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H643',
                'nama' => 'Madhasan',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964343',
                'section_id' => '9', // MAINTENANCE & UTILITY
                'department_id' => '2', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H646',
                'nama' => 'Taufik Sukron',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964344',
                'section_id' => '9', // MAINTENANCE & UTILITY
                'department_id' => '2', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H686',
                'nama' => 'Toni Gunawan',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964345',
                'section_id' => '9', // MAINTENANCE & UTILITY
                'department_id' => '2', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'M969',
                'nama' => 'Agus Miftah',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964346',
                'section_id' => '9', // MAINTENANCE & UTILITY
                'department_id' => '2', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'M970',
                'nama' => 'Refi Ardian',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964347',
                'section_id' => '9', // MAINTENANCE & UTILITY
                'department_id' => '2', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H772',
                'nama' => 'Dinda Gibran Nababan',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964348',
                'section_id' => '9', // MAINTENANCE & UTILITY
                'department_id' => '2', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            //MARKETING & SALES
            [
                'npk' => '0014',
                'nama' => 'Isthof Fathony',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964349',
                'section_id' => '10', // MARKETING & SALES
                'department_id' => '3', // MARKETING
                'division_id' => '2', // BUSINESS DEV. & ENG
                'role_id' => null,
            ],
            [
                'npk' => '1660',
                'nama' => 'Sri Sulistiawati Kusuma',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964350',
                'section_id' => '10', // MARKETING & SALES
                'department_id' => '3', // MARKETING
                'division_id' => '2', // BUSINESS DEV. & ENG
                'role_id' => null,
            ],
            [
                'npk' => '0024',
                'nama' => 'Sri Wulandari',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964351',
                'section_id' => '10', // MARKETING & SALES
                'department_id' => '3', // MARKETING
                'division_id' => '2', // BUSINESS DEV. & ENG
                'role_id' => null,
            ],
            [
                'npk' => '0957',
                'nama' => 'Karina Permatasari',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964352',
                'section_id' => '10', // MARKETING & SALES
                'department_id' => '3', // MARKETING
                'division_id' => '2', // BUSINESS DEV. & ENG
                'role_id' => null,
            ],
            //MARKETING, ENGINEERING, MOLD & TOOLING
            [
                'npk' => '0489',
                'nama' => 'Ardiansyah',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964353',
                'section_id' => '11', // MOLD & TOOLING
                'department_id' => '3', // MARKETING, ENGINEERING, MOLD & TOOLING
                'division_id' => '2', // BUSINESS DEV. & ENG
                'role_id' => null,
            ],
            //MATCOMP & SUBCONT CONTROL
            [
                'npk' => 'M924',
                'nama' => 'Muhamad Aril',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964367',
                'section_id' => '22', // MATCOMP & SUBCONT CONTROL
                'department_id' => '1', // PPIC
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'P163',
                'nama' => 'Yasha Nurfauziah Azzahra',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964368',
                'section_id' => '22', // MATCOMP & SUBCONT CONTROL
                'department_id' => '1', // PPIC
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            //MOLD & TOOLING DESIGN
            [
                'npk' => '0568',
                'nama' => 'Muh. Fahmi Asagaf',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964369',
                'section_id' => '23', // MOLD & TOOLING DESIGN
                'department_id' => '2', // MOLD & TOOLING
                'division_id' => '2', // BUSSINES DEV. & ENG
                'role_id' => null,
            ],
            [
                'npk' => '0216',
                'nama' => 'Aldy Galvani',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964370',
                'section_id' => '23', // MOLD & TOOLING DESIGN
                'department_id' => '2', // MOLD & TOOLING
                'division_id' => '2', // BUSSINES DEV. & ENG
                'role_id' => null,
            ],
            [
                'npk' => '0883',
                'nama' => 'Budi Wijayanto',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964371',
                'section_id' => '23', // MOLD & TOOLING DESIGN
                'department_id' => '2', // MOLD & TOOLING
                'division_id' => '2', // BUSSINES DEV. & ENG
                'role_id' => null,
            ],
            [
                'npk' => '1000',
                'nama' => 'Muhammad Arifudin',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964372',
                'section_id' => '23', // MOLD & TOOLING DESIGN
                'department_id' => '2', // MOLD & TOOLING
                'division_id' => '2', // BUSSINES DEV. & ENG
                'role_id' => null,
            ],
            [
                'npk' => '1543',
                'nama' => 'Muhammad Muttaqin',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964373',
                'section_id' => '23', // MOLD & TOOLING DESIGN
                'department_id' => '2', // MOLD & TOOLING
                'division_id' => '2', // BUSSINES DEV. & ENG
                'role_id' => null,
            ],
            //PARAMEDIS
            [
                'npk' => 'H640',
                'nama' => 'Apip Hapinurdin',
                'password' => Hash::make('1234'),
                'no_telp' => '081329964374',
                'section_id' => '24', // PARAMEDIS
                'department_id' => '3', // HC & GA
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            //PLANT ENGINEERING
            [
                'npk' => '1613',
                'nama' => 'Zaid Ilham Amrulah',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567890',
                'section_id' => '25', // PLANT ENGINEERING
                'department_id' => '4', // TECHNICAL SUPPORT
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '0591',
                'nama' => 'Ruslan Ependi',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567891',
                'section_id' => '25',
                'department_id' => '4',
                'division_id' => '1',
                'role_id' => null,
            ],
            [
                'npk' => '0018',
                'nama' => 'Agus Nggermadi',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567892',
                'section_id' => '25',
                'department_id' => '4',
                'division_id' => '1',
                'role_id' => null,
            ],
            [
                'npk' => 'H214',
                'nama' => 'Ryan Sopyan',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567893',
                'section_id' => '25',
                'department_id' => '4',
                'division_id' => '1',
                'role_id' => null,
            ],
            [
                'npk' => 'H484',
                'nama' => 'Bayu Permana',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567894',
                'section_id' => '25',
                'department_id' => '4',
                'division_id' => '1',
                'role_id' => null,
            ],
            //PROCESS ENGINEERING
            [
                'npk' => '0115',
                'nama' => 'Wahyudi Komala',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567895',
                'section_id' => '26', // PROCESS ENGINEERING
                'department_id' => '4', // TECHNICAL SUPPORT
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'P175',
                'nama' => 'Fallah Yunansyah',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567896',
                'section_id' => '26',
                'department_id' => '4',
                'division_id' => '1',
                'role_id' => null,
            ],
            [
                'npk' => 'P174',
                'nama' => 'Elang Saputra',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567897',
                'section_id' => '26',
                'department_id' => '4',
                'division_id' => '1',
                'role_id' => null,
            ],
            //PROCESS ENGINEERING PA
            [
                'npk' => '0068',
                'nama' => 'Fredi Pranata',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567898',
                'section_id' => '27', // PROCESS ENGINEERING PA
                'department_id' => '4', // TECHNICAL SUPPORT
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '0213',
                'nama' => 'Febri Mega Setiawan',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567899',
                'section_id' => '27',
                'department_id' => '4',
                'division_id' => '1',
                'role_id' => null,
            ],
            //PROCESS ENGINEERING PI
            [
                'npk' => '0046',
                'nama' => 'Ahmad Daerobi',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '28', // PROCESS ENGINEERING PI
                'department_id' => '4', // TECHNICAL SUPPORT
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '0057',
                'nama' => 'Vebby Januar',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567801',
                'section_id' => '28',
                'department_id' => '4',
                'division_id' => '1',
                'role_id' => null,
            ],
            [
                'npk' => '0066',
                'nama' => 'Eko Aji Nugroho',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567802',
                'section_id' => '28',
                'department_id' => '4',
                'division_id' => '1',
                'role_id' => null,
            ],
            [
                'npk' => '0121',
                'nama' => 'Susilo Nugroho',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567803',
                'section_id' => '28',
                'department_id' => '4',
                'division_id' => '1',
                'role_id' => null,
            ],
            [
                'npk' => '0375',
                'nama' => 'Mukti Hari',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567804',
                'section_id' => '28',
                'department_id' => '4',
                'division_id' => '1',
                'role_id' => null,
            ],
            [
                'npk' => '0575',
                'nama' => 'Muhamad Yusuf Anshori',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567805',
                'section_id' => '28',
                'department_id' => '4',
                'division_id' => '1',
                'role_id' => null,
            ],
            [
                'npk' => 'H441',
                'nama' => 'Dian Sugiana',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567806',
                'section_id' => '28',
                'department_id' => '4',
                'division_id' => '1',
                'role_id' => null,
            ],
            [
                'npk' => 'H442',
                'nama' => 'Tumingan',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567807',
                'section_id' => '28',
                'department_id' => '4',
                'division_id' => '1',
                'role_id' => null,
            ],
            [
                'npk' => 'H787',
                'nama' => 'Wisnu Rizkyansyah',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567808',
                'section_id' => '28',
                'department_id' => '4',
                'division_id' => '1',
                'role_id' => null,
            ],
            [
                'npk' => 'H788',
                'nama' => 'Muhammad Raka Ridwansyah Sulaeman',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567809',
                'section_id' => '28',
                'department_id' => '4',
                'division_id' => '1',
                'role_id' => null,
            ],
            [
                'npk' => 'H790',
                'nama' => 'Sigit Ferdian',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567810',
                'section_id' => '28',
                'department_id' => '4',
                'division_id' => '1',
                'role_id' => null,
            ],
            //PROCUREMENT
            [
                'npk' => '0522',
                'nama' => 'Andreas Ari Susilo',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567801',
                'section_id' => '28', // PROCUREMENT
                'department_id' => '19', // PROCUREMENT
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => '1560',
                'nama' => 'Lilik Satria Wibawa',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567802',
                'section_id' => '28', // PROCUREMENT
                'department_id' => '19', // PROCUREMENT
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => '1499',
                'nama' => 'Handi Bowo',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567803',
                'section_id' => '28', // PROCUREMENT
                'department_id' => '19', // PROCUREMENT
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => 'H463',
                'nama' => 'Meliy Yana Safitri',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567804',
                'section_id' => '28', // PROCUREMENT
                'department_id' => '19', // PROCUREMENT
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => '1638',
                'nama' => 'Abdurrohim',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567805',
                'section_id' => '28', // PROCUREMENT
                'department_id' => '19', // PROCUREMENT
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            [
                'npk' => '1640',
                'nama' => 'Topan Adi Kurnia',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567806',
                'section_id' => '28', // PROCUREMENT
                'department_id' => '19', // PROCUREMENT
                'division_id' => '3', // ADMIN
                'role_id' => null,
            ],
            //PRODUCTION
            [
                'npk' => '0156',
                'nama' => 'Dedi Juansah',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '28', // PRODUCTION
                'department_id' => '4',  // PRODUCTION
                'division_id' => '1',  // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '0028',
                'nama' => 'Recky Resza',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567801',
                'section_id' => '28', // PRODUCTION
                'department_id' => '4',  // PRODUCTION
                'division_id' => '1',  // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '0030',
                'nama' => 'Ahmad Juhaeni',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567802',
                'section_id' => '28', // PRODUCTION
                'department_id' => '4',  // PRODUCTION
                'division_id' => '1',  // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H531',
                'nama' => 'Wempi Ravi Audric',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567899',
                'section_id' => '28', // PRODUCTION
                'department_id' => '4',  // PRODUCTION
                'division_id' => '1',  // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H384',
                'nama' => 'Herdy Dzulhiawan',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '27', // PRODUCTION
                'department_id' => '3', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H385',
                'nama' => 'Aris Rinaldi',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '27', // PRODUCTION
                'department_id' => '3', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H388',
                'nama' => 'Umi Azizah',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '27', // PRODUCTION
                'department_id' => '3', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H70',
                'nama' => 'Galih Haji Saputra',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '27', // PRODUCTION
                'department_id' => '3', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H47',
                'nama' => 'Wanto Hermawan',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '27', // PRODUCTION
                'department_id' => '3', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H42',
                'nama' => 'Heri Eprianto',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '27', // PRODUCTION
                'department_id' => '3', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H44',
                'nama' => 'Muhammad Ficky Mulyadi',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '27', // PRODUCTION
                'department_id' => '3', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H83',
                'nama' => 'Syaeful Imam',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '27', // PRODUCTION
                'department_id' => '3', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H493',
                'nama' => 'Yayan Sunandi',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '27', // PRODUCTION
                'department_id' => '3', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H504',
                'nama' => 'Egi Sugiana',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '27', // PRODUCTION
                'department_id' => '3', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H506',
                'nama' => 'Eka Purnama Rintia Sari',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '27', // PRODUCTION
                'department_id' => '3', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H508',
                'nama' => 'Riska Rosdiyani',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '27', // PRODUCTION
                'department_id' => '3', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H509',
                'nama' => 'Ricky Bayu Saputra',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '27', // PRODUCTION
                'department_id' => '3', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H16',
                'nama' => 'Fingki Ajisopyan',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '27', // PRODUCTION
                'department_id' => '3', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H07',
                'nama' => 'Andi Trimoyo',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '27', // PRODUCTION
                'department_id' => '3', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H30',
                'nama' => 'Raska',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '27', // PRODUCTION
                'department_id' => '3', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H121',
                'nama' => 'Rian Bahtiar',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '27', // PRODUCTION
                'department_id' => '3', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H123',
                'nama' => 'Agung Kurnia',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '27', // PRODUCTION
                'department_id' => '3', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H174',
                'nama' => 'Reyhan Mukti Nugroho',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '27', // PRODUCTION
                'department_id' => '3', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H177',
                'nama' => 'Andre Luki Pratama',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567800',
                'section_id' => '27', // PRODUCTION
                'department_id' => '3', // PRODUCTION
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            //PRODUCTION PLANNING & MRP
            [
                'npk' => '0164',
                'nama' => 'Mohamad Fauzi',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567890',
                'section_id' => '28', // PRODUCTION PLANNING & MRP
                'department_id' => '4', // PPIC
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '0268',
                'nama' => 'Ropik Nur Faizal',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567891',
                'section_id' => '28', // PRODUCTION PLANNING & MRP
                'department_id' => '4', // PPIC
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '0448',
                'nama' => 'Syarif Hidayat',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567892',
                'section_id' => '28', // PRODUCTION PLANNING & MRP
                'department_id' => '4', // PPIC
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '1553',
                'nama' => 'Janu Rohmani',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567893',
                'section_id' => '28', // PRODUCTION PLANNING & MRP
                'department_id' => '4', // PPIC
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '0051',
                'nama' => 'Waldi Firdaus',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567894',
                'section_id' => '28', // PRODUCTION PLANNING & MRP
                'department_id' => '4', // PPIC
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'M917',
                'nama' => 'Ashari Azis',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567895',
                'section_id' => '28', // PRODUCTION PLANNING & MRP
                'department_id' => '4', // PPIC
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            //PROJECT ENGINEERING
            [
                'npk' => '0029',
                'nama' => 'Roto Abdullah',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567900',
                'section_id' => '29', // PROJECT ENGINEERING
                'department_id' => '5', // ENGINEERING
                'division_id' => '2', // BUSSINES DEV. & ENG
                'role_id' => null,
            ],
            [
                'npk' => '0058',
                'nama' => 'Aldi Rian Ripai',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567901',
                'section_id' => '29', // PROJECT ENGINEERING
                'department_id' => '5', // ENGINEERING
                'division_id' => '2', // BUSSINES DEV. & ENG
                'role_id' => null,
            ],
            [
                'npk' => '0117',
                'nama' => 'Christina Suryani',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567902',
                'section_id' => '29', // PROJECT ENGINEERING
                'department_id' => '5', // ENGINEERING
                'division_id' => '2', // BUSSINES DEV. & ENG
                'role_id' => null,
            ],
            [
                'npk' => '0647',
                'nama' => 'Odhi Apriyan',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567903',
                'section_id' => '29', // PROJECT ENGINEERING
                'department_id' => '5', // ENGINEERING
                'division_id' => '2', // BUSSINES DEV. & ENG
                'role_id' => null,
            ],
            [
                'npk' => '1497',
                'nama' => 'Rizqy Faizal Muttaqin',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567904',
                'section_id' => '29', // PROJECT ENGINEERING
                'department_id' => '5', // ENGINEERING
                'division_id' => '2', // BUSSINES DEV. & ENG
                'role_id' => null,
            ],
            [
                'npk' => '1611',
                'nama' => 'Muhamad Gojali Rahman',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567905',
                'section_id' => '29', // PROJECT ENGINEERING
                'department_id' => '5', // ENGINEERING
                'division_id' => '2', // BUSSINES DEV. & ENG
                'role_id' => null,
            ],
            [
                'npk' => '1617',
                'nama' => 'Suryono',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567906',
                'section_id' => '29', // PROJECT ENGINEERING
                'department_id' => '5', // ENGINEERING
                'division_id' => '2', // BUSSINES DEV. & ENG
                'role_id' => null,
            ],
            [
                'npk' => '1513',
                'nama' => 'Wahyu Satriyo Ramadhany',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567907',
                'section_id' => '29', // PROJECT ENGINEERING
                'department_id' => '5', // ENGINEERING
                'division_id' => '2', // BUSSINES DEV. & ENG
                'role_id' => null,
            ],
            [
                'npk' => 'P171',
                'nama' => 'Hafizh Musthafa',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567908',
                'section_id' => '29', // PROJECT ENGINEERING
                'department_id' => '5', // ENGINEERING
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '1673',
                'nama' => 'Rizal Pahlevi',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567909',
                'section_id' => '29', // PROJECT ENGINEERING
                'department_id' => '5', // ENGINEERING
                'division_id' => '2', // BUSSINES DEV. & ENG
                'role_id' => null,
            ],
            //QUALITY ASSURANCE
            [
                'npk' => '0105',
                'nama' => 'Wendra Ari Wibowo',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567900',
                'section_id' => '30', // QUALITY ASSURANCE
                'department_id' => '4', // QUALITY
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '0112',
                'nama' => 'Ahmad Kumaedi',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567901',
                'section_id' => '30', // QUALITY ASSURANCE
                'department_id' => '4', // QUALITY
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '0119',
                'nama' => 'Ray Vhalent',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567902',
                'section_id' => '30', // QUALITY ASSURANCE
                'department_id' => '4', // QUALITY
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '0171',
                'nama' => 'Ahmad Tahroji',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567903',
                'section_id' => '30', // QUALITY ASSURANCE
                'department_id' => '4', // QUALITY
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '0273',
                'nama' => 'Rudi Hardiansyah',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567904',
                'section_id' => '30', // QUALITY ASSURANCE
                'department_id' => '4', // QUALITY
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '0472',
                'nama' => 'Andri Winarso',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567905',
                'section_id' => '30', // QUALITY ASSURANCE
                'department_id' => '4', // QUALITY
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '0546',
                'nama' => 'Wandi Mustofah',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567906',
                'section_id' => '30', // QUALITY ASSURANCE
                'department_id' => '4', // QUALITY
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '1555',
                'nama' => 'Rudiansyah',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567907',
                'section_id' => '30', // QUALITY ASSURANCE
                'department_id' => '4', // QUALITY
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => '1580',
                'nama' => 'Irfan Kristanto',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567908',
                'section_id' => '30', // QUALITY ASSURANCE
                'department_id' => '4', // QUALITY
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H251',
                'nama' => 'Teguh Iman Septiaji',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567909',
                'section_id' => '30', // QUALITY ASSURANCE
                'department_id' => '4', // QUALITY
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H159',
                'nama' => 'Iqbal Hidayat',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567910',
                'section_id' => '30', // QUALITY ASSURANCE
                'department_id' => '4', // QUALITY
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H169',
                'nama' => 'Luthfianto',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567911',
                'section_id' => '30', // QUALITY ASSURANCE
                'department_id' => '4', // QUALITY
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H171',
                'nama' => 'Erwan Fahmi',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567912',
                'section_id' => '30', // QUALITY ASSURANCE
                'department_id' => '4', // QUALITY
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H464',
                'nama' => 'Abdul Basit Romadhoni',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567913',
                'section_id' => '30', // QUALITY ASSURANCE
                'department_id' => '4', // QUALITY
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H116',
                'nama' => 'Ahmad Nurkhalim',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567914',
                'section_id' => '30', // QUALITY ASSURANCE
                'department_id' => '4', // QUALITY
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H86',
                'nama' => 'Rifan Sah',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567915',
                'section_id' => '30', // QUALITY ASSURANCE
                'department_id' => '4', // QUALITY
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H517',
                'nama' => 'Dandi Ardiansyah',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567916',
                'section_id' => '30', // QUALITY ASSURANCE
                'department_id' => '4', // QUALITY
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H766',
                'nama' => 'M HARIZ PRATAMA',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567917',
                'section_id' => '30', // QUALITY ASSURANCE
                'department_id' => '4', // QUALITY
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H767',
                'nama' => 'AKHADUL MUSTOFA',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567918',
                'section_id' => '30', // QUALITY ASSURANCE
                'department_id' => '4', // QUALITY
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H768',
                'nama' => 'EGY SURYANA',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567919',
                'section_id' => '30', // QUALITY ASSURANCE
                'department_id' => '4', // QUALITY
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
            [
                'npk' => 'H793',
                'nama' => 'Ahmad Zaneal Zayadi',
                'password' => Hash::make('1234'),
                'no_telp' => '081234567920',
                'section_id' => '30', // QUALITY ASSURANCE
                'department_id' => '4', // QUALITY
                'division_id' => '1', // PLANT
                'role_id' => null,
            ],
        ];
        foreach ($usersData as $key => $val) {
            User::create($val);
        };
    }
}
