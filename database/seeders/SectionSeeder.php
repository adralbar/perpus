<?php

namespace Database\Seeders;

use App\Models\SectionModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sectionData = [
            ['nama' => 'ACCOUNTING & TAX', 'department_id' => '15'],
            ['nama' => 'ADM. DIREKTUR', 'department_id' => '1'],
            ['nama' => 'AUTOMATION & SYSTEM ANALYST', 'department_id' => '13'],
            ['nama' => 'COSTING & COST CONTROL', 'department_id' => '15'],
            ['nama' => 'CS', 'department_id' => '16'],
            ['nama' => 'DIGITALISASI', 'department_id' => '13'],
            ['nama' => 'DRIVER', 'department_id' => '16'],
            ['nama' => 'ENVIRONMENT, HEALTHY & SAFETY & ISO', 'department_id' => '16'],
            ['nama' => 'FINANCE & ACCOUNTING', 'department_id' => '15'],
            ['nama' => 'FINANCE, BUDGET & ANALYSIS', 'department_id' => '15'],
            ['nama' => 'FINANCIAL PLANNING & ANALYSIS', 'department_id' => '15'],
            ['nama' => 'HC & GA', 'department_id' => '16'],
            ['nama' => 'HR & COMP. BENEFITS', 'department_id' => '16'],
            ['nama' => 'INVENTORY & COST CONTROL', 'department_id' => '15'],
            ['nama' => 'IT', 'department_id' => '16'],
            ['nama' => 'KLINIK', 'department_id' => '16'],
            ['nama' => 'MAINTENANCE & UTILITY', 'department_id' => '2'],
            ['nama' => 'MARKETING & SALES', 'department_id' => '17'],
            ['nama' => 'MARKETING, ENGINEERING, MOLD & TOOLING', 'department_id' => '18'],
            ['nama' => 'MATCOMP & SUBCONT CONTROL', 'department_id' => '1'],
            ['nama' => 'MOLD & TOOLING DESIGN', 'department_id' => '18'],
            ['nama' => 'MOLD MAINTENANCE', 'department_id' => '18'],
            ['nama' => 'OB', 'department_id' => '16'],
            ['nama' => 'PARAMEDIS', 'department_id' => '16'],
            ['nama' => 'PLANT ENGINEERING', 'department_id' => '13'],
            ['nama' => 'PLASTIK INJECTION', 'department_id' => '19'],
            ['nama' => 'PROCESS ENGINEERING', 'department_id' => '13'],
            ['nama' => 'PROCESS ENGINEERING PA', 'department_id' => '13'],
            ['nama' => 'PROCESS ENGINEERING PI', 'department_id' => '13'],
            ['nama' => 'PROCUREMENT', 'department_id' => '19'],
            ['nama' => 'PRODUCTION', 'department_id' => '2'],
            ['nama' => 'PRODUCTION IMPROVEMENT', 'department_id' => '2'],
            ['nama' => 'PRODUCTION PLANNING & MRP', 'department_id' => '1'],
            ['nama' => 'PROJECT ENGINEERING', 'department_id' => '3'],
            ['nama' => 'QUALITY ASSURANCE', 'department_id' => '5'],
            ['nama' => 'QUALITY CONTROL', 'department_id' => '5'],
            ['nama' => 'QUALITY ENGINEERING', 'department_id' => '5'],
            ['nama' => 'R&D AND PRODUCT DEV.', 'department_id' => '3'],
            ['nama' => 'RECRUITMENT, TRAINING & GA', 'department_id' => '16'],
            ['nama' => 'SALES ADMIN & PRICE CONTROL', 'department_id' => '17'],
            ['nama' => 'SECURITY', 'department_id' => '16'],
            ['nama' => 'SUPPORTING FACILITY', 'department_id' => '1'],
            ['nama' => 'TOOLING MAINTENANCE', 'department_id' => '18'],
            ['nama' => 'VENDOR CONTROL', 'department_id' => '20'],
            ['nama' => 'VENDOR DEVELOPMENT', 'department_id' => '20'],
            ['nama' => 'VENDOR MANAGEMENT', 'department_id' => '19'],
            ['nama' => 'W/H FINISHED GOODS & DELIVERY', 'department_id' => '1'],
            ['nama' => 'W/H MATERIAL, COMP. & INCOMING', 'department_id' => '1'],
        ];

        foreach ($sectionData as $key => $val) {
            SectionModel::create($val);
        };
    }
}
