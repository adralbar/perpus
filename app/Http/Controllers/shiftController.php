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
        $npk = $request->input('npk');
        $date = $request->input('date');

        $data = Shift::select([
            'kategorishift.id',
            'kategorishift.npk',
            'kategorishift.shift1',
            'kategorishift.date',
            'users.nama'
        ])
            ->join('users', 'kategorishift.npk', '=', 'users.npk')
            ->orderBy('kategorishift.date', 'DESC');

        // Filter berdasarkan npk dan date
        if (!empty($npk)) {
            $data->where('kategorishift.npk', $npk);
        }
        if (!empty($date)) {
            $data->where('kategorishift.date', $date);
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

    public function store2(Request $request)
    {

        $request->validate([
            'npk' => 'required', // Memastikan npk adalah array
            'shift1' => 'required', // Minimal input untuk shift hari kerja
            'date' => 'required'
        ]);


        shift::create([

            'npk' => $request->input('npk'),
            'shift1' => $request->input('shift1'),
            'date' => $request->input('date'),
        ]);

        // Return response success
        return response()->json(['success' => 'Data berhasil disimpan']);
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
    public function getShiftHistory(Request $request)
    {

        $data = shift::select([
            'kategorishift.id',
            'kategorishift.npk',
            'kategorishift.shift1',
            'kategorishift.date',
            'users.nama'
        ])
            ->join('users', 'kategorishift.npk', '=', 'users.npk')
            ->orderBy('kategorishift.date', 'DESC');

        $date = $request->query('date');
        $npk = $request->query('npk');

        // Ambil data shift berdasarkan tanggal dan npk
        $data = shift::where('date', $date)
            ->where('npk', $npk)
            ->get();

        return response()->json([
            'draw' => 0,
            'recordsTotal' => $data->count(),
            'recordsFiltered' => $data->count(),
            'data' => $data,
        ]);
    }
}
