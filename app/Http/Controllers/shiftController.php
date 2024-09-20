<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ShiftsImport;
use Illuminate\Support\Facades\DB;

class shiftController extends Controller
{
    public function index()
    {
        return view('shift.shift');
    }

    public function getData(Request $request)
    {
        $data = Shift::select([
            'id',
            'nama',
            'npkSistem',
            'npk',
            'divisi',
            'departement',
            'section',
            'shift1',
            // Menggabungkan start_date dan end_date menjadi satu kolom 'tanggal'
            DB::raw("CONCAT(start_date, ' - ', end_date) as tanggal"),
            'status'

        ])->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'npkSistem' => 'required',
            'npk' => 'required',
            'divisi' => 'required',
            'departement' => 'required',
            'section' => 'required',
            'shift1' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'status' => 'required',
        ]);

        shift::create($request->all());

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
            'nama' => 'required',
            'npkSistem' => 'required',
            'npk' => 'required',
            'divisi' => 'required',
            'departement' => 'required',
            'section' => 'required',
            'shift1' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'status' => 'required',
        ]);

        // Memperbarui data dengan array key-value
        shift::where('id', $id)->update([
            'nama' => $request->nama,
            'npkSistem' => $request->npkSistem,
            'npk' => $request->npk,
            'divisi' => $request->divisi,
            'departement' => $request->departement,
            'section' => $request->section,
            'shift1' => $request->shift1,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
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
