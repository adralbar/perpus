<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Shift;
use Illuminate\Http\Request;
use App\Imports\ShiftsImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Models\DepartmentModel;
use App\Models\DivisionModel;
use App\Models\RoleModel;
use App\Models\SectionModel;
use App\Models\User;

class shiftController extends Controller
{
    public function index()
    {
        return view('shift.shift');
    }

    public function getData(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $data = shift::select([
            'kategorishift.id',
            'kategorishift.npk',
            'kategorishift.shift1',
            'kategorishift.date',
            'users.nama'
        ])
            ->join('users', 'kategorishift.npk', '=', 'users.npk')
            ->orderBy('kategorishift.date', 'DESC');

        // Check if both startDate and endDate are provided
        if (!empty($startDate) && !empty($endDate)) {
            $data->whereBetween('kategorishift.date', [$startDate, $endDate]);
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->setRowId(function ($data) {
                return $data->id; // Set ID baris jika diperlukan
            })
            ->make(true);
    }






    public function store(Request $request)
    {

        $request->validate([
            'npk' => 'required|array', // Memastikan npk adalah array
            'shift1' => 'required', // Minimal input untuk shift hari kerja
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Parse tanggal dari input
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        // Loop untuk setiap hari antara start_date dan end_date
        while ($startDate->lte($endDate)) {
            foreach ($request->input('npk') as $npk) { // Loop untuk setiap NPK yang dipilih
                $data = $request->except(['start_date', 'end_date']); // Mengabaikan kolom start_date dan end_date
                $data['npk'] = $npk; // Menyimpan NPK yang dipilih
                $data['date'] = $startDate->toDateString(); // Set tanggal harian

                // Simpan data ke tabel Shift
                Shift::create($data);
            }
            // Lanjut ke hari berikutnya
            $startDate->addDay();
        }

        // Cek hari setelah end_date untuk menambahkan Sabtu dan Minggu sebagai off
        while ($startDate->dayOfWeek == 6 || $startDate->dayOfWeek == 0) {
            foreach ($request->input('npk') as $npk) { // Loop untuk setiap NPK yang dipilih
                $data = [
                    'npk' => $npk,
                    'date' => $startDate->toDateString(),
                    'shift1' => 'OFF', // Menyimpan status OFF untuk akhir pekan
                ];

                // Simpan data untuk hari Sabtu atau Minggu setelah end_date
                Shift::create($data);
            }

            // Lanjut ke hari berikutnya
            $startDate->addDay();
        }

        // Return response success
        return response()->json(['success' => 'Data berhasil disimpan']);
    }

    public function edit($id)
    {
        $shift = shift::find($id);

        if ($shift) {
            return response()->json(['result' => $shift]);
        } else {
            return response()->json(['result' => null], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'npk' => 'required',
            'shift1' => 'required',
            'date' => 'required',
            'status' => 'required',
        ]);

        // Temukan data shift berdasarkan ID untuk mendapatkan detail awal
        $shift = Shift::find($id);

        if (!$shift) {
            return back()->with('error', 'Shift tidak ditemukan');
        }


        // Update data spesifik berdasarkan ID (termasuk shift dan tanggal)
        $shift->update([
            'npk' => $request->npk,
            'shift1' => $request->shift1,
            'date' => $request->date,
            'status' => $request->status,
        ]);
    }

    public function destroy($id)
    {
        shift::where('id', $id)->delete();
    }

    public function importProcess(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx|max:2048', // validasi hanya menerima file xlsx dengan ukuran maksimal 2MB
        ]);
        $file = $request->file('file');
        Excel::import(new ShiftsImport, $file);

        return redirect()->back()->with('success', 'File berhasil diunggah.');
    }
    public function getKaryawan()
    {
        $userData = User::select('nama', 'npk')->get();

        return view('shift.shift', compact('userData'));
    }
}
