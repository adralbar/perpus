<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift; // Perhatikan huruf kapital pada nama model
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ShiftsImport;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        return view('shift.shift');
    }

    public function getData(Request $request)
    {
        $data = Shift::select(['id', 'nama', 'npk', 'divisi', 'departement', 'section', 'shift1', 'shift2', 'shift3', 'status']);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
                    <button class="btn btn-sm btn-info" onclick="editShift(' . $row->id . ')">Edit</button>
                    <button class="btn btn-sm btn-danger" onclick="deleteShift(' . $row->id . ')">Delete</button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'npk' => 'required|string',
            'divisi' => 'required|string',
            'departement' => 'required|string',
            'section' => 'required|string',
            'shift1' => 'required|string',
            'shift2' => 'nullable|string',
            'shift3' => 'nullable|string',
            'status' => 'required|string',
        ]);

        Shift::create($request->all());

        return response()->json(['success' => 'Data berhasil disimpan']);
    }

    public function edit($id)
    {
        $shift = Shift::find($id);
        return response()->json($shift);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string',
            'npk' => 'required|string',
            'divisi' => 'required|string',
            'departement' => 'required|string',
            'section' => 'required|string',
            'shift1' => 'required|string',
            'shift2' => 'nullable|string',
            'shift3' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $shift = Shift::find($id);
        $shift->update($request->all());

        return response()->json(['success' => 'Data berhasil diperbarui']);
    }

    public function destroy($id)
    {
        Shift::destroy($id);
        return response()->json(['success' => 'Data berhasil dihapus']);
    }

    public function importProcess(Request $request)
    {
        $file = $request->file('file');
        Excel::import(new ShiftsImport, $file);

        return redirect()->back()->with('success', 'Data imported successfully.');
    }
}
