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
use Illuminate\Support\Facades\Auth;
use App\Exports\ShiftExport;
use App\Exports\templateExport;
use App\Models\MasterShift;

class shiftController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roleId = $user->role_id;
        $sectionId = $user->section_id;
        $departmentId = $user->department_id;

        $query = User::select('nama', 'npk');

        if ($roleId == 2) {
            $query->where('section_id', $sectionId);
        } else if ($roleId == 9) {
            $query->where('department_id', $departmentId);
        }

        $userData = $query->get();
        $masterShift = MasterShift::pluck('waktu');

        return view('shift.shift', compact('userData', 'masterShift'));
    }

    // public function getData(Request $request)
    // {
    //     $startDate = $request->input('startDate');
    //     $endDate = $request->input('endDate');

    //     // Mengambil data `shift1` dengan `created_at` terbaru untuk setiap `npk` dan `tanggal`
    //     $data = Shift::select([
    //         'kategorishift.id',
    //         'kategorishift.npk',
    //         'kategorishift.shift1',
    //         'kategorishift.date',
    //         'users.nama'
    //     ])
    //         ->join('users', 'kategorishift.npk', '=', 'users.npk')
    //         ->where(function ($query) use ($startDate, $endDate) {
    //             if (!empty($startDate) && !empty($endDate)) {
    //                 $query->whereBetween('kategorishift.date', [$startDate, $endDate]);
    //             }
    //         })
    //         ->whereIn('kategorishift.created_at', function ($query) {
    //             $query->selectRaw('MAX(created_at)')
    //                 ->from('kategorishift as ks')
    //                 ->whereColumn('ks.npk', 'kategorishift.npk')
    //                 ->whereColumn('ks.date', 'kategorishift.date')
    //                 ->groupBy('ks.npk', 'ks.date');
    //         })
    //         ->orderBy('kategorishift.date', 'ASC');

    //     $user = Auth::user();
    //     $roleId = $user->role_id;

    //     if ($request->has('selected_npk') && !empty($request->selected_npk)) {
    //         $selectedNPKs = explode(',', $request->selected_npk);
    //         $data->whereIn('users.npk', $selectedNPKs);
    //     }

    //     // Pengecekan role_id
    //     if ($roleId == 2) {
    //         $sectionId = $user->section_id;
    //         $data->where('users.section_id', $sectionId);
    //     }

    //     return DataTables::of($data)
    //         ->addIndexColumn()
    //         ->setRowId(function ($data) {
    //             return $data->id;
    //         })
    //         ->make(true);
    // }
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

        $user = Auth::user();
        $roleId = $user->role_id;

        if ($request->has('selected_npk') && !empty($request->selected_npk)) {
            $selectedNPKs = explode(',', $request->selected_npk);
            $data->whereIn('users.npk', $selectedNPKs);
        }

        if (!empty($startDate) && !empty($endDate)) {
            $data->whereBetween('kategorishift.date', [$startDate, $endDate]);
        }

        // Pengecekan role_id
        if ($roleId == 2) {
            $sectionId = $user->section_id;
            $data->where('users.section_id', $sectionId);
        }

        // Mengambil shift terbaru berdasarkan npk dan tanggal
        foreach ($data->get() as $checkin) {
            $latestShift = Shift::where('npk', $checkin->npk)
                ->where('date', $checkin->date) // Menggunakan date dari $checkin
                ->latest()
                ->first();

            $checkin->latest_shift = $latestShift ? $latestShift->shift1 : null; // Menambahkan shift terbaru ke objek checkin
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->setRowId(function ($data) {
                return $data->id;
            })
            ->make(true);
    }


    public function exportData(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $selectedNPKs = $request->input('selected_npk') ? explode(',', $request->input('selected_npk')) : [];


        $dataQuery = Shift::with(['user.section', 'user.department', 'user.division'])
            ->select(['kategorishift.id', 'kategorishift.npk', 'kategorishift.shift1', 'kategorishift.date'])
            ->join('users', 'kategorishift.npk', '=', 'users.npk')
            ->orderBy('kategorishift.date', 'ASC');

        if (!empty($selectedNPKs)) {
            $dataQuery->whereIn('users.npk', $selectedNPKs);
        }

        // Tambahkan filter untuk rentang tanggal
        if (!empty($startDate) && !empty($endDate)) {
            $dataQuery->whereBetween('kategorishift.date', [$startDate, $endDate]);
        }


        $data = $dataQuery->get();

        if ($data->isEmpty()) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Siapkan data untuk ekspor
        $exportData = [];
        foreach ($data as $shift) {
            $user = $shift->user;
            $exportData[] = [
                'NPK' => $shift->npk,
                'Nama' => $user->nama,
                'Tanggal' => $shift->date,
                'Shift' => $shift->shift1,
                'Section' => $user->section ? $user->section->nama : 'Tidak Ada',
                'Department' => $user->department ? $user->department->nama : 'Tidak Ada',
                'Division' => $user->division ? $user->division->nama : 'Tidak Ada',
            ];
        }

        return Excel::download(new ShiftExport($exportData), 'shift_data.xlsx');
    }

    public function templateExport()
    {
        $user = Auth::user();
        $roleId = $user->role_id;
        $sectionId = $user->section_id;
        $departmentId = $user->department_id; // Ambil department_id pengguna

        $query = User::select('npk', 'nama');

        if ($roleId == 2) {
            $query->where('section_id', $sectionId);
        } elseif ($roleId == 9) {
            $query->where('department_id', $departmentId);
        }

        $userData = $query->get();

        return Excel::download(new templateExport($userData), 'template.xlsx');
    }

    public function store(Request $request)
    {
        if (Auth::user()->role_id != 1) {
            $startDate = Carbon::parse($request->input('start_date'));
            $endDate = Carbon::parse($request->input('end_date'));

            $maxEndDate = $startDate->copy()->addDays(14);
            if ($endDate->gt($maxEndDate)) {
                return response()->json(['error' => 'Rentang tanggal tidak boleh lebih dari 2 minggu.'], 403);
            }

            if ($startDate->lte(Carbon::today())) {
                return response()->json(['error' => 'Anda tidak diizinkan membuat shift untuk hari ini atau sebelumnya.'], 403);
            }
        }

        $request->validate([
            'npk' => 'required|array',
            'shift1' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));

        // Loop melalui tanggal dan buat shift
        while ($startDate->lte($endDate)) {
            foreach ($request->input('npk') as $npk) {
                $data = $request->except(['start_date', 'end_date']);
                $data['npk'] = $npk;
                $data['date'] = $startDate->toDateString();

                // Dapatkan section_id dari pengguna yang sedang login
                $sectionId = Auth::user()->section_id;

                // Set shift1 sebagai 'OFF' di akhir pekan, kecuali hari Sabtu untuk section_id 22 dan 40
                if ($startDate->isWeekend()) {
                    if ($startDate->isSaturday() && in_array($sectionId, [22, 40])) {
                        // Biarkan shift tetap terisi untuk section_id 22 dan 40 pada hari Sabtu
                        $data['shift1'] = $request->input('shift1');
                    } else {
                        // Set 'OFF' untuk hari Sabtu dan Minggu lainnya
                        $data['shift1'] = 'OFF';
                    }
                }

                Shift::create($data);
            }
            $startDate->addDay();
        }

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
        $date = Carbon::parse($request->input('date'));
        $dayOfWeek = $date->dayOfWeek;

        // Cek jika role_id bukan 1 atau 6, dan hari adalah Sabtu (6) atau Minggu (0)
        if (Auth::user()->role_id != 1 && Auth::user()->role_id != 6 && ($dayOfWeek == 6 || $dayOfWeek == 0)) {
            return response()->json(['error' => 'Anda tidak diizinkan untuk mengedit data di hari Sabtu dan Minggu'], 403);
        }

        if (Auth::user()->role_id != 1 && $date->lt(Carbon::today())) {
            return response()->json(['error' => 'Anda tidak diizinkan untuk menyimpan shift yang sudah atau sedang berjalan'], 403);
        }

        $request->validate([
            'npk' => 'required',
            'shift1' => 'required',
            'date' => 'required|date'
        ]);

        // Buat data shift baru
        Shift::create([
            'npk' => $request->input('npk'),
            'shift1' => $request->input('shift1'),
            'date' => $request->input('date'),
        ]);

        return response()->json(['success' => 'Data berhasil disimpan']);
    }


    public function destroy($id)
    {
        shift::where('id', $id)->delete();
    }

    public function importProcess(Request $request)
    {
        set_time_limit(300);

        $request->validate([
            'file' => 'required|mimes:xlsx|max:2048', // validasi hanya menerima file xlsx dengan ukuran maksimal 2MB
        ]);

        try {
            $file = $request->file('file');
            Excel::import(new ShiftsImport, $file);

            // Set message sukses jika tidak ada error
            $successMessage = 'File berhasil diunggah.';
            return redirect()->back()->with('success', $successMessage); // Kembalikan dengan pesan sukses
        } catch (\Exception $e) {
            // Tangkap error dan set pesan error
            $errorMessage = $e->getMessage();
            return redirect()->back()->with('error', $errorMessage); // Kembalikan dengan pesan error
        }
    }



    public function getShiftHistory(Request $request)
    {
        $date = $request->query('date');
        $npk = $request->query('npk');

        $data = shift::select([
            'kategorishift.id',
            'kategorishift.npk',
            'kategorishift.shift1',
            'kategorishift.date',
            'users.nama'
        ])
            ->join('users', 'kategorishift.npk', '=', 'users.npk')
            ->where('kategorishift.date', $date)
            ->where('kategorishift.npk', $npk)
            ->orderBy('kategorishift.date', 'DESC')
            ->get();

        return response()->json([
            'draw' => 0,
            'recordsTotal' => $data->count(),
            'recordsFiltered' => $data->count(),
            'data' => $data,
        ]);
    }
}
