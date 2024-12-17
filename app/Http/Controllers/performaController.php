<?php

namespace App\Http\Controllers;

use App\Models\absensici;
use App\Models\shift;
use App\Models\User;
use App\Models\PcdLoginLog;
use App\Models\PcdLoginLogs;
use App\Models\SectionModel;
use Illuminate\Http\Request;
use App\Models\DivisionModel;
use App\Models\PcdMasterUser;
use App\Exports\performaExport;
use App\Models\DepartmentModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class performaController extends Controller
{
    public function index()
    {
        return view('performa.performa');
    }
    public function getData(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // Fetch absensici data
        $absensiciQuery = DB::connection('mysql')->table('absensici')
            ->select('absensici.npk', 'absensici.tanggal')
            ->whereNotNull('absensici.npk')
            ->distinct();

        if (!empty($startDate) && !empty($endDate)) {
            $absensiciQuery->whereBetween('absensici.tanggal', [$startDate, $endDate]);
        }

        $absensiciData = $absensiciQuery->get();

        // Fetch user1 data
        $user1Data = DB::connection('mysql')->table('users as user1')
            ->select('user1.npk', 'user1.nama', 'user1.section_id', 'user1.department_id', 'user1.division_id')
            ->whereIn('user1.section_id', [31, 25])
            ->get();

        // Fetch categorishift data
        $categorishiftData = DB::connection('mysql')->table('kategorishift')
            ->select(['kategorishift.npk', DB::raw('kategorishift.date AS tanggal'), 'kategorishift.shift1'])
            ->get();

        // Fetch pcd_users data
        $pcdUsersData = DB::connection('mysql2')->table('pcd_master_users as pcd_users')
            ->select('pcd_users.npk', 'pcd_users.id')
            ->where('pcd_users.usergroup', '=', 'operator') // Add this line
            ->get();


        // Fetch first_login data
        $firstLoginData = DB::connection('mysql2')->table(DB::raw('(SELECT user_id, DATE(created_at) as tanggal, MIN(created_at) AS first_login_time, MAX(station_id) AS station_id FROM autoplastik.pcd_login_logs GROUP BY user_id, DATE(created_at)) AS first_login'))
            ->select('first_login.user_id', 'first_login.tanggal', 'first_login.first_login_time', 'first_login.station_id')
            ->get();

        // Fetch first_checkin data
        $firstCheckinData = DB::connection('mysql')->table(DB::raw('(SELECT npk, tanggal, MIN(waktuci) AS waktuci FROM absensici GROUP BY npk, tanggal) AS first_checkin'))
            ->select('first_checkin.npk', 'first_checkin.tanggal', 'first_checkin.waktuci')
            ->get();

        $data = [];
        foreach ($absensiciData as $item) {
            $user1 = $user1Data->firstWhere('npk', $item->npk);
            $categorishift = $categorishiftData->firstWhere('npk', $item->npk);
            $pcdUser = $pcdUsersData->firstWhere('npk', $item->npk);
            $firstLogin = $firstLoginData->firstWhere(function ($login) use ($item, $pcdUser) {
                if (!$pcdUser || !$pcdUser->id) {
                    return false;
                }
                return $login->user_id === $pcdUser->id && $login->tanggal === $item->tanggal;
            });
            $firstCheckin = $firstCheckinData->firstWhere(function ($checkin) use ($item) {
                return $checkin->npk === $item->npk && $checkin->tanggal === $item->tanggal;
            });

            if ($user1 && $firstLogin && $firstCheckin) {
                $item->nama = $user1->nama;
                $item->section_id = $user1->section_id;
                $item->department_id = $user1->department_id;
                $item->division_id = $user1->division_id;
                $item->shift1 = $categorishift ? $categorishift->shift1 : null;
                $item->waktuci_checkin = $firstCheckin ? $firstCheckin->waktuci : null;
                $item->waktu_login_dashboard = $firstLogin ? \Carbon\Carbon::parse($firstLogin->first_login_time)->format('H:i:s') : null;
                $item->station_id = $firstLogin->station_id;

                // Calculate time difference
                $shiftStartTime = $categorishift ? \Carbon\Carbon::createFromFormat('H:i', explode(' - ', $categorishift->shift1)[0]) : null;
                $firstLoginTime = $firstLogin ? \Carbon\Carbon::parse($firstLogin->first_login_time) : null;
                $item->selisih_waktu = $shiftStartTime && $firstLoginTime
                    ? $shiftStartTime->diffInMinutes($firstLoginTime) * ($shiftStartTime < $firstLoginTime ? -1 : 1)
                    : null;

                // Fetch section, department, and division names
                $section = SectionModel::find($item->section_id);
                $department = $section ? DepartmentModel::find($section->department_id) : null;
                $division = $department ? DivisionModel::find($department->division_id) : null;

                $item->section_nama = $section ? $section->nama : 'Unknown';
                $item->department_nama = $department ? $department->nama : 'Unknown';
                $item->division_nama = $division ? $division->nama : 'Unknown';

                $data[] = $item;
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => 'Data fetched successfully',
        ]);
    }
    // public function getData(Request $request)
    // {
    //     $startDate = $request->input('startDate');
    //     $endDate = $request->input('endDate');

    //     //   // Fetch npk values from absensici table
    //     // $absensiciNpk = DB::connection('mysql')
    //     //     ->table('absensici')
    //     //     ->select('npk')
    //     //     ->get()
    //     //     ->pluck('npk')
    //     //     ->toArray(); // Convert to array

    //     // // Fetch npk values from pcd_master_users table
    //     // $pcdUsersNpk = DB::connection('mysql2')
    //     //     ->table('pcd_master_users as pcd_users')
    //     //     ->select('pcd_users.npk')
    //     //     ->get()
    //     //     ->pluck('npk')
    //     //     ->toArray(); // Convert to array

    //     // // Find npk values that exist in absensici but not in pcd_master_users
    //     // $missingNpk = array_diff($absensiciNpk, $pcdUsersNpk);


    //     // // You can return the missing npk values or log them
    //     // return $missingNpk;


    //     // Fetch absensici data
    //     $absensiciQuery = DB::connection('mysql')->table('absensici')
    //         ->select(
    //             'absensici.npk',
    //             'absensici.tanggal'
    //         )
    //         ->whereNotNull('absensici.npk')
    //         ->distinct();

    //     if (!empty($startDate) && !empty($endDate)) {
    //         $absensiciQuery->whereBetween('absensici.tanggal', [$startDate, $endDate]);
    //     }

    //     $absensiciData = $absensiciQuery->paginate(1000);

    //     // Fetch user1 data
    //     $user1Data = DB::connection('mysql')->table('users as user1')
    //         ->select('user1.npk', 'user1.nama', 'user1.section_id', 'user1.department_id', 'user1.division_id')
    //         ->whereIn('user1.section_id', [31, 25])
    //         ->get();

    //     // Fetch categorishift data
    //     $categorishiftData = DB::connection('mysql')->table('kategorishift')
    //         ->select(['kategorishift.npk', DB::raw('kategorishift.date AS tanggal'), 'kategorishift.shift1'])
    //         ->get();

    //     // Fetch pcd_users data
    //     $pcdUsersData = DB::connection('mysql2')->table('pcd_master_users as pcd_users')
    //         ->select('pcd_users.npk', 'pcd_users.id')
    //         ->get();

    //     // Fetch first_login data
    //     $firstLoginData = DB::connection('mysql2')->table(DB::raw('(SELECT user_id, DATE(created_at) as tanggal, MIN(created_at) AS first_login_time, MAX(station_id) AS station_id FROM autoplastik.pcd_login_logs GROUP BY user_id, DATE(created_at)) AS first_login'))
    //         ->select('first_login.user_id', 'first_login.tanggal', 'first_login.first_login_time', 'first_login.station_id')
    //         ->get();


    //     // Fetch first_checkin data
    //     $firstCheckinData = DB::connection('mysql')->table(DB::raw('(SELECT npk, tanggal, MIN(waktuci) AS waktuci FROM absensici GROUP BY npk, tanggal) AS first_checkin'))
    //         ->select('first_checkin.npk', 'first_checkin.tanggal', 'first_checkin.waktuci')
    //         ->get();


    //     $data = [];
    //     foreach ($absensiciData as $item) {
    //         $user1 = $user1Data->firstWhere('npk', $item->npk);
    //         $categorishift = $categorishiftData->firstWhere('npk', $item->npk);
    //         $pcdUser = $pcdUsersData->firstWhere('npk', $item->npk);
    //         $firstLogin = $firstLoginData->firstWhere(function ($login) use ($item, $pcdUser) {
    //             if (! $pcdUser || ! $pcdUser->id) {
    //                 return false;
    //             }
    //             return $login->user_id === $pcdUser->id && $login->tanggal === $item->tanggal;
    //         });
    //         $firstCheckin = $firstCheckinData->firstWhere(function ($checkin) use ($item) {
    //             return $checkin->npk === $item->npk && $checkin->tanggal === $item->tanggal;
    //         });

    //         if ($user1 && $firstLogin && $firstCheckin) {
    //             $item->nama = $user1->nama;
    //             $item->section_id = $user1->section_id;
    //             $item->department_id = $user1->department_id;
    //             $item->division_id = $user1->division_id;
    //             $item->shift1 = $categorishift ? $categorishift->shift1 : null;
    //             $item->waktuci_checkin = $firstCheckin ? $firstCheckin->waktuci : null;
    //             $item->waktu_login_dashboard = $firstLogin ? \Carbon\Carbon::parse($firstLogin->first_login_time)->format('H:i:s') : null;
    //             $item->station_id = $firstLogin->station_id;

    //             // Calculate time difference
    //             $shiftStartTime = $categorishift ? \Carbon\Carbon::createFromFormat('H:i', explode(' - ', $categorishift->shift1)[0]) : null;
    //             $firstLoginTime = $firstLogin ? \Carbon\Carbon::parse($firstLogin->first_login_time) : null;
    //             $item->selisih_waktu = $shiftStartTime && $firstLoginTime ? $shiftStartTime->diffInMinutes($firstLoginTime) : null;

    //             // Fetch section, department, and division names
    //             $section = SectionModel::find($item->section_id);
    //             $department = $section ? DepartmentModel::find($section->department_id) : null;
    //             $division = $department ? DivisionModel::find($department->division_id) : null;

    //             $item->section_nama = $section ? $section->nama : 'Unknown';
    //             $item->department_nama = $department ? $department->nama : 'Unknown';
    //             $item->division_nama = $division ? $division->nama : 'Unknown';

    //             $data[] = $item;
    //         }
    //     }

    //     foreach ($data as $item) {
    //         $section = SectionModel::find($item->section_id);
    //         $department = $section ? DepartmentModel::find($section->department_id) : null;
    //         $division = $department ? DivisionModel::find($department->division_id) : null;

    //         $item->section_nama = $section ? $section->nama : 'Unknown';
    //         $item->department_nama = $department ? $department->nama : 'Unknown';
    //         $item->division_nama = $division ? $division->nama : 'Unknown';
    //     }

    //     return DataTables::of($data)
    //         ->addIndexColumn()
    //         ->addColumn('aksi', function ($row) {
    //             $btn = '<button class="btn btn-primary btn-sm btnDetail"
    //         data-nama="' . e($row->nama) . '"
    //         data-npk="' . e($row->npk) . '"
    //         data-tanggal="' . e($row->tanggal) . '"  
    //         data-section="' . e($row->section_nama) . '"
    //         data-department="' . e($row->department_nama) . '"
    //         data-division="' . e($row->division_nama) . '"> 
    //         Detail
    //     </button>';
    //             return $btn;
    //         })
    //         ->rawColumns(['aksi'])
    //         ->make(true);
    // }




    public function storeLogs(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'created_at' => 'required',
        ]);

        PcdLoginLogs::create([
            'user_id' => $request->user_id,
            'created_at' => $request->created_at,
        ]);

        return response()->json(['success' => 'Check-in berhasil ditambahkan!']);
    }

    public function storeUserId(Request $request)
    {
        $request->validate([
            'id' => 'required',
            // 'nama' => 'required',
            'npk' => 'required',
        ]);

        PcdMasterUser::create([
            'id' => $request->id,
            // 'nama' => $request->nama,
            'npk' => $request->npk,
        ]);

        return response()->json(['success' => 'Check-in berhasil ditambahkan!']);
    }
    public function performaExport(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $search = $request->input('search'); // Ambil parameter search

        return Excel::download(new performaExport($startDate, $endDate, $search), 'Performa.xlsx');
    }
}
