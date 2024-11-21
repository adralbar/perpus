<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Shift;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterShift;


class ShiftsImport implements ToCollection
{
    /**
     * @param Collection 
     */

    public function collection(Collection $collection)
    {
        $user = Auth::user();
        $roleId = $user->role_id; // Mendapatkan role_id dari pengguna

        $userSectionId = $user->section_id;
        $userDepartmentId = $user->department_id;

        $indexKe = 1;
        $errors = [];  // Menyimpan semua error yang ditemukan

        foreach ($collection as $row) {

            if (empty($row[0]) && empty($row[2]) && empty($row[3]) && empty($row[4])) {
                continue;
            }
            if ($indexKe > 1) {
                $data['npk'] = !empty($row[0]) ? $row[0] : '';
                $data['shift1'] = !empty($row[2]) ? $row[2] : '';
                $startDate = !empty($row[3]) ? Carbon::createFromFormat('Y-m-d', str_replace("'", "", $row[3])) : null;
                $endDate = !empty($row[4]) ? Carbon::createFromFormat('Y-m-d', str_replace("'", "", $row[4])) : null;

                if (in_array($roleId, [2, 9])) {
                    if ($startDate && $startDate->lte(Carbon::today())) {
                        $errors[] = "Start Date tidak boleh hari h atau sebelumnya untuk NPK: {$data['npk']} pada baris {$indexKe}";
                        continue;
                    }
                }
                if ($roleId == 2) {
                    $user = User::where('npk', $data['npk'])->first();
                    if ($user && $user->section_id !== $userSectionId) {
                        $errors[] = "NPK: {$data['npk']} tidak sesuai dengan section Anda pada baris {$indexKe}";
                        continue; // Lewatkan baris ini dan lanjut ke baris berikutnya
                    }
                }

                // Jika role_id adalah 9, pastikan department_id sama dengan department_id user yang sedang login
                if ($roleId == 9) {
                    $user = User::where('npk', $data['npk'])->first();
                    if ($user && $user->department_id !== $userDepartmentId) {
                        $errors[] = "NPK: {$data['npk']} tidak sesuai dengan department Anda pada baris {$indexKe}";
                        continue; // Lewatkan baris ini dan lanjut ke baris berikutnya
                    }
                }
                // Validasi NPK
                $user = User::where('npk', $data['npk'])->first();
                if (!$user) {
                    $errors[] = "NPK tidak ditemukan di baris " . $indexKe . " untuk NPK: " . $data['npk'];
                }

                $sectionId = $user ? $user->section_id : null;

                // Validasi shift
                if (!MasterShift::where('waktu', $data['shift1'])->exists()) {
                    $errors[] = "Shift tidak valid di baris " . $indexKe . " untuk shift: " . $data['shift1'];
                }

                // Validasi tanggal
                if ($startDate && $endDate && $startDate->gt($endDate)) {
                    $errors[] = "Tanggal mulai lebih besar dari tanggal selesai di baris " . $indexKe . ": " . $startDate->toDateString() . " - " . $endDate->toDateString();
                }

                // Jika tidak ada error, proses data
                if (empty($errors)) {
                    if ($startDate && $endDate && $sectionId !== null) {
                        while ($startDate->lte($endDate)) {
                            if ($startDate->isWeekend()) {
                                if ($startDate->isSaturday() && in_array($sectionId, [22, 40])) {
                                    $data['shift1'] = !empty($row[2]) ? $row[2] : '';
                                } else {
                                    $data['shift1'] = 'OFF';
                                }
                            } else {
                                $data['shift1'] = !empty($row[2]) ? $row[2] : '';
                            }

                            $data['date'] = $startDate->toDateString();

                            // Simpan data ke database
                            Shift::create($data);

                            $startDate->addDay();
                        }
                    }
                }
            }
            $indexKe++;
        }

        // Jika ada error, kembalikan semua error ke halaman
        if (!empty($errors)) {
            $errorMessages = implode('<br>', $errors);  // Menggabungkan semua error menjadi satu string
            return redirect()->back()->with('error', $errorMessages);
        }

        // Jika tidak ada error, beri pesan sukses
        return redirect()->back()->with('success', 'Data berhasil diimpor!');
    }
}
