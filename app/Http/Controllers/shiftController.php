<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Shift;
use Illuminate\Http\Request;
use App\Imports\ShiftsImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class shiftController extends Controller
{
    public function index()
    {
        return view('shift.shift');
    }

    public function getData(Request $request)
    {
        $data = shift::select([
            'kategorishift.id',
            'kategorishift.npk',
            'kategorishift.shift1',
            'kategorishift.date',
            'users.nama' // assuming the user's name is stored in the 'name' column
        ])
            ->join('users', 'kategorishift.npk', '=', 'users.npk') // join with the users table
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }





    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'npk' => 'required',
            'shift1' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Parse tanggal dari input
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        // Loop untuk setiap hari antara start_date dan end_date
        while ($startDate->lte($endDate)) {
            $data = $request->except(['start_date', 'end_date']); // Mengabaikan kolom start_date dan end_date
            $data['date'] = $startDate->toDateString(); // Set tanggal harian

            // Simpan data ke tabel kategorishift
            Shift::create($data);

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
}
