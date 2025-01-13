<?php

namespace App\Http\Controllers;

use App\Models\absensici;
use App\Models\User;
use App\Models\RoleModel;
use App\Models\SectionModel;
use Illuminate\Http\Request;
use App\Models\DivisionModel;
use App\Models\DepartmentModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PcdMasterUser;

use App\Models\absensico;
use App\Models\RecapAbsensi;
use App\Models\Shift;

class UsersController extends Controller
{

    public function index()
    {

        $userData = User::with('division', 'department', 'section', 'role')->orderBy('created_at', 'DESC')->get();
        $role = RoleModel::all();
        $section = SectionModel::all(); // Ambil semua section
        $department = DepartmentModel::all(); // Ambil semua departemen
        $division = DivisionModel::all(); // Ambil semua division
        return view('user.index', compact('userData', 'role', 'section', 'department', 'division'));
    }
    public function getDepartmentAndDivision(Request $request)
    {
        $section_id = $request->section_id;

        // Ambil data section beserta relasi department dan division
        $section = SectionModel::with('department.division')->find($section_id);

        if ($section && $section->department && $section->department->division) {
            return response()->json([
                'department' => [
                    'id' => $section->department->id,
                    'nama' => $section->department->nama,
                ],
                'division' => [
                    'id' => $section->department->division->id,
                    'nama' => $section->department->division->nama,
                ]
            ]);
        }

        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }

    public function store(Request $request)
    {
        try {
            $request->validate(
                [
                    'npk_sistem' => 'required',
                    'npk' => 'required|unique:users,npk',
                    'nama' => 'required',
                    'password' => 'required',
                    'no_telp' => 'required',
                    'section_id' => 'required',
                    'department_id' => 'required',
                    'division_id' => 'required',
                    'role_id' => 'required',
                    'status' => 'nullable',
                ],
                [
                    'npk_sistem.required' => 'NPK Sistem wajib diisi.',
                    'npk.required' => 'NPK wajib diisi.',
                    'npk.unique' => 'NPK sudah terdaftar.',
                    'nama.required' => 'Nama wajib diisi.',
                    'password.required' => 'Password wajib diisi.',
                    'no_telp.required' => 'Nomor telepon wajib diisi.',
                    'section_id.required' => 'Section wajib dipilih.',
                    'department_id.required' => 'Departemen wajib dipilih.',
                    'division_id.required' => 'Divisi wajib dipilih.',
                    'role_id.required' => 'Role wajib dipilih.',
                ]
            );

            $data = [
                'npk_sistem' => $request->npk_sistem,
                'npk' => $request->npk,
                'nama' => $request->nama,
                'password' => bcrypt($request->password),
                'no_telp' => $request->no_telp,
                'section_id' => $request->section_id,
                'department_id' => $request->department_id,
                'division_id' => $request->division_id,
                'role_id' => $request->role_id,
                'status' => $request->status,
            ];

            User::create($data);

            // Jika berhasil
            return redirect('user')->with('success', 'User berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Jika terjadi error
            return redirect('user')->with('error', 'Terjadi kesalahan saat menambahkan user: ' . $e->getMessage());
        }
    }


    public function detail($npk)
    {
        $user = User::where('npk', $npk)->firstOrFail();
        $division = DivisionModel::find($user->division_id); // Ambil semua division
        $department = DepartmentModel::find($user->department_id);
        $section = SectionModel::find($user->section_id); // Ambil semua section
        $role = RoleModel::find($user->role_id);

        return view('user.detail', [
            'user' => $user,
            'division' => $division,
            'department' => $department,
            'section' => $section,
            'role' => $role,
        ]);
    }

    public function edit($npk)
    {
        $user = User::where('npk', $npk)->firstOrFail();

        // Ambil semua division, department, section, dan role
        $division = DivisionModel::all(); // Ambil semua division
        $department = DepartmentModel::all(); // Ambil semua department
        $section = SectionModel::all(); // Ambil semua section
        $role = RoleModel::all(); // Ambil semua role

        return view('user.update', [
            'user' => $user,
            'division' => $division,
            'department' => $department,
            'section' => $section,
            'role' => $role
        ]);
    }

    public function update(Request $request, $npk)
    {
        $request->validate([
            'nama' => 'required',
            'password' => 'nullable|min:6',
            'no_telp' => 'required',
            'division_id' => 'required',
            'department_id' => 'required',
            'section_id' => 'required',
            'role_id' => 'required',
            'status' => 'nullable',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'no_telp.required' => 'no_telp wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'Division_id.required' => 'Division wajib dipilih.',
            'department_id.required' => 'Departemen wajib dipilih.',
            'Section_id.required' => 'Section wajib dipilih.',
            'role_id.required' => 'Role wajib dipilih.',
        ]);
        $user = User::where('npk', $npk)->firstOrFail();
        $user->nama = $request->nama;
        $user->no_telp = $request->no_telp;
        $user->division_id = $request->division_id;
        $user->department_id = $request->department_id;
        $user->section_id = $request->section_id;
        $user->role_id = $request->role_id;
        $user->status = $request->status;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect('/user')->with('success', 'Data User berhasil diperbarui.');
    }

    public function destroy($npk)
    {
        $user = User::where('npk', $npk)->firstOrFail();
        $user->delete();

        return redirect('/user')->with('success', 'Data User berhasil dihapus.');
    }


    public function karyawandata()
    {
        $userData = User::with('division', 'department', 'section', 'role')->orderBy('created_at', 'DESC')->get();
        $role = RoleModel::all();
        $section = SectionModel::all(); // Ambil semua section
        $department = DepartmentModel::all(); // Ambil semua departemen
        $division = DivisionModel::all(); // Ambil semua division

        return response()->json([
            'userData' => $userData,
            'roles' => $role,
            'sections' => $section,
            'departments' => $department,
            'divisions' => $division
        ]);
    }

    public function export()
    {
        $user = Auth::user();
        return Excel::download(new UsersExport($user->role_id, $user->section_id), 'users.xlsx');
    }
}
