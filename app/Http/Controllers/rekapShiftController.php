<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\shift;
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
use Illuminate\Support\Facades\Auth;
use App\Exports\ShiftExport;
use App\Exports\templateExport;
use App\Models\MasterShift;

class rekapShiftController extends Controller
{
    public function index()
    {
        return view('shift.rekapshift');
    }




    public function getData(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $data = shift::select([
            'kategorishift.npk',
            'kategorishift.shift1',
            'kategorishift.date',
            'latest_shift.latest_created_at',
            'master_shift.shift_name' // Menambahkan shift_name dari master_shift
        ])
            ->with(['user.department'])
            ->join('users', 'kategorishift.npk', '=', 'users.npk')
            // Subquery untuk mendapatkan shift terbaru berdasarkan npk, date, dan created_at
            ->join(DB::raw('(
            SELECT npk, date, MAX(created_at) as latest_created_at
            FROM kategorishift
            GROUP BY npk, date
        ) AS latest_shift'), function ($join) {
                $join->on('kategorishift.npk', '=', 'latest_shift.npk')
                    ->on('kategorishift.date', '=', 'latest_shift.date')
                    ->on('kategorishift.created_at', '=', 'latest_shift.latest_created_at');
            })
            ->join('master_shift', 'kategorishift.shift1', '=', 'master_shift.waktu')
            ->distinct()
            ->orderBy('kategorishift.date', 'DESC')
            ->whereBetween('kategorishift.date', [$startDate, $endDate])
            ->get();

        // Mengelompokkan data berdasarkan date, shift1, dan department_id
        $groupedData = $data->groupBy(function ($item) {
            return $item->date . '-' . $item->shift_name . '-' . $item->department_id;
        });

        // Menghitung jumlah untuk setiap grup
        $result = $groupedData->map(function ($group) {
            return [
                'date' => $group->first()->date,
                'shift_name' => $group->first()->shift_name,
                'department_nama' => $group->first()->user->department->nama,
                'shiftcount' => $group->count(),
                'npkCount' => $group->pluck('npk')->unique()->count(),
            ];
        });

        // Mengembalikan data yang sudah dikelompokkan dan dihitung
        return response()->json($result->values());
    }
}
