<?php

namespace App\Http\Controllers;

use App\Models\MasterShift;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MasterShiftController extends Controller
{
    public function index(Request $request)
    {
        // dd($request);
        if ($request->ajax()) {
            $data = MasterShift::all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($row) {
                    return '
                        <button class="btn btn-sm btn-warning" onclick="openEditModal(' . $row->id . ')">Edit</button>
                        <form method="POST" action="' . route('master-shift.destroy', $row->id) . '" style="display:inline;">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button class="btn btn-sm btn-danger btn-delete">Delete</button>
                        </form>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('master_shift.index');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'shift_name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);

        // Gabungkan start_time dan end_time menjadi waktu
        $waktu = $request->start_time . ' - ' . $request->end_time;

        // Simpan data ke database
        MasterShift::create([
            'shift_name' => $request->shift_name,
            'waktu' => $waktu, // Simpan waktu gabungan
        ]);

        return redirect()->back()->with('success', 'Shift berhasil ditambahkan!');
    }


    public function edit($id)
    {
        $shift = MasterShift::findOrFail($id);
        return response()->json($shift);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'shift_name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);

        // Gabungkan start_time dan end_time menjadi waktu
        $waktu = $request->start_time . ' - ' . $request->end_time;

        // Temukan data berdasarkan ID
        $shift = MasterShift::findOrFail($id);

        // Update data
        $shift->update([
            'shift_name' => $request->shift_name,
            'waktu' => $waktu, // Perbarui waktu gabungan
        ]);

        return back()->with('success', 'Shift berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $shift = MasterShift::findOrFail($id);
        $shift->delete();

        return back()->with('success', 'Shift berhasil dihapus!');
    }
}
