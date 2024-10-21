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
                'npk' => 'SUPERADMIN',
                'nama' => 'Arif superadmin',
                'password' => bcrypt('1234'),
                'no_telp' => '081329964278',
                'section_id' => '1',
                'department_id' => '1',
                'division_id' => '1',
                'role_id' => '1',
            ],
            [
                'npk' => 'ADMIN',
                'nama' => 'Arif admin',
                'password' => bcrypt('1234'),
                'no_telp' => '081329964278',
                'section_id' => '1',
                'department_id' => '1',
                'division_id' => '1',
                'role_id' => '2',
            ],
            [
                'npk' => 'SECTION',
                'nama' => 'Arif section',
                'password' => bcrypt('1234'),
                'no_telp' => '081329964278',
                'section_id' => '1',
                'department_id' => '1',
                'division_id' => '1',
                'role_id' => '3',
            ],
            [
                'npk' => 'DEPARTMENT',
                'nama' => 'Arif department',
                'password' => bcrypt('1234'),
                'no_telp' => '081329964278',
                'section_id' => '1',
                'department_id' => '1',
                'division_id' => '1',
                'role_id' => '4',
            ],
            [
                'npk' => 'DIVISION',
                'nama' => 'Arif division',
                'password' => bcrypt('1234'),
                'no_telp' => '081329964278',
                'section_id' => '1',
                'department_id' => '1',
                'division_id' => '1',
                'role_id' => '5',
            ],
            [
                'npk' => 'HRD',
                'nama' => 'Arif hrd',
                'password' => bcrypt('1234'),
                'no_telp' => '081329964278',
                'section_id' => '1',
                'department_id' => '1',
                'division_id' => '1',
                'role_id' => '6',
            ],
            // ACCOUNTING & TAX
            [
                'npk' => '0706',
                'nama' => 'Hendra Gunawan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 1, // ACCOUNTING & TAX
                'department_id' => 15, // FINANCE & ACCOUNTING
                'division_id' => 3, // Sesuaikan jika ada divisi
                'role_id' => 7, // Role ID sesuai dengan kebutuhan
            ],
            [
                'npk' => '1637',
                'nama' => 'Fatimah Nurrahmah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 1, // ACCOUNTING & TAX
                'department_id' => 15, // FINANCE & ACCOUNTING
                'division_id' => 3, // Sesuaikan jika ada divisi
                'role_id' => 7, // Role ID sesuai dengan kebutuhan
            ],
            // AUTOMATION & SYSTEM ANALYST
                        [
                'npk' => '0124',
                'nama' => 'Yusuf',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 2, // AUTOMATION & SYSTEM ANALYST
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0675',
                'nama' => 'Mohamad Faisal Alamsyah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 2, // AUTOMATION & SYSTEM ANALYST
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '1007',
                'nama' => 'Rizal Teguh Rahayu',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 2, // AUTOMATION & SYSTEM ANALYST
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '1647',
                'nama' => 'Fatdli Bramastha',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 2, // AUTOMATION & SYSTEM ANALYST
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H527',
                'nama' => 'Andi Junianto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 2, // AUTOMATION & SYSTEM ANALYST
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H683',
                'nama' => 'Haekan Firdaus Mujib Chandra',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 2, // AUTOMATION & SYSTEM ANALYST
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // CS
                        [
                'npk' => 'H102',
                'nama' => 'Nursid Herdiansah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 3, // CS
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H103',
                'nama' => 'Sahroni',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 3, // CS
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H105',
                'nama' => 'Sukarno',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 3, // CS
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H101',
                'nama' => 'Murjaya',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 3, // CS
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H100',
                'nama' => 'Hamdan Permana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 3, // CS
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H64',
                'nama' => 'Erif Purnama',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 3, // CS
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H63',
                'nama' => 'Darman',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 3, // CS
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H185',
                'nama' => 'Hasannudin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 3, // CS
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H187',
                'nama' => 'Hermanudin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 3, // CS
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H186',
                'nama' => 'Yaman',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 3, // CS
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H474',
                'nama' => 'Ajat Supriatna',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 3, // CS
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H545',
                'nama' => 'Adwi Rasta Prasetya',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 3, // CS
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H606',
                'nama' => 'Asep Sunarya',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 3, // CS
                'department_id' => 16, // HC & GA
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H642',
                'nama' => 'Maryana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 3, // CS
                'department_id' => 16, // HC & GA
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H755',
                'nama' => 'Diah Amalia',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 3, // CS
                'department_id' => 16, // HC & GA
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // DIGITALISASI
            [
                'npk' => '0011',
                'nama' => 'Agustinus Progo Sutrisno',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 4, // DIGITALISASI
                'department_id' => 15, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '1653',
                'nama' => 'Yoga Nugraha',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 4, // DIGITALISASI
                'department_id' => 15, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '1664',
                'nama' => 'Roni Paslan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 4, // DIGITALISASI
                'department_id' => 15, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '1665',
                'nama' => 'Harby Anwardi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 4, // DIGITALISASI
                'department_id' => 15, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H701',
                'nama' => 'Toharoh',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 4, // DIGITALISASI
                'department_id' => 15, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'P177',
                'nama' => 'Adrian Rafe Albar',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 4, // DIGITALISASI
                'department_id' => 15, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'P178',
                'nama' => 'Arip Dwi Sulistyo',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 4, // DIGITALISASI
                'department_id' => 15, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'P179',
                'nama' => 'Hafidh Muhammad Yusuf',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 4, // DIGITALISASI
                'department_id' => 15, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // DRIVER
                        [
                'npk' => 'H691',
                'nama' => 'Teguh Pujiono',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 5, // DRIVER
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H306',
                'nama' => 'Nana Mulyana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 5, // DRIVER
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H692',
                'nama' => 'Andre Antoni',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 5, // DRIVER
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H693',
                'nama' => 'Hery Suranto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 5, // DRIVER
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H689',
                'nama' => 'Asep',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 5, // DRIVER
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H789',
                'nama' => 'Budiyanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 5, // DRIVER
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // ENVIRONMENT, HEALTHY & SAFETY & ISO
                        [
                'npk' => '1573',
                'nama' => 'Misbahul Anam',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 6, // ENVIRONMENT, HEALTHY & SAFETY & ISO
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '1603',
                'nama' => 'Renti Iswarindra',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 6, // ENVIRONMENT, HEALTHY & SAFETY & ISO
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'P136',
                'nama' => 'Suci Rahmatulah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 6, // ENVIRONMENT, HEALTHY & SAFETY & ISO
                'department_id' => 16, // HC & GA
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // FINANCE & ACCOUNTING
                        [
                'npk' => '1059',
                'nama' => 'Afdian Eka Prasetya',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 7, // FINANCE & ACCOUNTING
                'department_id' => 15, // FINANCE & ACCOUNTING
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '1671',
                'nama' => 'Wong Andrean Wijaya',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 7, // FINANCE & ACCOUNTING
                'department_id' => 15, // FINANCE & ACCOUNTING
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // FINANCIAL PLANNING & ANALYSIS
                        [
                'npk' => '0521',
                'nama' => 'Fikri Utomo',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 8, // FINANCIAL PLANNING & ANALYSIS
                'department_id' => 15, // FINANCE & ACCOUNTING
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '1013',
                'nama' => 'Alami Daranita',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 8, // FINANCIAL PLANNING & ANALYSIS
                'department_id' => 15, // FINANCE & ACCOUNTING
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H448',
                'nama' => 'Anisa',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 8, // FINANCIAL PLANNING & ANALYSIS
                'department_id' => 15, // FINANCE & ACCOUNTING
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'P167',
                'nama' => 'Siti Rohmah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 8, // FINANCIAL PLANNING & ANALYSIS
                'department_id' => 15, // FINANCE & ACCOUNTING
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'P169',
                'nama' => 'Ferlianty Nurulita',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 8, // FINANCIAL PLANNING & ANALYSIS
                'department_id' => 15, // FINANCE & ACCOUNTING
                'division_id' => 3, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'P170',
                'nama' => 'Maryanti',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 8, // FINANCIAL PLANNING & ANALYSIS
                'department_id' => 15, // FINANCE & ACCOUNTING
                'division_id' => 3, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // HC & GA
                        [
                'npk' => '1659',
                'nama' => 'Antonius Hasibuan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 9, // HC & GA
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '1667',
                'nama' => 'Rian Effendi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 9, // HC & GA
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // HR & COMP. BENEFITS
                        [
                'npk' => '0125',
                'nama' => 'Tribuana Tunggal Dhewi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 10, // HR & COMP. BENEFITS
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '1591',
                'nama' => 'Ridwan Pamungkas',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 10, // HR & COMP. BENEFITS
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'P166',
                'nama' => 'Shelvy Berliani',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 10, // HR & COMP. BENEFITS
                'department_id' => 16, // HC & GA
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // INVENTORY & COST CONTROL
                        [
                'npk' => '1571',
                'nama' => 'Syifa Faujiah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 11, // INVENTORY & COST CONTROL
                'department_id' => 15, // FINANCE & ACCOUNTING
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '1658',
                'nama' => 'Diyo Sabdo Sukmono',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 11, // INVENTORY & COST CONTROL
                'department_id' => 15, // FINANCE & ACCOUNTING
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // IT
            [
                'npk' => 'IT',
                'nama' => 'Prayogo Hadi Satrio',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 12, // IT
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // KLINIK
                        [
                'npk' => 'H564',
                'nama' => 'Irfan Maulana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 13, // KLINIK
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H521',
                'nama' => 'Deka Kurniawan Hendrayanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 13, // KLINIK
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H566',
                'nama' => 'Singgih Syahrial.dr',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 13, // KLINIK
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // MAINTENANCE & UTILITY
            [
                'npk' => 'H541',
                'nama' => 'Zafanya Fernando S.',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 14, // MAINTENANCE & UTILITY
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '1662',
                'nama' => 'Mohammad Faisal',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 14, // MAINTENANCE & UTILITY
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H577',
                'nama' => 'Irwanudin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 14, // MAINTENANCE & UTILITY
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H608',
                'nama' => 'Angga Prasetya',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 14, // MAINTENANCE & UTILITY
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H637',
                'nama' => 'Natan Galih Wicaksono',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 14, // MAINTENANCE & UTILITY
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H643',
                'nama' => 'Madhasan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 14, // MAINTENANCE & UTILITY
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H646',
                'nama' => 'Taufik Sukron',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 14, // MAINTENANCE & UTILITY
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H686',
                'nama' => 'Toni Gunawan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 14, // MAINTENANCE & UTILITY
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'M969',
                'nama' => 'Agus Miftah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 14, // MAINTENANCE & UTILITY
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'M970',
                'nama' => 'Refi Ardian',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 14, // MAINTENANCE & UTILITY
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H772',
                'nama' => 'Dinda Gibran Nababan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 14, // MAINTENANCE & UTILITY
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // MARKETING & SALES
                        [
                'npk' => '0014',
                'nama' => 'Isthof Fathony',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 15, // MARKETING & SALES
                'department_id' => 7, // MARKETING
                'division_id' => 2, // Sesuaikan dengan kebutuhan
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '1660',
                'nama' => 'Sri Sulistiawati Kusuma',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 15, // MARKETING & SALES
                'department_id' => 7, // MARKETING
                'division_id' => 2, // Sesuaikan dengan kebutuhan
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0024',
                'nama' => 'Sri Wulandari',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 15, // MARKETING & SALES
                'department_id' => 7, // MARKETING
                'division_id' => 2, // Sesuaikan dengan kebutuhan
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0957',
                'nama' => 'Karina Permatasari',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 15, // MARKETING & SALES
                'department_id' => 7, // MARKETING
                'division_id' => 2, // Sesuaikan dengan kebutuhan
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // MARKETING, ENGINEERING, MOLD & 
                        [
                'npk' => '0489',
                'nama' => 'Ardiansyah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 16, // MARKETING, ENGINEERING, MOLD & TOOLING
                'department_id' => 18, // MODL $ TOOLING
                'division_id' => 2, // Sesuaikan dengan kebutuhan
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // MATCOMP & SUBCONT CONTROL
                        [
                'npk' => 'M924',
                'nama' => 'Muhamad Aril',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 17, // MATCOMP & SUBCONT CONTROL
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'P163',
                'nama' => 'Yasha Nurfauziah Azzahra',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 17, // MATCOMP & SUBCONT CONTROL
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // MOLD & TOOLING DESIGN
                        [
                'npk' => '0568',
                'nama' => 'Muh. Fahmi Asagaf',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 18, // MOLD & TOOLING DESIGN
                'department_id' => 2, // MOLD ENGINEERING
                'division_id' => 2, // BUSSINES DEV. & ENG
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0216',
                'nama' => 'Aldy Galvani',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 18, // MOLD & TOOLING DESIGN
                'department_id' => 2, // MOLD ENGINEERING
                'division_id' => 2, // BUSSINES DEV. & ENG
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0883',
                'nama' => 'Budi Wijayanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 18, // MOLD & TOOLING DESIGN
                'department_id' => 2, // MOLD ENGINEERING
                'division_id' => 2, // BUSSINES DEV. & ENG
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '1000',
                'nama' => 'Muhammad Arifudin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 18, // MOLD & TOOLING DESIGN
                'department_id' => 2, // MOLD ENGINEERING
                'division_id' => 2, // BUSSINES DEV. & ENG
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '1543',
                'nama' => 'Muhammad Muttaqin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 18, // MOLD & TOOLING DESIGN
                'department_id' => 2, // MOLD ENGINEERING
                'division_id' => 2, // BUSSINES DEV. & ENG
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // PARAMEDIS
                        [
                'npk' => 'H640',
                'nama' => 'Apip Hapinurdin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 19, // PARAMEDIS
                'department_id' => 16, // HC & GA
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // PLANT 
                        [
                'npk' => '1613',
                'nama' => 'Zaid Ilham Amrulah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 20, // PLANT ENGINEERING
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0591',
                'nama' => 'Ruslan Ependi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 20, // PLANT ENGINEERING
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0018',
                'nama' => 'Agus Nggermadi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 20, // PLANT ENGINEERING
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H214',
                'nama' => 'Ryan Sopyan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 20, // PLANT ENGINEERING
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H484',
                'nama' => 'Bayu Permana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 20, // PLANT ENGINEERING
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // PROCESS ENGINEERING
                        [
                'npk' => '0115',
                'nama' => 'Wahyudi Komala',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 20, // PROCESS ENGINEERING
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'P175',
                'nama' => 'Fallah Yunansyah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 20, // PROCESS ENGINEERING
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'P174',
                'nama' => 'Elang Saputra',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 20, // PROCESS ENGINEERING
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // PROCESS ENGINEERING PA
                        [
                'npk' => '0068',
                'nama' => 'Fredi Pranata',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 22, // PROCESS ENGINEERING PA
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0213',
                'nama' => 'Febri Mega Setiawan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 22, // PROCESS ENGINEERING PA
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // PROCESS ENGINEERING PI
                        [
                'npk' => '0046',
                'nama' => 'Ahmad Daerobi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 23, // PROCESS ENGINEERING PI
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0057',
                'nama' => 'Vebby Januar',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 23, // PROCESS ENGINEERING PI
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0066',
                'nama' => 'Eko Aji Nugroho',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 23, // PROCESS ENGINEERING PI
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0121',
                'nama' => 'Susilo Nugroho',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 23, // PROCESS ENGINEERING PI
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0375',
                'nama' => 'Mukti Hari',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 23, // PROCESS ENGINEERING PI
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0575',
                'nama' => 'Muhamad Yusuf Anshori',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 23, // PROCESS ENGINEERING PI
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H441',
                'nama' => 'Dian Sugiana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 23, // PROCESS ENGINEERING PI
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H442',
                'nama' => 'Tumingan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 23, // PROCESS ENGINEERING PI
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H787',
                'nama' => 'Wisnu Rizkyansyah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 23, // PROCESS ENGINEERING PI
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H788',
                'nama' => 'Muhammad Raka Ridwansyah Sulaeman',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 23, // PROCESS ENGINEERING PI
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H790',
                'nama' => 'Sigit Ferdian',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 23, // PROCESS ENGINEERING PI
                'department_id' => 13, // TECHNICAL SUPPORT
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // PROCUREMENT
                        [
                'npk' => '0522',
                'nama' => 'Andreas Ari Susilo',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 24, // PROCUREMENT
                'department_id' => 19, // PROCUREMENT
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '1560',
                'nama' => 'Lilik Satria Wibawa',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 24, // PROCUREMENT
                'department_id' => 19, // PROCUREMENT
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '1499',
                'nama' => 'Handi Bowo',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 24, // PROCUREMENT
                'department_id' => 19, // PROCUREMENT
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => 'H463',
                'nama' => 'Meliy Yana Safitri',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 24, // PROCUREMENT
                'department_id' => 19, // PROCUREMENT
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '1638',
                'nama' => 'Abdurrohim',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 24, // PROCUREMENT
                'department_id' => 19, // PROCUREMENT
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '1640',
                'nama' => 'Topan Adi Kurnia',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 24, // PROCUREMENT
                'department_id' => 19, // PROCUREMENT
                'division_id' => 3, // ADMIN
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            // PRODUCTION
                        [
                'npk' => '0156',
                'nama' => 'Dedi Juansah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0028',
                'nama' => 'Recky Resza',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0030',
                'nama' => 'Ahmad Juhaeni',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0031',
                'nama' => 'Sopian Hanapi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0038',
                'nama' => 'Naryo',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0039',
                'nama' => 'Deden Herdiana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0042',
                'nama' => 'Heru Nuryadi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0044',
                'nama' => 'Muhamad Taufik Huda Nugraha',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0045',
                'nama' => 'Asep Riki Bin Cahlan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0050',
                'nama' => 'Suryaman',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0054',
                'nama' => 'Castim Bin Datim',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0056',
                'nama' => 'Charisun Saputra',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0073',
                'nama' => 'Nurhadi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0080',
                'nama' => 'Didi Sutardi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0110',
                'nama' => 'Cusyanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0130',
                'nama' => 'Agus Suwanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0133',
                'nama' => 'Erwan Irawan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0173',
                'nama' => 'Angga Riyan Wahyu Utomo',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0230',
                'nama' => 'Supirmanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0323',
                'nama' => 'Krispriyanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0329',
                'nama' => 'Pirman Natawijaya',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0392',
                'nama' => 'Yuana Kurniawan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0458',
                'nama' => 'Achmat Kurniawan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0460',
                'nama' => 'Akosa Wahyu Sejati',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
            [
                'npk' => '0503',
                'nama' => 'Anggit Yuli Suwandi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7, // Sesuaikan dengan kebutuhan
            ],
                [
        'npk' => '0737',
        'nama' => 'Hanan Audzan Diesta Wijaya',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => '0893',
        'nama' => 'Zaki Fikri Maulana',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => '0894',
        'nama' => 'Akhmad Faozi',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => '1530',
        'nama' => 'Rendi',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => '1533',
        'nama' => 'Surya Maulana',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => '1534',
        'nama' => 'Maryun Muakad',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => '1536',
        'nama' => 'Shohib Bukhori Anwar',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => '1537',
        'nama' => 'Adi Darmadi',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => '1541',
        'nama' => 'Yana Apriana',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => '1547',
        'nama' => 'Agus Rusdhiana',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => '1548',
        'nama' => 'Fikri Haikal',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => '1549',
        'nama' => 'Ahmad Jubaedi',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => '1556',
        'nama' => 'Fikri Gumilar',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => '1557',
        'nama' => 'Yahya Rohyana',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => '1567',
        'nama' => 'Alfaozi',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => '1578',
        'nama' => 'Surya Antowa Pradana',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => '1583',
        'nama' => 'Kirwan',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => '1586',
        'nama' => 'Ibnu Nugraha',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => '1587',
        'nama' => 'Dedi Andrianto',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => '1590',
        'nama' => 'Marhaban',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
        [
        'npk' => 'H220',
        'nama' => 'Arief Ariansyah',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H165',
        'nama' => 'Deden Supriyatin',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H166',
        'nama' => 'Asep Sripudin',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H147',
        'nama' => 'Kharis Supriyadi',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H148',
        'nama' => 'Apriano',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H149',
        'nama' => 'Adam Firdaus Ramadhan',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H150',
        'nama' => 'M. Wildan Romadlon',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H151',
        'nama' => 'Muhammad Yudi Prakoso',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H152',
        'nama' => 'Riyan Abdul Rahman',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H153',
        'nama' => 'Sarwahid Datul Khafi',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H184',
        'nama' => 'Handi Arisandi',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H135',
        'nama' => 'Muhamad Ainun Najib',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H138',
        'nama' => 'Yusup Supriatman',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H141',
        'nama' => 'Ade Kurniawan',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H143',
        'nama' => 'Arief Santoso',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H176',
        'nama' => 'Ivan Rivandi',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H175',
        'nama' => 'Moh Tri Wibowo',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H178',
        'nama' => 'Ikmal Lusibyan',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H179',
        'nama' => 'Yadi Mulyadi',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H246',
        'nama' => 'Wahyudi',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H248',
        'nama' => 'Tazwa Andika Heryadi',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
        [
        'npk' => 'H226',
        'nama' => 'Guntur Saputro',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H227',
        'nama' => 'Agung Hardiansyah',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H231',
        'nama' => 'Wili Riski Priyanto',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H236',
        'nama' => 'Ruspendi Hidayat',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H240',
        'nama' => 'Fatkhurohman',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H245',
        'nama' => 'Heri Haryanto',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H250',
        'nama' => 'Trisyanto',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H270',
        'nama' => 'Yuyut Adi Purnawan',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H271',
        'nama' => 'Wahyu Priyanto',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H298',
        'nama' => 'Shodiqul Alim',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H301',
        'nama' => 'Andri Nursalim',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H302',
        'nama' => 'Andika Mistahudin Ngusadani',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H303',
        'nama' => 'Rokhmat Subarkah',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H304',
        'nama' => 'Surya Adi Antowa',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H319',
        'nama' => 'Irvan Wahyu Purnomo',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H325',
        'nama' => 'Baqi Maolana Risqi',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H330',
        'nama' => 'Agung Widya Prabawa',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H331',
        'nama' => 'Fahri Abdul Malik',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H332',
        'nama' => 'Maulana Dwi Sutanto',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H338',
        'nama' => 'Indra Bhakti Nugroho',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H320',
        'nama' => 'Tamrin',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H321',
        'nama' => 'M. Khoerul',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H339',
        'nama' => 'Daam Darmawan',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H340',
        'nama' => 'Yusup',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
        [
        'npk' => 'H346',
        'nama' => 'Afif Andika',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25, // ID Section: PRODUCTION
        'department_id' => 2, // ID Department: PRODUCTION
        'division_id' => 1, // ID Division: PLANT
        'role_id' => 7,
    ],
    [
        'npk' => 'H352',
        'nama' => 'Ripki Hapipi Haryanto',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H366',
        'nama' => 'Muhammad Supriyadi',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H367',
        'nama' => 'Muhamad Rizki Romadon',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H370',
        'nama' => 'Eka Yoga Pratama',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H371',
        'nama' => 'Teguh Supriyanto',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H372',
        'nama' => 'Dona Dwi Andriana',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H373',
        'nama' => 'Sandika',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H376',
        'nama' => 'Alfian Achmad Fauzi',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H377',
        'nama' => 'Devan',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H378',
        'nama' => 'Rindi',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H379',
        'nama' => 'Filda Restu Nur Wijaya',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H380',
        'nama' => 'Yusuf Khoiruddin',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H381',
        'nama' => 'Syahrul Gunawan',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H382',
        'nama' => 'Robi Maulana',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H383',
        'nama' => 'Ahmad Ryan Febriansyah',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H384',
        'nama' => 'Herdy Dzulhiawan',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H385',
        'nama' => 'Aris Rinaldi',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H388',
        'nama' => 'Umi Azizah',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H70',
        'nama' => 'Galih Haji Saputra',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H47',
        'nama' => 'Wanto Hermawan',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H42',
        'nama' => 'Heri Eprianto',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H44',
        'nama' => 'Muhammad Ficky Mulyadi',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H83',
        'nama' => 'Syaeful Imam',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H493',
        'nama' => 'Yayan Sunandi',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H504',
        'nama' => 'Egi Sugiana',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H506',
        'nama' => 'Eka Purnama Rintia Sari',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H508',
        'nama' => 'Riska Rosdiyani',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H509',
        'nama' => 'Ricky Bayu Saputra',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H16',
        'nama' => 'Fingki Ajisopyan',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H07',
        'nama' => 'Andi Trimoyo',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H30',
        'nama' => 'Raska',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H121',
        'nama' => 'Rian Bahtiar',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H123',
        'nama' => 'Agung Kurnia',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H174',
        'nama' => 'Reyhan Mukti Nugroho',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H177',
        'nama' => 'Andre Luki Pratama',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H200',
        'nama' => 'Tedi Tri Mulyadi',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H216',
        'nama' => 'Intan Nurtanjil',
        'password' => bcrypt('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
                [
                'npk' => 'H502',
                'nama' => 'Dwi Muhamad Sofyan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID section untuk PRODUCTION
                'department_id' => 2, // ID department untuk PRODUCTION
                'division_id' => 1, // ID division untuk PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H490',
                'nama' => "Nanang Ma'Ruf",
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '1629',
                'nama' => 'Nandar Hendriyanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '1631',
                'nama' => 'Ikhbal Arie Setiawan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '1632',
                'nama' => 'Alan Martiansyah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '1633',
                'nama' => 'Rachman Ardiansyah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '1634',
                'nama' => 'Rian Astak Duriat',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '1635',
                'nama' => 'Muhammad Robiansyah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H530',
                'nama' => 'Reno L Pamas',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
                        [
                'npk' => 'H531',
                'nama' => 'Wempi Ravi Audric',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID section untuk PRODUCTION
                'department_id' => 2, // ID department untuk PRODUCTION
                'division_id' => 1, // ID division untuk PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H403',
                'nama' => 'Muhamad Avicenna Rizky',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H404',
                'nama' => 'Imam Mudjianto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H406',
                'nama' => 'Bia Adriansyah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H407',
                'nama' => 'Jamaludin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H408',
                'nama' => 'Dimas Andisa',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H414',
                'nama' => 'Muhammad Arifin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H419',
                'nama' => 'Endang Koswara',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H428',
                'nama' => 'Aa Miftahudin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H429',
                'nama' => 'Ari Muhamad Ridwan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H430',
                'nama' => 'Irpan Hasanudin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H427',
                'nama' => 'Syadam Haykal',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H431',
                'nama' => 'Ferian Nur Ikhsan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H426',
                'nama' => 'Yudi Handoko',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H432',
                'nama' => 'Aprily Oka Wibowo',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H433',
                'nama' => 'Akhmad Nur Fatoni',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H548',
                'nama' => 'Dandi Ardiansyah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H547',
                'nama' => 'Vikry Ardiansyah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H558',
                'nama' => 'Yandri Febrianto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H556',
                'nama' => 'Soli Prasetiyo',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
                        [
                'npk' => '1646',
                'nama' => 'Adhitya Pratama Putra Setiawan',
                'password' => Hash::make('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID untuk PRODUCTION
                'department_id' => 2, // ID untuk PRODUCTION
                'division_id' => 1, // ID untuk PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H562',
                'nama' => 'Darus Salam',
                'password' => Hash::make('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H597',
                'nama' => 'Dimas Pandu Maning Gusti',
                'password' => Hash::make('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H598',
                'nama' => 'Bustomi Ali',
                'password' => Hash::make('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
                [
        'npk' => 'H599',
        'nama' => 'Dionisius Dhay',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => '1651',
        'nama' => 'Bambang Tetuko',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H617',
        'nama' => 'Aris Firdaus',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H618',
        'nama' => 'Nahdi Suwandi',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H495',
        'nama' => 'Sarip Saepudin',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H638',
        'nama' => 'Armika',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H514',
        'nama' => 'Fian Khairudin',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H519',
        'nama' => 'Tri Wibowo',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H520',
        'nama' => 'Bayu Amiruddin',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H522',
        'nama' => 'Sartika Dewi',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H523',
        'nama' => 'Rizki Anggun',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H533',
        'nama' => 'Muhammad Rizky',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H534',
        'nama' => 'Andri Guntama',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H699',
        'nama' => 'Raden Muhammad Rizaldi Fauzy',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H695',
        'nama' => 'Wawan Hardiana',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H696',
        'nama' => 'Muhamad Hatta',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'M893',
        'nama' => 'Muhamad Firmansyah',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H580',
        'nama' => 'Darmawan Saputra',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H581',
        'nama' => 'M. Hermawan Dani',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H582',
        'nama' => 'Rizwan Mulyana',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H584',
        'nama' => 'Jaenal Abidin',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H585',
        'nama' => 'Aldi Difanza',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H586',
        'nama' => 'M Wijanatul Firdaus',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H587',
        'nama' => 'Farhan Djulianto',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H588',
        'nama' => 'Jeremi Paulian',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H589',
        'nama' => 'Andi Irawan',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
    [
        'npk' => 'H590',
        'nama' => 'Mahendra Ahmad',
        'password' => Hash::make('1234'),
        'no_telp' => $this->generateRandomPhoneNumber(),
        'section_id' => 25,
        'department_id' => 2,
        'division_id' => 1,
        'role_id' => 7,
    ],
            [
                'npk' => 'M872',
                'nama' => 'Pandu Rismaya',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // PRODUCTION
                'department_id' => 2, // PRODUCTION
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => 'H571',
                'nama' => 'Muhhamad Faisal',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H572',
                'nama' => 'Aldi Nurfauzie',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H573',
                'nama' => 'Ardika Eka Saputra',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H574',
                'nama' => 'Sutaman',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H575',
                'nama' => 'Candra Adhi Nugroho',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H576',
                'nama' => 'Slamet Kuswanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H578',
                'nama' => 'Susanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M886',
                'nama' => 'Bambang Sumantri',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M890',
                'nama' => 'Luthfi Humam',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M892',
                'nama' => 'Muahammad Farhan Luthfi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H609',
                'nama' => 'Sultan Aji Satria',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H610',
                'nama' => 'Rohim Makumulloh',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H611',
                'nama' => 'Muhamad Iqbal Maulana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H621',
                'nama' => 'Riswanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H622',
                'nama' => 'Ajang Sugiman',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H623',
                'nama' => 'Wahyudi yanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H624',
                'nama' => 'Edy Yulianto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H625',
                'nama' => 'Joko Priono',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H626',
                'nama' => 'Nur Cahyono',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H627',
                'nama' => 'Sutriono',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H628',
                'nama' => 'Dian Wahyudi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H629',
                'nama' => 'Makmur',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H633',
                'nama' => 'Rudianto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H634',
                'nama' => 'Sulistiyo',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M898',
                'nama' => 'Rangga Aditya',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M899',
                'nama' => 'Ghifari Albaighi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M900',
                'nama' => 'Ahmad Muzadzi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M904',
                'nama' => 'Syahril Hildano',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M906',
                'nama' => 'Nasul',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M909',
                'nama' => 'Anang Makruf',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M913',
                'nama' => 'Khomar',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M914',
                'nama' => 'Azhimi Al Baehaqi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M918',
                'nama' => 'Muhamad Irfan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M923',
                'nama' => 'Taufik Hidayatullah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '1668',
                'nama' => 'Nurul Hakim',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '1669',
                'nama' => 'M. Sairofi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
                        [
                'npk' => '1670',
                'nama' => 'Suheriadi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // Ganti dengan ID yang sesuai
                'department_id' => 2, // Ganti dengan ID yang sesuai
                'division_id' => 1, // Ganti dengan ID yang sesuai
                'role_id' => 7,
            ],
            [
                'npk' => 'M926',
                'nama' => 'Nicholas Saputra',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M928',
                'nama' => 'Ramadhon',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M930',
                'nama' => 'Rizki Ramdhani',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M931',
                'nama' => 'Parhan Nur Ali',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M933',
                'nama' => 'Fauzan Ramadhan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H654',
                'nama' => 'HARIS SABARNO',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H656',
                'nama' => 'Herman Zulkipli',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M936',
                'nama' => 'Ending',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M937',
                'nama' => 'Gilang Permana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M938',
                'nama' => 'Mustopa',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M941',
                'nama' => 'Sonny Durahman',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M943',
                'nama' => 'Feri Irawan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H659',
                'nama' => 'Sugih Mukti',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H660',
                'nama' => 'Alwi Zakaria Maulana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H661',
                'nama' => 'Bintang Aprian Firmansyah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H662',
                'nama' => 'Eram Supriatna',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H663',
                'nama' => 'Fani Khaerul Triyansyah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H664',
                'nama' => 'Nazmudin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H666',
                'nama' => 'Syahril Akbar',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H667',
                'nama' => 'Fatahilah Dias W.K',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H668',
                'nama' => 'Dandi Saputra',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25,
                'department_id' => 2,
                'division_id' => 1,
                'role_id' => 7,
            ],
                        [
                'npk' => 'H669',
                'nama' => 'Wahyudi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PRODUCTION
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H670',
                'nama' => 'M. Rizal .W',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PRODUCTION
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H671',
                'nama' => 'Oktafiyadi H',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PRODUCTION
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H672',
                'nama' => 'Syahrul F',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PRODUCTION
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H673',
                'nama' => 'Hasan Sarip H',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PRODUCTION
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'M944',
                'nama' => 'Miocalm Zyach Dear Surya R.',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PRODUCTION
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'M945',
                'nama' => 'Riki Christian Damopoli',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PRODUCTION
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H682',
                'nama' => 'Givo Muhammad Rizky',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PRODUCTION
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'M950',
                'nama' => 'Faisal Ardiansyah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PRODUCTION
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'P161',
                'nama' => 'Mutiara Kaila Putri',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PRODUCTION
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H725',
                'nama' => 'Helmi Nur Irvan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PRODUCTION
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H726',
                'nama' => 'Yogi Nandi Pratama',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PRODUCTION
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H728',
                'nama' => 'Islapani',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PRODUCTION
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'M953',
                'nama' => 'Zulhiadi Ismail',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PRODUCTION
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'M954',
                'nama' => 'Rizki Fadillah Akhbar',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PRODUCTION
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'M955',
                'nama' => 'Agung Supriyanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PRODUCTION
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'M956',
                'nama' => 'Adi Prima Wardana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PRODUCTION
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H754',
                'nama' => 'M Sigit Nugraha Darmawan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PRODUCTION
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H756',
                'nama' => 'Diana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PRODUCTION
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
                        [
                'npk' => 'M967',
                'nama' => 'Noval Rizki Ramadan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PLANT
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H773',
                'nama' => 'Riki Fajar Sopandi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PLANT
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H774',
                'nama' => 'Ilham Ramdatul Iksan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PLANT
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H775',
                'nama' => 'Bima Adjie Santosa',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PLANT
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'M971',
                'nama' => 'Heru Setiawan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PLANT
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'M972',
                'nama' => 'Ahmad Maulana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PLANT
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'M973',
                'nama' => 'Hermawan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PLANT
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'M974',
                'nama' => 'Muhammad Faiz Akbar',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PLANT
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'M975',
                'nama' => 'Rifaldo Lambok Purba',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PLANT
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'M976',
                'nama' => 'Ivanka Raihan Adammalika',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PLANT
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'M982',
                'nama' => 'Yusuf Saepul Anam',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PLANT
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'M983',
                'nama' => 'M Fikri Geovani',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PLANT
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'M984',
                'nama' => 'Wicassy Freda',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PLANT
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'M985',
                'nama' => 'Parhan Muhamad Pasha',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PLANT
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'M986',
                'nama' => 'Reza Dwi Andihika',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PLANT
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'M988',
                'nama' => 'Fauza Aditya',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PLANT
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H791',
                'nama' => 'Farhan Fahrezi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 25, // ID Section: PLANT
                'department_id' => 2, // ID Department: PRODUCTION
                'division_id' => 1, // ID Division: PLANT
                'role_id' => 7, // default
            ],
            // PRODUCTION PLANNING & MRP
            [
                'npk' => '0164',
                'nama' => 'Mohamad Fauzi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 26, // PRODUCTION PLANNING & MRP
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => '0268',
                'nama' => 'Ropik Nur Faizal',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 26,
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0448',
                'nama' => 'Syarif Hidayat',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 26,
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '1553',
                'nama' => 'Janu Rohmani',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 26,
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0051',
                'nama' => 'Waldi Firdaus',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 26,
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M917',
                'nama' => 'Ashari Azis',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 26,
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7,
            ],
            // PROJECT ENGINEERING
            [
                'npk' => '0029',
                'nama' => 'Roto Abdullah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 27, // PROJECT ENGINEERING
                'department_id' => 3, // ENGINEERING
                'division_id' => 2, // BUSSINES DEV. & ENG
                'role_id' => 7,
            ],
            [
                'npk' => '0058',
                'nama' => 'Aldi Rian Ripai',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 27,
                'department_id' => 3,
                'division_id' => 2,
                'role_id' => 7,
            ],
            [
                'npk' => '0117',
                'nama' => 'Christina Suryani',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 27,
                'department_id' => 3,
                'division_id' => 2,
                'role_id' => 7,
            ],
            [
                'npk' => '0647',
                'nama' => 'Odhi Apriyan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 27,
                'department_id' => 3,
                'division_id' => 2,
                'role_id' => 7,
            ],
            [
                'npk' => '1497',
                'nama' => 'Rizqy Faizal Muttaqin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 27,
                'department_id' => 3,
                'division_id' => 2,
                'role_id' => 7,
            ],
            [
                'npk' => '1611',
                'nama' => 'Muhamad Gojali Rahman',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 27,
                'department_id' => 3,
                'division_id' => 2,
                'role_id' => 7,
            ],
            [
                'npk' => '1617',
                'nama' => 'Suryono',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 27,
                'department_id' => 3,
                'division_id' => 2,
                'role_id' => 7,
            ],
            [
                'npk' => '1513',
                'nama' => 'Wahyu Satriyo Ramadhany',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 27,
                'department_id' => 3,
                'division_id' => 2,
                'role_id' => 7,
            ],
            [
                'npk' => 'P171',
                'nama' => 'Hafizh Musthafa',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 27,
                'department_id' => 3,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '1673',
                'nama' => 'Rizal Pahlevi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 27,
                'department_id' => 3,
                'division_id' => 2,
                'role_id' => 7,
            ],
            // QUALITY ASSURANCE
                        [
                'npk' => '0075',
                'nama' => 'Wendra Ari Wibowo',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 28, // QUALITY ASSURANCE
                'department_id' => 5, // QUALITY
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => '0076',
                'nama' => 'Ahmad Kumaedi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 28,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0077',
                'nama' => 'Ray Vhalent',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 28,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0078',
                'nama' => 'Ahmad Tahroji',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 28,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0079',
                'nama' => 'Rudi Hardiansyah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 28,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0081',
                'nama' => 'Wandi Mustofah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 28,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0082',
                'nama' => 'Rudiansyah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 28,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0083',
                'nama' => 'Irfan Kristanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 28,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0084',
                'nama' => 'Teguh Iman Septiaji',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 28,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0085',
                'nama' => 'Iqbal Hidayat',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 28,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0086',
                'nama' => 'Luthfianto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 28,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0087',
                'nama' => 'Erwan Fahmi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 28,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0088',
                'nama' => 'Abdul Basit Romadhoni',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 28,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0089',
                'nama' => 'Ahmad Nurkhalim',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 28,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0090',
                'nama' => 'Rifan Sah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 28,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0091',
                'nama' => 'Dandi Ardiansyah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 28,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0093',
                'nama' => 'AKHADUL MUSTOFA',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 28,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0094',
                'nama' => 'EGY SURYANA',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 28,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0095',
                'nama' => 'Ahmad Zaneal Zayadi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 28,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            // QUALITY CONTROL
            [
                'npk' => '0012',
                'nama' => 'Stanislaus Kostha Putro Eko Kurniawan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29, // QUALITY CONTROL
                'department_id' => 5, // QUALITY
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => '0106',
                'nama' => 'Adhi Arya Setiawan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '1569',
                'nama' => 'Yogi Apriandi Putra',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0127',
                'nama' => 'Muhamad Atieq',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0026',
                'nama' => 'Sahrudin Hidayat',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0074',
                'nama' => 'Mirawan Nursoleh',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0197',
                'nama' => 'Dedi Rumdani',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0272',
                'nama' => 'Muhammad Muhlisin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0289',
                'nama' => 'Sana Kuspriatna',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '0348',
                'nama' => 'Durotun Nasihin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '1564',
                'nama' => 'Eko Purjianto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => '1594',
                'nama' => 'Muslikhudin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H170',
                'nama' => 'Lia Yuliana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H162',
                'nama' => 'Rahmat Nur Arifin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H219',
                'nama' => 'Dzikri Syahlatif',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H243',
                'nama' => 'Tri Hartami Tungga Dewi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H350',
                'nama' => 'Ginanjar',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H354',
                'nama' => 'Dede Kurniawan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H392',
                'nama' => 'Sawaludin Al Paidul Kodir',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H99',
                'nama' => 'Chaerul Chandra',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H511',
                'nama' => 'Mahasa',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H512',
                'nama' => 'Iil Mulyadi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29, // Sesuaikan dengan section_id yang relevan
                'department_id' => 5, // QUALITY
                'division_id' => 1, // Sesuaikan dengan division_id yang relevan
                'role_id' => 7, // Sesuaikan dengan role_id yang relevan
            ],
            [
                'npk' => 'H89',
                'nama' => 'Ahmad Alfan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H128',
                'nama' => 'Heri Fitriono',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H126',
                'nama' => 'Andi Nur Setiawan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H560',
                'nama' => 'Egi Dwi Prasetia',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H600',
                'nama' => 'Doni Awal Altika',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H697',
                'nama' => 'Yadi Suryafi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H583',
                'nama' => 'Muhamad Fadil Risnandar',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H591',
                'nama' => 'Rizki Agus Solih',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H568',
                'nama' => 'Ahmad Rosid',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H569',
                'nama' => 'Bayu Prasetyawan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H592',
                'nama' => 'Khoirul Ikhwan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H593',
                'nama' => 'Muhammad Suthoni',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H594',
                'nama' => 'Mohammad Arsal',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M882',
                'nama' => 'Fikri Zainul Arifin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H612',
                'nama' => 'Fernanda Wijaya Kusumah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 29,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
                        [
                'npk' => 'H613',
                'nama' => 'Mulya Pratama',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30, // SECTION ID
                'department_id' => 5, // QUALITY CONTROL
                'division_id' => 1, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H631',
                'nama' => 'Rachmat Dwi Prasetyo',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H635',
                'nama' => 'Ari Sutardi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M916',
                'nama' => 'Muhlis Nurirfan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'P145',
                'nama' => 'Kurniaty Arlyani Putry',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'P146',
                'nama' => 'Hafizha Ustiana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H657',
                'nama' => 'Tomy Suprastomo',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M948',
                'nama' => 'Yusup Maulana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H687',
                'nama' => 'Wahyudin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'P162',
                'nama' => 'Destian Nurfadillah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H723',
                'nama' => 'Bintang Jangga Mahardika',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H724',
                'nama' => 'Cristoforus Dimas Tri Wicaksono',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H757',
                'nama' => 'Aditia Pratama',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H758',
                'nama' => 'Yugi Iskandar',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H759',
                'nama' => 'Djabar Linuhung',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H760',
                'nama' => 'Gilang Agil Usman Azis',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'P168',
                'nama' => 'Padli Wisnu A',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M968',
                'nama' => 'Rizki Maulana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H776',
                'nama' => 'Kiki Alfyan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H777',
                'nama' => 'Sahepi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'H778',
                'nama' => 'Ujang Jayadi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            [
                'npk' => 'M987',
                'nama' => 'Aditya Ramdani',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30,
                'department_id' => 5,
                'division_id' => 1,
                'role_id' => 7,
            ],
            // QUALITY ENGINEERING
                        [
                'npk' => '0016',
                'nama' => 'Fransiskus Aris Wahyu Nugroho',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30, // QUALITY ENGINEERING
                'department_id' => 5, // QUALITY
                'division_id' => 1, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => '0033',
                'nama' => 'Khoerul Umam',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30, // QUALITY ENGINEERING
                'department_id' => 5, // QUALITY
                'division_id' => 1, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => '0292',
                'nama' => 'Irfansyah Juliana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30, // QUALITY ENGINEERING
                'department_id' => 5, // QUALITY
                'division_id' => 1, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => '0352',
                'nama' => 'Iqbal Maulana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30, // QUALITY ENGINEERING
                'department_id' => 5, // QUALITY
                'division_id' => 1, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => '0609',
                'nama' => 'Luki Widiyanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30, // QUALITY ENGINEERING
                'department_id' => 5, // QUALITY
                'division_id' => 1, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => '1554',
                'nama' => 'Adam Malik Fajar',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30, // QUALITY ENGINEERING
                'department_id' => 5, // QUALITY
                'division_id' => 1, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H222',
                'nama' => 'Galuh Sera Eka Karyanto Bl',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30, // QUALITY ENGINEERING
                'department_id' => 5, // QUALITY
                'division_id' => 1, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H249',
                'nama' => 'Ami Khoerunisa',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30, // QUALITY ENGINEERING
                'department_id' => 5, // QUALITY
                'division_id' => 1, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => '1663',
                'nama' => 'Eka Prasetya',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30, // QUALITY ENGINEERING
                'department_id' => 5, // QUALITY
                'division_id' => 1, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'P172',
                'nama' => 'Alfin Jofandi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30, // QUALITY ENGINEERING
                'department_id' => 5, // QUALITY
                'division_id' => 1, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'P173',
                'nama' => 'Rozan Adiyatama S',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 30, // QUALITY ENGINEERING
                'department_id' => 5, // QUALITY
                'division_id' => 1, // PLANT
                'role_id' => 7, // default
            ],
            // R&D AND PRODUCT DEV.
                        [
                'npk' => '1606',
                'nama' => 'Septiyen Abdullah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 31, // R&D AND PRODUCT DEV.
                'department_id' => 3, // ENGINEERING
                'division_id' => 2, // BUSSINES DEV. & ENG
                'role_id' => 7, // default
            ],
            [
                'npk' => '1618',
                'nama' => 'Muhamad Fajar Sidiq',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 31, // R&D AND PRODUCT DEV.
                'department_id' => 3, // ENGINEERING
                'division_id' => 2, // BUSSINES DEV. & ENG
                'role_id' => 7, // default
            ],
            [
                'npk' => '1648',
                'nama' => 'Ferdy Rezka Refanio',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 31, // R&D AND PRODUCT DEV.
                'department_id' => 3, // ENGINEERING
                'division_id' => 2, // BUSSINES DEV. & ENG
                'role_id' => 7, // default
            ],
            // RECRUITMENT, TRAINING & GA
                        [
                'npk' => '0013',
                'nama' => 'Fendi Yustriyoso',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 32, // RECRUITMENT, TRAINING & GA
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            [
                'npk' => '0813',
                'nama' => 'Nuraeni',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 32, // RECRUITMENT, TRAINING & GA
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H480',
                'nama' => 'Gilang Permana Putra',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 32, // RECRUITMENT, TRAINING & GA
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            [
                'npk' => '1661',
                'nama' => 'Muhammad Farhan Dudding',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 32, // RECRUITMENT, TRAINING & GA
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            // SALES ADMIN & PRICE CONTROL
            [
                'npk' => '0393',
                'nama' => 'Florencia Theresia',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 33, // SALES ADMIN & PRICE CONTROL
                'department_id' => 17, // MARKETING
                'division_id' => 2, // BUSSINES DEV. & ENG
                'role_id' => 7, // default
            ],
            [
                'npk' => '1644',
                'nama' => 'Nadila Insani Putri',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 33, // SALES ADMIN & PRICE CONTROL
                'department_id' => 17, // MARKETING
                'division_id' => 2, // BUSSINES DEV. & ENG
                'role_id' => 7, // default
            ],
            // SECURITY
                        [
                'npk' => 'S02',
                'nama' => 'Ade Rismanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 34, // SECURITY
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            [
                'npk' => 'S03',
                'nama' => 'Ari Sasongko',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 34, // SECURITY
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            [
                'npk' => 'S04',
                'nama' => 'Christianto S',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 34, // SECURITY
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            [
                'npk' => 'S05',
                'nama' => 'Dani Mardani',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 34, // SECURITY
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            [
                'npk' => 'S07',
                'nama' => 'Madi Ahmadi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 34, // SECURITY
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            [
                'npk' => 'S11',
                'nama' => 'Ojang Sonjaya',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 34, // SECURITY
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            [
                'npk' => 'S12',
                'nama' => 'Rifki Bahtiar',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 34, // SECURITY
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            [
                'npk' => 'S13',
                'nama' => 'Suparwiyanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 34, // SECURITY
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            [
                'npk' => 'S14',
                'nama' => 'Sutrisno',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 34, // SECURITY
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            [
                'npk' => 'S15',
                'nama' => 'Yayat',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 34, // SECURITY
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            [
                'npk' => 'S16',
                'nama' => 'Aep Saepudin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 34, // SECURITY
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            [
                'npk' => 'S17',
                'nama' => 'Darim Mulyana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 34, // SECURITY
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            [
                'npk' => 'S18',
                'nama' => 'Sopian',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 34, // SECURITY
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            [
                'npk' => 'S19',
                'nama' => 'Deka Sapriana Saputra',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 34, // SECURITY
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            [
                'npk' => 'S20',
                'nama' => 'Indra Purnama',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 34, // SECURITY
                'department_id' => 16, // HC & GA
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            // SUPPORTING FACILITY
                        [
                'npk' => '0395',
                'nama' => 'Cahyo Purwito',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => '0483',
                'nama' => 'Anan Bayu Lesmana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => '0523',
                'nama' => 'Arizal Diantono',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => '1584',
                'nama' => 'Budhik Darwanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => '0449',
                'nama' => 'Marjuki',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => '0048',
                'nama' => 'Mohamad Sapikin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H173',
                'nama' => 'Hamdan Sakuro',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H356',
                'nama' => 'Ari Nurrohman',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H108',
                'nama' => 'Ayub Hidayat',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H109',
                'nama' => 'Roby Muharam',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H110',
                'nama' => 'Didi Mardi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H76',
                'nama' => 'Muhamad Yanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H31',
                'nama' => 'Rian Juliawan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H12',
                'nama' => 'Dedi Suherman',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H51',
                'nama' => 'Rizki Nugraha',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H288',
                'nama' => 'Rahmat Juliana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H561',
                'nama' => 'Nur Afdilah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H601',
                'nama' => 'Yohanes Rikardus Poso',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H602',
                'nama' => 'Dede Cahyadi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H483',
                'nama' => 'Didit Cahyo Nugroho',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H496',
                'nama' => 'Ico Beny Zulbihandoko',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => '1657',
                'nama' => 'Muhamad Dimas Arif Fatchurozak',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'M883',
                'nama' => 'Rizki Nugraha Putra',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H674',
                'nama' => 'Marianus Barbarigo Sugi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H680',
                'nama' => 'Didin Syamsyudin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H681',
                'nama' => 'Ikhwan Nurul Afni',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'M951',
                'nama' => 'Fajar Ramadhan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H722',
                'nama' => 'Putra Irfanudin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H729',
                'nama' => 'Imam Nawawi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H730',
                'nama' => 'Muhammad Iqbal',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H731',
                'nama' => 'Mad Doni',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H732',
                'nama' => 'Hari Akbad Romadon',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H733',
                'nama' => 'M Davit Ferdiansyah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H734',
                'nama' => 'Syaiprudin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H735',
                'nama' => 'Ridwan Saputra',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H736',
                'nama' => 'I Ahmad Rifai',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H737',
                'nama' => 'Toni',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H739',
                'nama' => 'Ebih Sabini',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 35, // SUPPORTING FACILITY
                'department_id' => 1, // PPIC
                'division_id' => 2, // PLANT
                'role_id' => 7, // default
            ],
            // TOOLING MAINTENANCE
                        [
                'npk' => '0020',
                'nama' => 'Bustanil Arifin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 36, // TOOLING MAINTENANCE
                'department_id' => 18, // MOLD & TOOLING
                'division_id' => 2, // BUSSINES DEV. & ENG
                'role_id' => 7, // default
            ],
            [
                'npk' => '0062',
                'nama' => 'Jajang Suarja',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 36, 
                'department_id' => 18, 
                'division_id' => 2, 
                'role_id' => 7, 
            ],
            [
                'npk' => '0285',
                'nama' => 'Ghardika Puji Kusuma',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 36, 
                'department_id' => 18, 
                'division_id' => 2, 
                'role_id' => 7, 
            ],
            [
                'npk' => '0320',
                'nama' => 'Hendra',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 36, 
                'department_id' => 18, 
                'division_id' => 2, 
                'role_id' => 7, 
            ],
            [
                'npk' => '0600',
                'nama' => 'Faesal',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 36, 
                'department_id' => 18, 
                'division_id' => 2, 
                'role_id' => 7, 
            ],
            [
                'npk' => '0717',
                'nama' => 'Muhamad Ayub',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 36, 
                'department_id' => 18, 
                'division_id' => 2, 
                'role_id' => 7, 
            ],
            [
                'npk' => '0719',
                'nama' => 'Muhammad Rachmat Romadhon',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 36, 
                'department_id' => 18, 
                'division_id' => 2, 
                'role_id' => 7, 
            ],
            [
                'npk' => '0977',
                'nama' => 'Afid Mujiyanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 36, 
                'department_id' => 18, 
                'division_id' => 2, 
                'role_id' => 7, 
            ],
            [
                'npk' => '1545',
                'nama' => 'Imam Arifin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 36, 
                'department_id' => 18, 
                'division_id' => 2, 
                'role_id' => 7, 
            ],
            [
                'npk' => 'H394',
                'nama' => 'Septian Wijaya',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 36, 
                'department_id' => 18, 
                'division_id' => 2, 
                'role_id' => 7, 
            ],
            [
                'npk' => 'H518',
                'nama' => 'Danang Saputra',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 36, 
                'department_id' => 18, 
                'division_id' => 2, 
                'role_id' => 7, 
            ],
            [
                'npk' => '1656',
                'nama' => 'Rifki Maulana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 36, 
                'department_id' => 18, 
                'division_id' => 2, 
                'role_id' => 7, 
            ],
            // VENDOR CONTROL
                        [
                'npk' => '0445',
                'nama' => 'Ricky Charles Merling',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 37, // VENDOR CONTROL
                'department_id' => 20, // VENDOR MANAGEMENT
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            [
                'npk' => '1607',
                'nama' => 'Difari Aizani Sujud',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 37, 
                'department_id' => 20, 
                'division_id' => 3, 
                'role_id' => 7, 
            ],
            [
                'npk' => '1609',
                'nama' => 'Ajie Bangun Alriyanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 37, 
                'department_id' => 20, 
                'division_id' => 3, 
                'role_id' => 7, 
            ],
            // VENDOR DEVELOPMENT
                        [
                'npk' => '0022',
                'nama' => 'Deden Ramdhani Hadimulya',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 38, // VENDOR DEVELOPMENT
                'department_id' => 20, // VENDOR MANAGEMENT
                'division_id' => 1, // PLANT
                'role_id' => 7, // default
            ],
            // VENDOR MANAGEMENT
                        [
                'npk' => '0215',
                'nama' => 'Andie Rohandie',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 39, // VENDOR MANAGEMENT
                'department_id' => 20, // PROCUREMENT
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            [
                'npk' => 'H364',
                'nama' => 'Muhammad Khoirul Rizqi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 39, // VENDOR MANAGEMENT
                'department_id' => 20, // PROCUREMENT
                'division_id' => 3, // ADMIN
                'role_id' => 7, // default
            ],
            // W/H FINISHED GOODS & DELIVERY
                        [
                'npk' => '0034',
                'nama' => 'Rian Febriana Ramadhan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, 
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7, // default
            ],
            [
                'npk' => '0092',
                'nama' => 'Edi Nurwanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, 
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7, // default
            ],
            [
                'npk' => '0096',
                'nama' => 'Hendrawan Prasetyo Aji',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, 
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7, // default
            ],
            [
                'npk' => '0243',
                'nama' => 'Karya Saputra',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, 
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7, // default
            ],
            [
                'npk' => '0270',
                'nama' => 'Suhandi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, 
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7, // default
            ],
            [
                'npk' => '0275',
                'nama' => 'Nurul Arifin',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, 
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7, // default
            ],
            [
                'npk' => '0300',
                'nama' => 'Achmad Muwaffiyul Achdi',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, 
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7, // default
            ],
            [
                'npk' => '0332',
                'nama' => 'Rio Prastomo',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, 
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7, // default
            ],
            [
                'npk' => '0339',
                'nama' => 'Abdurrohim',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, 
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7, // default
            ],
            [
                'npk' => '0452',
                'nama' => 'Sri Mulyani',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, 
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7, // default
            ],
            [
                'npk' => '0462',
                'nama' => 'Anggi Nur Huda',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, 
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7, // default
            ],
            [
                'npk' => '0465',
                'nama' => 'Ari Ramdani',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, 
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7, // default
            ],
            [
                'npk' => '0474',
                'nama' => 'Erwin Setyo Nugroho',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, 
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7, // default
            ],
            [
                'npk' => '0479',
                'nama' => 'Ari Nasta\'In',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, 
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7, // default
            ],
            [
                'npk' => '0551',
                'nama' => 'Risky Adi Riawan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, 
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7, // default
            ],
            [
                'npk' => '0554',
                'nama' => 'Agus Sriyanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, 
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7, // default
            ],
            [
                'npk' => '0714',
                'nama' => 'Dian Nurdiyanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, 
                'department_id' => 1,
                'division_id' => 1,
                'role_id' => 7, // default
            ],
                        [
                'npk' => '0967',
                'nama' => 'Abdul Rohim',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, // W/H FINISHED GOODS & DELIVERY
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => '1213',
                'nama' => 'Muhammad Ikhya',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, // W/H FINISHED GOODS & DELIVERY
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => '1539',
                'nama' => 'Firda Alfiansyah',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, // W/H FINISHED GOODS & DELIVERY
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => '1558',
                'nama' => 'Aiediel Alief',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, // W/H FINISHED GOODS & DELIVERY
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => '1565',
                'nama' => 'Irfan Malik',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, // W/H FINISHED GOODS & DELIVERY
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => '1566',
                'nama' => 'Bangkit Setya Darmawan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, // W/H FINISHED GOODS & DELIVERY
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => '1574',
                'nama' => 'Dimas Yudi Hidayat',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, // W/H FINISHED GOODS & DELIVERY
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => '1576',
                'nama' => 'Anwar Sodik',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, // W/H FINISHED GOODS & DELIVERY
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => '1577',
                'nama' => 'Wakhyu Harsono',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, // W/H FINISHED GOODS & DELIVERY
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => '1589',
                'nama' => 'Agung Laksono',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, // W/H FINISHED GOODS & DELIVERY
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => '1612',
                'nama' => 'Martinus Enndy Anggoro Putro',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, // W/H FINISHED GOODS & DELIVERY
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => '0650',
                'nama' => 'Faizal Indra Kusmawan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, // W/H FINISHED GOODS & DELIVERY
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => 'H160',
                'nama' => 'Abhis Parna Wijaya',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, // W/H FINISHED GOODS & DELIVERY
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => 'H167',
                'nama' => 'Arri Kurniawan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 40, // W/H FINISHED GOODS & DELIVERY
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            // W/H MATERIAL, COMP. & INCOMING
                        [
                'npk' => '0237',
                'nama' => 'Muchammad Edwin Fajar Pradana',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 41, // W/H MATERIAL, COMP. & INCOMING
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => '0613',
                'nama' => 'Dwi Septario Hariyanto',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 41, // W/H MATERIAL, COMP. & INCOMING
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => '0909',
                'nama' => 'Khaerul Wildan',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 41, // W/H MATERIAL, COMP. & INCOMING
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => 'H241',
                'nama' => 'Rizky Mubarak',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 41, // W/H MATERIAL, COMP. & INCOMING
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => 'H113',
                'nama' => 'Riski Puji Hastuti',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 41, // W/H MATERIAL, COMP. & INCOMING
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => '1639',
                'nama' => 'Yessica Nugrahaningrum',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 41, // W/H MATERIAL, COMP. & INCOMING
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
            [
                'npk' => 'H607',
                'nama' => 'Dimas Wijanarko',
                'password' => bcrypt('1234'),
                'no_telp' => $this->generateRandomPhoneNumber(),
                'section_id' => 41, // W/H MATERIAL, COMP. & INCOMING
                'department_id' => 1, // PPIC
                'division_id' => 1, // PLANT
                'role_id' => 7,
            ],
        ];
        foreach ($usersData as $key => $val) {
            User::create($val);
        };
    }
    private function generateRandomPhoneNumber()
    {
        // Nomor telepon awalan 081, 082, 083 (awalan provider di Indonesia)
        $prefix = '081'; 

        // Membuat 9 digit acak untuk melengkapi nomor telepon
        $number = str_pad(rand(0, 999999999), 9, '0', STR_PAD_LEFT);

        // Gabungkan prefix dengan nomor acak
        return $prefix . $number;
    }
}
